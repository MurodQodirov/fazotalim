(function () {
    const canvas = document.getElementById('heroCanvas');
    if (!canvas) return;

    const labels = Object.assign({
        cube: 'Kub',
        pyramid: 'Piramida',
        cone: 'Konus',
        cylinder: 'Silindr',
        prism: 'Prizma',
    }, (window.FAZO && window.FAZO.shapes) || {});

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

        const mesh = createSolid('cube', THREE, { color: 0xffffff, edge: 0xffd700, opacity: 0.35 });
        mesh.scale.setScalar(1.28);
        scene.add(mesh);

        const orbit = enableOrbit(canvas, camera, { x: 0, y: 0, z: 0 }, () => renderer.render(scene, camera));
        orbit.reset();

        function resize() {
            const w = canvas.clientWidth || canvas.parentElement.clientWidth;
            const h = canvas.clientHeight || canvas.parentElement.clientHeight;
            camera.aspect = w / Math.max(h, 1);
            camera.updateProjectionMatrix();
            renderer.setSize(w, h, false);
            renderer.render(scene, camera);
        }

        window.addEventListener('resize', resize);
        resize();

        let visible = true;
        let raf = 0;
        let last = 0;

        function tick(now) {
            raf = 0;
            if (!visible || document.hidden) return;
            if (now - last < 50) {
                raf = requestAnimationFrame(tick);
                return;
            }
            last = now;
            mesh.rotation.y += 0.006;
            renderer.render(scene, camera);
            raf = requestAnimationFrame(tick);
        }

        function play() {
            if (!raf && visible && !document.hidden) {
                raf = requestAnimationFrame(tick);
            }
        }

        function stop() {
            if (raf) cancelAnimationFrame(raf);
            raf = 0;
        }

        if ('IntersectionObserver' in window) {
            const io = new IntersectionObserver((entries) => {
                visible = entries.some((e) => e.isIntersecting);
                if (visible) play();
                else stop();
            }, { threshold: 0.15 });
            io.observe(canvas);
        }

        document.addEventListener('visibilitychange', () => {
            if (document.hidden) stop();
            else play();
        });

        play();
    }

    if (window.THREE && window.FazoGeometry) {
        start();
    } else {
        window.addEventListener('load', start);
        setTimeout(start, 400);
    }
})();
