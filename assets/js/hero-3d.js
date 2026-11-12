(function () {
    const canvas = document.getElementById('heroCanvas');
    if (!canvas) return;

    const types = ['cube', 'pyramid', 'cone', 'cylinder', 'prism'];
    const labels = {
        cube: 'Kub',
        pyramid: 'Piramida',
        cone: 'Konus',
        cylinder: 'Silindr',
        prism: 'Prizma',
    };

    const theme = {
        paper: '#153a6b',
        stroke: '#ffffff',
        fill: 'rgba(255, 215, 0, 0.16)',
        grid: 'rgba(255, 255, 255, 0.1)',
    };

    function drawViews(type) {
        const { drawProjectionSVG } = window.FazoGeometry || {};
        if (!drawProjectionSVG) return;
        ['heroFront', 'heroTop', 'heroSide'].forEach((id, i) => {
            const svg = document.getElementById(id);
            if (svg) drawProjectionSVG(svg, type, ['front', 'top', 'side'][i], theme);
        });
        const tag = document.getElementById('heroShapeTag');
        if (tag) tag.textContent = labels[type] || type;
    }

    function start() {
        drawViews('cube');
        if (!window.THREE || !window.FazoGeometry) return;

        const THREE = window.THREE;
        const { createSolid, createSceneBasics, enableOrbit } = window.FazoGeometry;
        const { scene, camera, renderer } = createSceneBasics(THREE, canvas, { bg: 0x1e4d8c });

        camera.position.set(3.4, 2.4, 4.2);

        let index = 0;
        let mesh = createSolid(types[index], THREE, { color: 0xffffff, edge: 0xffd700, opacity: 0.35 });
        mesh.scale.setScalar(1.28);
        scene.add(mesh);
        drawViews(types[index]);

        const orbit = enableOrbit(canvas, camera);
        orbit.reset();

        let lastSwitch = performance.now();

        function swapSolid() {
            scene.remove(mesh);
            mesh.traverse((obj) => {
                if (obj.geometry) obj.geometry.dispose();
                if (obj.material) obj.material.dispose();
            });
            index = (index + 1) % types.length;
            mesh = createSolid(types[index], THREE, { color: 0xffffff, edge: 0xffd700, opacity: 0.35 });
            mesh.scale.setScalar(1.28);
            scene.add(mesh);
            drawViews(types[index]);
        }

        function resize() {
            const w = canvas.clientWidth || canvas.parentElement.clientWidth;
            const h = canvas.clientHeight || canvas.parentElement.clientHeight;
            camera.aspect = w / Math.max(h, 1);
            camera.updateProjectionMatrix();
            renderer.setSize(w, h, false);
        }

        window.addEventListener('resize', resize);
        resize();

        function animate(now) {
            requestAnimationFrame(animate);
            mesh.rotation.y += 0.008;
            mesh.rotation.x = Math.sin(now / 2400) * 0.12;
            if (now - lastSwitch > 5200) {
                lastSwitch = now;
                swapSolid();
            }
            renderer.render(scene, camera);
        }
        requestAnimationFrame(animate);
    }

    if (window.THREE && window.FazoGeometry) {
        start();
    } else {
        window.addEventListener('load', start);
        setTimeout(start, 400);
    }
})();
