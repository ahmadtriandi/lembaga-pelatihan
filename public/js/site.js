// Filter program
document.querySelectorAll('.filters button').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('.filters button').forEach(b => b.setAttribute('aria-pressed', b === btn));
    const cat = btn.dataset.cat;
    document.querySelectorAll('.prog').forEach(card => {
      card.hidden = !(cat === 'all' || card.dataset.cat === cat);
    });
  });
});

// Tombol "Isi formulir" di kartu program -> pilih program di form pendaftaran
document.querySelectorAll('[data-pick-program]').forEach(a => {
  a.addEventListener('click', () => {
    const sel = document.getElementById('program_id');
    if (sel) sel.value = a.dataset.pickProgram;
  });
});

// Menu mobile
const burger = document.querySelector('.burger');
const menu = document.getElementById('menu');
if (burger && menu) {
  burger.addEventListener('click', () => {
    const open = menu.classList.toggle('open');
    burger.setAttribute('aria-expanded', open);
  });
  menu.querySelectorAll('a').forEach(a => a.addEventListener('click', () => {
    menu.classList.remove('open');
    burger.setAttribute('aria-expanded', false);
  }));
}

// Animasi angka
const io = new IntersectionObserver(entries => entries.forEach(e => {
  if (!e.isIntersecting) return;
  const el = e.target;
  const end = parseInt(el.dataset.count, 10);
  const suffix = el.dataset.suffix || '';
  let start = null;
  const step = ts => {
    start ??= ts;
    const p = Math.min((ts - start) / 1200, 1);
    el.textContent = Math.round(end * p) + (p === 1 ? suffix : '');
    if (p < 1) requestAnimationFrame(step);
  };
  requestAnimationFrame(step);
  io.unobserve(el);
}), { threshold: .5 });
document.querySelectorAll('[data-count]').forEach(el => io.observe(el));

// Jika formulir gagal divalidasi, gulir kembali ke formulir
if (document.querySelector('#daftar .err')) {
  document.getElementById('daftar').scrollIntoView();
}
