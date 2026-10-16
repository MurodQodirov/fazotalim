(async function () {
    const canvas = document.getElementById('labCanvas');
    if (!canvas || !window.THREE || !window.FazoGeometry) return;

    const THREE = window.THREE;
    const { createSolid, createSceneBasics, enableOrbit, drawProjectionSVG } = window.FazoGeometry;

    const dataEl = document.getElementById('solidsData');
    const solids = dataEl ? JSON.parse(dataEl.textContent) : [];
    let currentId = window.FAZO_SELECTED_SOLID || solids[0]?.id || 'cube';
    let currentSolid = null;
    let showEdges = true;
    let showFaces = true;

    const { scene, camera, renderer } = createSceneBasics(THREE, canvas);
    const orbit = enableOrbit(canvas, camera);
    orbit.reset();

    const infoName = document.getElementById('infoName');
    const infoDesc = document.getElementById('infoDesc');
    const infoFaces = document.getElementById('infoFaces');
    const infoEdges = document.getElementById('infoEdges');
    const infoVertices = document.getElementById('infoVertices');
    const infoVolume = document.getElementById('infoVolume');
    const infoArea = document.getElementById('infoArea');
    const infoTheory = document.getElementById('infoTheory');

    const svgFront = document.getElementById('projFront');
    const svgTop = document.getElementById('projTop');
    const svgSide = document.getElementById('projSide');

    function findSolid(id) {
        return solids.find((s) => s.id === id) || solids[0];
    }

    function loadSolid(id) {
        currentId = id;
        const meta = findSolid(id);
        if (currentSolid) scene.remove(currentSolid);

        // Yellowtrace: grafit kontur / yengil to‘ldirish
        currentSolid = createSolid(id, THREE, { color: 0xffffff, edge: 0xffd700 });
        scene.add(currentSolid);

        document.querySelectorAll('.solid-list button').forEach((btn) => {
            btn.classList.toggle('active', btn.dataset.id === id);
        });

        if (infoName) infoName.textContent = meta?.name || id;
        if (infoDesc) infoDesc.textContent = meta?.description || '';
        if (infoFaces) infoFaces.textContent = meta?.faces ?? '—';
        if (infoEdges) infoEdges.textContent = meta?.edges ?? '—';
        if (infoVertices) infoVertices.textContent = meta?.vertices ?? '—';
        if (infoVolume) infoVolume.textContent = meta?.formula_volume || '—';
        if (infoArea) infoArea.textContent = meta?.formula_area || '—';
        if (infoTheory) infoTheory.textContent = meta?.theory || '';

        updateProjections();
        applyVisibility();
    }

    function updateProjections() {
        if (svgFront) drawProjectionSVG(svgFront, currentId, 'front');
        if (svgTop) drawProjectionSVG(svgTop, currentId, 'top');
        if (svgSide) drawProjectionSVG(svgSide, currentId, 'side');
    }

    function applyVisibility() {
        if (!currentSolid) return;
        const { mesh, line } = currentSolid.userData;
        if (mesh) mesh.visible = showFaces;
        if (line) line.visible = showEdges;
    }

    document.querySelectorAll('.solid-list button').forEach((btn) => {
        btn.addEventListener('click', () => loadSolid(btn.dataset.id));
    });

    document.querySelectorAll('[data-view]').forEach((btn) => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('[data-view]').forEach((b) => b.classList.remove('active'));
            btn.classList.add('active');
            orbit.setView(btn.dataset.view);
        });
    });

    const btnEdges = document.getElementById('toggleEdges');
    const btnFaces = document.getElementById('toggleFaces');
    const btnReset = document.getElementById('resetView');

    if (btnEdges) {
        btnEdges.addEventListener('click', () => {
            showEdges = !showEdges;
            btnEdges.classList.toggle('active', showEdges);
            applyVisibility();
        });
    }
    if (btnFaces) {
        btnFaces.addEventListener('click', () => {
            showFaces = !showFaces;
            btnFaces.classList.toggle('active', showFaces);
            applyVisibility();
        });
    }
    if (btnReset) {
        btnReset.addEventListener('click', () => orbit.reset());
    }

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

    loadSolid(currentId);
})();
