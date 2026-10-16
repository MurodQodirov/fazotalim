(function () {
    const canvas = document.getElementById('heroCanvas');
    if (!canvas) return;

    const theme = {
        paper: '#153a6b',
        stroke: '#ffffff',
        fill: 'rgba(255, 215, 0, 0.16)',
        grid: 'rgba(255, 255, 255, 0.1)',
    };

    function drawFallback() {
        const { drawProjectionSVG } = window.FazoGeometry || {};
        if (!drawProjectionSVG) return;
        ['heroFront', 'heroTop', 'heroSide'].forEach((id, i) => {
            const svg = document.getElementById(id);
            if (svg) drawProjectionSVG(svg, 'cube', ['front', 'top', 'side'][i], theme);
        });
    }

    function start() {
        drawFallback();
        if (!window.THREE || !window.FazoGeometry) return;

        const THREE = window.THREE;
        const { createSolid, createSceneBasics, enableOrbit } = window.FazoGeometry;
        const { scene, camera, renderer } = createSceneBasics(THREE, canvas, { bg: 0x1e4d8c });

        camera.position.set(3.4, 2.4, 4.2);
        const cube = createSolid('cube', THREE, { color: 0xffffff, edge: 0xffd700, opacity: 0.35 });
        cube.scale.setScalar(1.35);
        scene.add(cube);

        const orbit = enableOrbit(canvas, camera);
        orbit.reset();

        function resize() {
            const w = canvas.clientWidth || canvas.parentElement.clientWidth;
            const h = canvas.clientHeight || canvas.parentElement.clientHeight;
            camera.aspect = w / Math.max(h, 1);
            camera.updateProjectionMatrix();
            renderer.setSize(w, h, false);
        }

        window.addEventListener('resize', resize);
        resize();

        function animate() {
            requestAnimationFrame(animate);
            cube.rotation.y += 0.006;
            renderer.render(scene, camera);
        }
        animate();
    }

    if (window.THREE && window.FazoGeometry) {
        start();
    } else {
        window.addEventListener('load', start);
        setTimeout(start, 400);
    }
})();
