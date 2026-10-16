(async function () {
    if (!window.THREE || !window.FazoGeometry) return;
    const THREE = window.THREE;
    const { createSolid } = window.FazoGeometry;

    document.querySelectorAll('[data-solid-preview]').forEach((el) => {
        const type = el.dataset.solidPreview;

        const canvas = document.createElement('canvas');
        el.appendChild(canvas);

        const scene = new THREE.Scene();
        scene.background = new THREE.Color(0x1e4d8c);

        const camera = new THREE.PerspectiveCamera(40, 1, 0.1, 50);
        camera.position.set(2.4, 1.8, 2.8);
        camera.lookAt(0, 0, 0);

        const renderer = new THREE.WebGLRenderer({ canvas, antialias: true, alpha: true });
        renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, 2));

        scene.add(new THREE.HemisphereLight(0xffffff, 0x1e4d8c, 0.95));
        const dir = new THREE.DirectionalLight(0xffffff, 0.7);
        dir.position.set(3, 4, 2);
        scene.add(dir);

        const solid = createSolid(type, THREE, { color: 0xffffff, edge: 0xffd700 });
        scene.add(solid);

        function resize() {
            const w = el.clientWidth;
            const h = el.clientHeight;
            camera.aspect = w / Math.max(h, 1);
            camera.updateProjectionMatrix();
            renderer.setSize(w, h, false);
        }
        resize();
        window.addEventListener('resize', resize);

        let t = Math.random() * 10;
        function animate() {
            requestAnimationFrame(animate);
            t += 0.01;
            solid.rotation.y = t;
            solid.rotation.x = Math.sin(t * 0.7) * 0.2;
            renderer.render(scene, camera);
        }
        animate();
    });
})();
