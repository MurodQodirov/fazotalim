/**
 * FazoTa'lim — Three.js (Yellowtrace: sariq qog‘oz + grafit chiziq)
 */
(function (global) {
    function createSolid(type, THREE, options = {}) {
        const color = options.color || 0xffffff;
        const material = new THREE.MeshStandardMaterial({
            color,
            metalness: 0.08,
            roughness: 0.55,
            transparent: true,
            opacity: options.opacity ?? 0.28,
            side: THREE.DoubleSide,
        });

        let geometry;
        switch (type) {
            case 'cube':
                geometry = new THREE.BoxGeometry(1.6, 1.6, 1.6);
                break;
            case 'prism':
                geometry = new THREE.CylinderGeometry(1, 1, 1.8, 3, 1);
                break;
            case 'pyramid':
                geometry = new THREE.ConeGeometry(1.1, 1.8, 4);
                break;
            case 'cylinder':
                geometry = new THREE.CylinderGeometry(0.9, 0.9, 1.8, 48);
                break;
            case 'cone':
                geometry = new THREE.ConeGeometry(1, 1.8, 48);
                break;
            case 'sphere':
                geometry = new THREE.SphereGeometry(1.1, 48, 32);
                break;
            default:
                geometry = new THREE.BoxGeometry(1.4, 1.4, 1.4);
        }

        const mesh = new THREE.Mesh(geometry, material);

        const edges = new THREE.EdgesGeometry(geometry);
        const line = new THREE.LineSegments(
            edges,
            new THREE.LineBasicMaterial({
                color: options.edge || 0xffd700,
                transparent: true,
                opacity: 0.95,
            })
        );

        const group = new THREE.Group();
        group.add(mesh);
        group.add(line);
        group.userData = { mesh, line, type };
        return group;
    }

    function createSceneBasics(THREE, canvas, opts = {}) {
        const scene = new THREE.Scene();
        scene.background = new THREE.Color(opts.bg || 0x1e4d8c);

        const camera = new THREE.PerspectiveCamera(
            45,
            canvas.clientWidth / Math.max(canvas.clientHeight, 1),
            0.1,
            100
        );
        camera.position.set(3.2, 2.4, 4.2);

        const renderer = new THREE.WebGLRenderer({ canvas, antialias: true, alpha: true });
        renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, 2));
        renderer.setSize(canvas.clientWidth, canvas.clientHeight, false);

        const hemi = new THREE.HemisphereLight(0xffffff, 0x1e4d8c, 0.95);
        scene.add(hemi);
        const dir = new THREE.DirectionalLight(0xffffff, 0.85);
        dir.position.set(4, 6, 3);
        scene.add(dir);

        const grid = new THREE.GridHelper(10, 20, 0xffd700, 0x5a8fc4);
        if (Array.isArray(grid.material)) {
            grid.material.forEach((m) => { m.transparent = true; m.opacity = 0.28; });
        } else {
            grid.material.transparent = true;
            grid.material.opacity = 0.28;
        }
        grid.position.y = -1.2;
        scene.add(grid);

        const axes = new THREE.AxesHelper(2.2);
        axes.position.y = -1.2;
        scene.add(axes);

        return { scene, camera, renderer, grid, axes };
    }

    function enableOrbit(canvas, camera, target = { x: 0, y: 0, z: 0 }) {
        let dragging = false;
        let prev = { x: 0, y: 0 };
        let spherical = {
            radius: camera.position.length(),
            theta: Math.atan2(camera.position.x, camera.position.z),
            phi: Math.acos(Math.min(Math.max(camera.position.y / camera.position.length(), -1), 1)),
        };

        function updateCamera() {
            const sinPhi = Math.sin(spherical.phi);
            camera.position.x = target.x + spherical.radius * sinPhi * Math.sin(spherical.theta);
            camera.position.y = target.y + spherical.radius * Math.cos(spherical.phi);
            camera.position.z = target.z + spherical.radius * sinPhi * Math.cos(spherical.theta);
            camera.lookAt(target.x, target.y, target.z);
        }

        canvas.addEventListener('pointerdown', (e) => {
            dragging = true;
            prev = { x: e.clientX, y: e.clientY };
            canvas.setPointerCapture(e.pointerId);
        });
        canvas.addEventListener('pointerup', () => { dragging = false; });
        canvas.addEventListener('pointermove', (e) => {
            if (!dragging) return;
            const dx = e.clientX - prev.x;
            const dy = e.clientY - prev.y;
            prev = { x: e.clientX, y: e.clientY };
            spherical.theta -= dx * 0.01;
            spherical.phi = Math.min(Math.max(spherical.phi - dy * 0.01, 0.2), Math.PI - 0.2);
            updateCamera();
        });
        canvas.addEventListener('wheel', (e) => {
            e.preventDefault();
            spherical.radius = Math.min(Math.max(spherical.radius + e.deltaY * 0.01, 2.2), 12);
            updateCamera();
        }, { passive: false });

        return {
            setView(name) {
                if (name === 'front') {
                    spherical = { radius: 5, theta: 0, phi: Math.PI / 2 };
                } else if (name === 'top') {
                    spherical = { radius: 5, theta: 0, phi: 0.15 };
                } else if (name === 'side') {
                    spherical = { radius: 5, theta: Math.PI / 2, phi: Math.PI / 2 };
                } else {
                    spherical = { radius: 5.2, theta: 0.7, phi: 1.1 };
                }
                updateCamera();
            },
            reset() {
                spherical = { radius: 5.2, theta: 0.7, phi: 1.1 };
                updateCamera();
            },
        };
    }

    function drawProjectionSVG(svg, type, view, theme = {}) {
        while (svg.firstChild) svg.removeChild(svg.firstChild);
        const ns = 'http://www.w3.org/2000/svg';
        const stroke = theme.stroke || '#ffffff';
        const fill = theme.fill || 'rgba(255, 215, 0, 0.16)';
        const paper = theme.paper || '#153a6b';
        const grid = theme.grid || 'rgba(255,255,255,0.1)';

        function el(name, attrs) {
            const node = document.createElementNS(ns, name);
            Object.entries(attrs).forEach(([k, v]) => node.setAttribute(k, v));
            svg.appendChild(node);
            return node;
        }

        el('rect', { x: 0, y: 0, width: '100%', height: '100%', fill: paper });

        for (let i = 12; i < 120; i += 12) {
            el('line', { x1: i, y1: 0, x2: i, y2: 90, stroke: grid, 'stroke-width': 1 });
        }
        for (let j = 9; j < 90; j += 9) {
            el('line', { x1: 0, y1: j, x2: 120, y2: j, stroke: grid, 'stroke-width': 1 });
        }

        const shapes = {
            cube: {
                front: () => el('rect', { x: 35, y: 20, width: 50, height: 50, fill, stroke, 'stroke-width': 1.5 }),
                top: () => el('rect', { x: 35, y: 20, width: 50, height: 50, fill, stroke, 'stroke-width': 1.5 }),
                side: () => el('rect', { x: 35, y: 20, width: 50, height: 50, fill, stroke, 'stroke-width': 1.5 }),
            },
            prism: {
                front: () => el('polygon', { points: '30,70 60,20 90,70', fill, stroke, 'stroke-width': 1.5 }),
                top: () => el('polygon', { points: '30,55 60,25 90,55', fill, stroke, 'stroke-width': 1.5 }),
                side: () => el('rect', { x: 40, y: 20, width: 40, height: 50, fill, stroke, 'stroke-width': 1.5 }),
            },
            pyramid: {
                front: () => el('polygon', { points: '30,70 60,18 90,70', fill, stroke, 'stroke-width': 1.5 }),
                top: () => {
                    el('polygon', { points: '30,60 60,25 90,60 60,70', fill, stroke, 'stroke-width': 1.5 });
                    el('circle', { cx: 60, cy: 52, r: 2, fill: stroke });
                },
                side: () => el('polygon', { points: '35,70 60,18 85,70', fill, stroke, 'stroke-width': 1.5 }),
            },
            cylinder: {
                front: () => el('rect', { x: 40, y: 18, width: 40, height: 55, fill, stroke, 'stroke-width': 1.5 }),
                top: () => el('ellipse', { cx: 60, cy: 45, rx: 28, ry: 28, fill, stroke, 'stroke-width': 1.5 }),
                side: () => el('rect', { x: 40, y: 18, width: 40, height: 55, fill, stroke, 'stroke-width': 1.5 }),
            },
            cone: {
                front: () => el('polygon', { points: '30,70 60,18 90,70', fill, stroke, 'stroke-width': 1.5 }),
                top: () => {
                    el('ellipse', { cx: 60, cy: 48, rx: 30, ry: 30, fill, stroke, 'stroke-width': 1.5 });
                    el('circle', { cx: 60, cy: 48, r: 2, fill: stroke });
                },
                side: () => el('polygon', { points: '35,70 60,18 85,70', fill, stroke, 'stroke-width': 1.5 }),
            },
            sphere: {
                front: () => el('circle', { cx: 60, cy: 45, r: 28, fill, stroke, 'stroke-width': 1.5 }),
                top: () => el('circle', { cx: 60, cy: 45, r: 28, fill, stroke, 'stroke-width': 1.5 }),
                side: () => el('circle', { cx: 60, cy: 45, r: 28, fill, stroke, 'stroke-width': 1.5 }),
            },
        };

        const pack = shapes[type] || shapes.cube;
        (pack[view] || pack.front)();
    }

    global.FazoGeometry = {
        createSolid,
        createSceneBasics,
        enableOrbit,
        drawProjectionSVG,
    };
})(window);
