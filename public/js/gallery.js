(function () {
  // Setiap baris galeri (foto dan video) punya panah gesernya sendiri
  document.querySelectorAll('.gal-row').forEach(row => {
    const track = row.querySelector('.gal-track');
    if (!track) return;

    const nav = row.querySelector('.gal-nav');
    const prev = row.querySelector('.gal-prev');
    const next = row.querySelector('.gal-next');

    function refresh() {
      const overflow = track.scrollWidth - track.clientWidth > 4;
      track.classList.toggle('fits', !overflow);
      if (nav) nav.hidden = !overflow;
      if (overflow && prev && next) {
        prev.disabled = track.scrollLeft < 8;
        next.disabled = track.scrollLeft + track.clientWidth >= track.scrollWidth - 8;
      }
    }

    function step() {
      const item = track.querySelector('.gal-item');
      return item ? item.getBoundingClientRect().width + 14 : track.clientWidth * 0.8;
    }

    prev?.addEventListener('click', () => track.scrollBy({ left: -step() }));
    next?.addEventListener('click', () => track.scrollBy({ left: step() }));
    track.addEventListener('scroll', refresh, { passive: true });
    new ResizeObserver(refresh).observe(track);
    refresh();
  });

  // Lightbox dipakai bersama oleh kedua baris
  const modal = document.querySelector('.gal-modal');
  if (!modal) return;
  const body = modal.querySelector('.gal-modal-body');
  let lastFocus = null;

  function open(figure) {
    lastFocus = document.activeElement;
    const src = figure.dataset.src;
    const title = figure.dataset.title || '';
    body.innerHTML = figure.dataset.type === 'video'
      ? `<iframe src="${src}" title="${title}" allow="autoplay; encrypted-media; picture-in-picture" allowfullscreen></iframe>`
      : `<img src="${src}" alt="${title}">`;
    modal.hidden = false;
    document.body.style.overflow = 'hidden';
    modal.querySelector('.gal-close').focus();
  }

  function close() {
    if (modal.hidden) return;
    body.innerHTML = '';            // hentikan video
    modal.hidden = true;
    document.body.style.overflow = '';
    lastFocus?.focus();
  }

  document.querySelectorAll('.gal-open').forEach(btn =>
    btn.addEventListener('click', () => open(btn.closest('.gal-item')))
  );
  modal.addEventListener('click', e => { if (e.target === modal || e.target.closest('.gal-close')) close(); });
  document.addEventListener('keydown', e => { if (e.key === 'Escape') close(); });
})();
