@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="stats">
  @foreach($stats as $label => $value)
    <div class="stat {{ $loop->first ? 'hl' : '' }}"><b>{{ $value }}</b><span>{{ $label }}</span></div>
  @endforeach
</div>

<div class="grid2">
  <div class="card">
    <div class="topline" style="margin-bottom:8px">
      <h2 style="margin:0">Pendaftar terbaru</h2>
      <a class="btn light sm" href="{{ route('admin.registrations.index') }}">Semua pendaftar</a>
    </div>
    @if($latest->isEmpty())
      <p class="empty">Belum ada pendaftar. Formulir pendaftaran ada di bagian bawah beranda website.</p>
    @else
      <div class="table-wrap">
        <table>
          <thead><tr><th>Nama</th><th>Program</th><th>Status</th><th>Masuk</th></tr></thead>
          <tbody>
          @foreach($latest as $r)
            <tr>
              <td><a href="{{ route('admin.registrations.show', $r) }}"><b>{{ $r->name }}</b></a><span class="sub">{{ $r->company }}</span></td>
              <td>{{ $r->program?->name ?? 'Konsultasi' }}</td>
              <td><span class="badge {{ $r->status }}">{{ \App\Models\Registration::STATUSES[$r->status] ?? $r->status }}</span></td>
              <td>{{ $r->created_at->diffForHumans() }}</td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>

  <div>
    <div class="card">
      <h2>Program paling diminati</h2>
      @forelse($perProgram->where('registrations_count', '>', 0) as $p)
        <div style="display:flex;justify-content:space-between;gap:10px;padding:.45rem 0;border-bottom:1px solid var(--line)">
          <span>{{ $p->name }}</span><b>{{ $p->registrations_count }}</b>
        </div>
      @empty
        <p style="color:var(--muted)">Data muncul setelah ada pendaftar.</p>
      @endforelse
    </div>
    <div class="card">
      <h2>Aksi cepat</h2>
      <div style="display:grid;gap:8px">
        <a class="btn light" href="{{ route('admin.programs.create') }}">Tambah program</a>
        <a class="btn light" href="{{ route('admin.posts.create') }}">Tulis artikel</a>
        <a class="btn light" href="{{ route('admin.galleries.index') }}">Unggah foto galeri</a>
        <a class="btn light" href="{{ route('admin.settings') }}">Ubah kontak & teks beranda</a>
      </div>
    </div>
  </div>
</div>
@endsection
