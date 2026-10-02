@php
    $nav = [
        'Utama' => [
            ['admin.dashboard', 'Dashboard', 'admin.dashboard'],
            ['admin.registrations.index', 'Pendaftar', 'admin.registrations.*', $newRegistrations ?? 0],
        ],
        'Konten' => [
            ['admin.programs.index', 'Program', 'admin.programs.*'],
            ['admin.categories.index', 'Bidang', 'admin.categories.*'],
            ['admin.posts.index', 'Artikel', 'admin.posts.*'],
            ['admin.galleries.index', 'Galeri', 'admin.galleries.*'],
            ['admin.facilities.index', 'Fasilitas', 'admin.facilities.*'],
            ['admin.clients.index', 'Klien', 'admin.clients.*'],
            ['admin.testimonials.index', 'Testimoni', 'admin.testimonials.*'],
        ],
        'Situs' => [
            ['admin.settings', 'Pengaturan', 'admin.settings*'],
        ],
    ];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex">
<title>@yield('title', 'Dashboard') — {{ $site['site_name'] ?? 'Admin' }}</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,700;12..96,800&family=Public+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
<div class="mobile-bar">
  <button type="button" onclick="document.querySelector('.side').classList.toggle('open')" aria-label="Menu">
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
  </button>
  <strong>{{ $site['site_name'] ?? 'Admin' }}</strong>
</div>
<div class="shell">
  <aside class="side">
    <a class="logo" href="{{ route('admin.dashboard') }}">{{ $site['site_name'] ?? 'Admin' }}<small>Dashboard admin</small></a>
    <nav>
      @foreach($nav as $group => $items)
        <div class="group">{{ $group }}</div>
        @foreach($items as $item)
          <a href="{{ route($item[0]) }}" class="{{ request()->routeIs($item[2]) ? 'active' : '' }}">
            <span>{{ $item[1] }}</span>
            @if(!empty($item[3]))<span class="count">{{ $item[3] }}</span>@endif
          </a>
        @endforeach
      @endforeach
    </nav>
    <div class="bottom">
      <nav><a href="{{ route('home') }}" target="_blank">Lihat website</a></nav>
      <div style="padding:.4rem .8rem;opacity:.7;font-size:.85rem">{{ auth()->user()->name }}</div>
      <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit">Keluar</button></form>
    </div>
  </aside>

  <main class="main">
    <div class="topline">
      <h1>@yield('title', 'Dashboard')</h1>
      <div>@yield('actions')</div>
    </div>

    @if(session('success'))<div class="flash" role="status">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="flash error" role="alert">Periksa kembali isian yang ditandai.</div>@endif

    @yield('content')
  </main>
</div>
<script>
// Konfirmasi sebelum menghapus
document.querySelectorAll('form[data-confirm]').forEach(f => f.addEventListener('submit', e => {
  if (!confirm(f.dataset.confirm)) e.preventDefault();
}));
// Pratinjau gambar sebelum upload
document.querySelectorAll('input[type=file][data-preview]').forEach(inp => inp.addEventListener('change', () => {
  const img = document.getElementById(inp.dataset.preview);
  if (img && inp.files[0]) { img.src = URL.createObjectURL(inp.files[0]); img.hidden = false; }
}));
</script>
</body>
</html>
