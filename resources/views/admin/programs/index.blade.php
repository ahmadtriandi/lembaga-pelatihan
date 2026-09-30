@extends('layouts.admin')

@section('title', 'Program pelatihan')
@section('actions')<a class="btn" href="{{ route('admin.programs.create') }}">Tambah program</a>@endsection

@section('content')
<div class="card">
  <form class="filters" method="GET">
    <input name="q" value="{{ request('q') }}" placeholder="Cari nama program">
    <select name="category" onchange="this.form.submit()">
      <option value="">Semua bidang</option>
      @foreach($categories as $c)
        <option value="{{ $c->id }}" @selected(request('category') == $c->id)>{{ $c->name }}</option>
      @endforeach
    </select>
    <button class="btn light" type="submit">Cari</button>
  </form>

  @if($programs->isEmpty())
    <p class="empty">Tidak ada program yang cocok.</p>
  @else
  <div class="table-wrap">
    <table>
      <thead><tr><th></th><th>Program</th><th>Bidang</th><th>Durasi</th><th>Biaya</th><th>Status</th><th></th></tr></thead>
      <tbody>
      @foreach($programs as $p)
        <tr>
          <td>@if($p->image)<img class="thumb" src="{{ asset('storage/' . $p->image) }}" alt="">@else<div class="thumb"></div>@endif</td>
          <td><b>{{ $p->name }}</b><span class="sub">{{ $p->level ? 'Level ' . $p->level : '' }}</span></td>
          <td>@if($p->category)<span class="dot" style="background:{{ $p->category->color }}"></span>{{ $p->category->name }}@else — @endif</td>
          <td>{{ $p->duration }}</td>
          <td>{{ $p->price_label }}</td>
          <td><span class="badge {{ $p->is_active ? '' : 'off' }}">{{ $p->is_active ? 'Tampil' : 'Disembunyikan' }}</span></td>
          <td class="actions">
            <a class="btn light sm" href="{{ route('admin.programs.edit', $p) }}">Ubah</a>
            @include('admin.partials.delete', ['action' => route('admin.programs.destroy', $p), 'confirm' => "Hapus program {$p->name}?"])
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>
  {{ $programs->links() }}
  @endif
</div>
@endsection
