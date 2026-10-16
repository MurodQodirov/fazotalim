document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.getElementById('navToggle');
    const nav = document.getElementById('mainNav');

    if (toggle && nav) {
        toggle.addEventListener('click', () => {
            const open = nav.classList.toggle('open');
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        });
    }

    const drop = document.getElementById('navDrop');
    const dropBtn = document.getElementById('navDropBtn');
    if (drop && dropBtn) {
        dropBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            const open = drop.classList.toggle('open');
            dropBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
        });
        document.addEventListener('click', () => {
            drop.classList.remove('open');
            dropBtn.setAttribute('aria-expanded', 'false');
        });
        drop.addEventListener('click', (e) => e.stopPropagation());
    }

    // Flash auto-hide
    const flash = document.querySelector('.flash');
    if (flash) {
        setTimeout(() => {
            flash.style.transition = 'opacity .4s';
            flash.style.opacity = '0';
            setTimeout(() => flash.remove(), 400);
        }, 4000);
    }
});
