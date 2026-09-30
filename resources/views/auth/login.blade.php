<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex">
<title>Masuk — {{ $site['site_name'] ?? 'Admin' }}</title>
<link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,800&family=Public+Sans:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
<div class="login">
  <div class="card">
    <h1>{{ $site['site_name'] ?? 'Admin' }}</h1>
    <p style="color:var(--muted)">Masuk ke dashboard untuk mengelola website.</p>
    <form method="POST" action="{{ url('/admin/login') }}">
      @csrf
      <label class="f">Email
        <input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
        @error('email')<span class="err">{{ $message }}</span>@enderror
      </label>
      <label class="f">Kata sandi
        <input type="password" name="password" required autocomplete="current-password">
      </label>
      <label class="check"><input type="checkbox" name="remember" value="1"> Ingat saya</label>
      <button class="btn" type="submit">Masuk</button>
    </form>
  </div>
</div>
</body>
</html>
