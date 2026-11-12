(function () {
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

        const renderer = new THREE.WebGLRenderer({
            canvas,
            antialias: false,
            alpha: true,
            powerPreference: 'low-power',
        });
        renderer.setPixelRatio(1);

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
            renderer.render(scene, camera);
        }
        resize();
        window.addEventListener('resize', resize);

        let raf = 0;
        let spinning = false;

        function spin() {
            raf = 0;
            if (!spinning) return;
            solid.rotation.y += 0.012;
            renderer.render(scene, camera);
            raf = requestAnimationFrame(spin);
        }

        el.addEventListener('pointerenter', () => {
            spinning = true;
            if (!raf) raf = requestAnimationFrame(spin);
        });
        el.addEventListener('pointerleave', () => {
            spinning = false;
            if (raf) cancelAnimationFrame(raf);
            raf = 0;
            renderer.render(scene, camera);
        });
    });
})();
