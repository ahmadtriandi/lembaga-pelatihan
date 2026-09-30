@extends('layouts.admin')

@section('title', 'Bidang pelatihan')
@section('actions')<a class="btn" href="{{ route('admin.categories.create') }}">Tambah bidang</a>@endsection

@section('content')
<div class="card">
  <p style="color:var(--muted);margin-bottom:10px">Bidang dipakai sebagai tombol filter di daftar program pada beranda.</p>
  @if($categories->isEmpty())
    <p class="empty">Belum ada bidang.</p>
  @else
  <div class="table-wrap">
    <table>
      <thead><tr><th>Nama</th><th>Urutan</th><th>Jumlah program</th><th></th></tr></thead>
      <tbody>
      @foreach($categories as $c)
        <tr>
          <td><span class="dot" style="background:{{ $c->color }}"></span>{{ $c->name }}</td>
          <td>{{ $c->sort_order }}</td>
          <td>{{ $c->programs_count }}</td>
          <td class="actions">
            <a class="btn light sm" href="{{ route('admin.categories.edit', $c) }}">Ubah</a>
            @include('admin.partials.delete', ['action' => route('admin.categories.destroy', $c), 'confirm' => "Hapus bidang {$c->name}? Program di dalamnya tidak ikut terhapus."])
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>
  @endif
</div>
@endsection
