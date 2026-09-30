@extends('layouts.admin')

@section('title', 'Pendaftar')
@section('actions')
  <a class="btn light" href="{{ route('admin.registrations.export', request()->only('status')) }}">Unduh Excel (CSV)</a>
@endsection

@section('content')
<div class="card">
  <form class="filters" method="GET">
    <input name="q" value="{{ request('q') }}" placeholder="Cari nama, perusahaan, nomor">
    <select name="status" onchange="this.form.submit()">
      <option value="">Semua status</option>
      @foreach(\App\Models\Registration::STATUSES as $k => $v)
        <option value="{{ $k }}" @selected(request('status') === $k)>{{ $v }}</option>
      @endforeach
    </select>
    <select name="program" onchange="this.form.submit()">
      <option value="">Semua program</option>
      @foreach($programs as $p)
        <option value="{{ $p->id }}" @selected(request('program') == $p->id)>{{ $p->name }}</option>
      @endforeach
    </select>
    <button class="btn light" type="submit">Cari</button>
  </form>

  @if($registrations->isEmpty())
    <p class="empty">Tidak ada pendaftar yang cocok.</p>
  @else
  <div class="table-wrap">
    <table>
      <thead><tr><th>Nama</th><th>WhatsApp</th><th>Program</th><th>Kelas</th><th>Status</th><th>Masuk</th><th></th></tr></thead>
      <tbody>
      @foreach($registrations as $r)
        <tr>
          <td><b>{{ $r->name }}</b><span class="sub">{{ $r->company }}</span></td>
          <td>{{ $r->phone }}</td>
          <td>{{ $r->program?->name ?? 'Konsultasi' }}</td>
          <td>{{ ucfirst($r->class_type) }}</td>
          <td><span class="badge {{ $r->status }}">{{ \App\Models\Registration::STATUSES[$r->status] ?? $r->status }}</span></td>
          <td>{{ $r->created_at->format('d/m/Y H:i') }}</td>
          <td class="actions"><a class="btn light sm" href="{{ route('admin.registrations.show', $r) }}">Buka</a></td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>
  {{ $registrations->links() }}
  @endif
</div>
@endsection
