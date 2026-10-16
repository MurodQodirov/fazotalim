(async function () {
    const canvas = document.getElementById('archCanvas');
    if (!canvas || !window.THREE) return;

    const THREE = window.THREE;
    const scene = new THREE.Scene();
    scene.background = new THREE.Color(0x1e4d8c);

    const camera = new THREE.PerspectiveCamera(45, 1, 0.1, 100);
    camera.position.set(8, 6, 10);

    const renderer = new THREE.WebGLRenderer({ canvas, antialias: true });
    renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, 2));

    scene.add(new THREE.HemisphereLight(0xffffff, 0x1e4d8c, 0.95));
    const sun = new THREE.DirectionalLight(0xffffff, 0.8);
    sun.position.set(6, 10, 4);
    scene.add(sun);

    const ground = new THREE.Mesh(
        new THREE.PlaneGeometry(30, 30),
            new THREE.MeshStandardMaterial({ color: 0x153a6b, roughness: 0.95, transparent: true, opacity: 0.45 })
    );
    ground.rotation.x = -Math.PI / 2;
    scene.add(ground);

    const grid = new THREE.GridHelper(30, 30, 0xffd700, 0x5a8fc4);
    if (Array.isArray(grid.material)) {
        grid.material.forEach((m) => { m.transparent = true; m.opacity = 0.25; });
    } else {
        grid.material.transparent = true;
        grid.material.opacity = 0.25;
    }
    scene.add(grid);

    const house = new THREE.Group();

    function boxWithEdges(w, h, d, x, y, z) {
        const g = new THREE.Group();
        const geo = new THREE.BoxGeometry(w, h, d);
        const mesh = new THREE.Mesh(
            geo,
            new THREE.MeshStandardMaterial({
                color: 0xffffff,
                transparent: true,
                opacity: 0.12,
                roughness: 0.7,
            })
        );
        const edges = new THREE.LineSegments(
            new THREE.EdgesGeometry(geo),
            new THREE.LineBasicMaterial({ color: 0xffd700 })
        );
        g.add(mesh, edges);
        g.position.set(x, y, z);
        return g;
    }

    house.add(boxWithEdges(6, 2.6, 4.5, 0, 1.3, 0));

    const roofGeo = new THREE.ConeGeometry(4.4, 1.8, 4);
    const roof = new THREE.Group();
    roof.add(new THREE.Mesh(
        roofGeo,
        new THREE.MeshStandardMaterial({ color: 0xffffff, transparent: true, opacity: 0.12, roughness: 0.6 })
    ));
    roof.add(new THREE.LineSegments(
        new THREE.EdgesGeometry(roofGeo),
        new THREE.LineBasicMaterial({ color: 0xffd700 })
    ));
    roof.position.y = 3.5;
    roof.rotation.y = Math.PI / 4;
    house.add(roof);

    house.add(boxWithEdges(1, 1.8, 0.12, 0, 0.9, 2.28));
    [[-1.8, 1.5, 2.28, 1.1, 1, 0.12], [1.8, 1.5, 2.28, 1.1, 1, 0.12], [-2.2, 1.5, 0, 0.12, 1, 1.1], [2.2, 1.5, 0, 0.12, 1, 1.1]].forEach(([x, y, z, w, h, d]) => {
        house.add(boxWithEdges(w, h, d, x, y, z));
    });
    house.add(boxWithEdges(0.5, 1.2, 0.5, 1.6, 3.9, -0.6));
    house.add(boxWithEdges(1.4, 0.08, 3, 0, 0.04, 3.8));

    scene.add(house);

    let dragging = false;
    let prev = { x: 0, y: 0 };
    let spherical = { radius: 14, theta: 0.7, phi: 1.05 };
    const target = new THREE.Vector3(0, 1.5, 0);

    function updateCam() {
        camera.position.x = target.x + spherical.radius * Math.sin(spherical.phi) * Math.sin(spherical.theta);
        camera.position.y = target.y + spherical.radius * Math.cos(spherical.phi);
        camera.position.z = target.z + spherical.radius * Math.sin(spherical.phi) * Math.cos(spherical.theta);
        camera.lookAt(target);
    }
    updateCam();

    canvas.addEventListener('pointerdown', (e) => {
        dragging = true;
        prev = { x: e.clientX, y: e.clientY };
    });
    canvas.addEventListener('pointerup', () => { dragging = false; });
    canvas.addEventListener('pointermove', (e) => {
        if (!dragging) return;
        spherical.theta -= (e.clientX - prev.x) * 0.01;
        spherical.phi = Math.min(Math.max(spherical.phi - (e.clientY - prev.y) * 0.01, 0.2), Math.PI / 2.1);
        prev = { x: e.clientX, y: e.clientY };
        updateCam();
    });
    canvas.addEventListener('wheel', (e) => {
        e.preventDefault();
        spherical.radius = Math.min(Math.max(spherical.radius + e.deltaY * 0.02, 8), 24);
        updateCam();
    }, { passive: false });

    document.querySelectorAll('[data-arch-view]').forEach((btn) => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('[data-arch-view]').forEach((b) => b.classList.remove('active'));
            btn.classList.add('active');
            const v = btn.dataset.archView;
            if (v === 'plan') spherical = { radius: 14, theta: 0, phi: 0.12 };
            else if (v === 'front') spherical = { radius: 14, theta: 0, phi: Math.PI / 2.2 };
            else if (v === 'side') spherical = { radius: 14, theta: Math.PI / 2, phi: Math.PI / 2.2 };
            else spherical = { radius: 14, theta: 0.7, phi: 1.05 };
            updateCam();
        });
    });

    function resize() {
        const box = canvas.parentElement;
        const w = canvas.clientWidth || box.clientWidth;
        const h = canvas.clientHeight || box.clientHeight;
        camera.aspect = w / Math.max(h, 1);
        camera.updateProjectionMatrix();
        renderer.setSize(w, h, false);
    }
    window.addEventListener('resize', resize);
    resize();

    function animate() {
        requestAnimationFrame(animate);
        renderer.render(scene, camera);
    }
    animate();
})();
