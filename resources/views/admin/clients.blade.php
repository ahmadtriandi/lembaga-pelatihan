@extends('layouts.admin')

@section('title', 'Klien')

@section('content')
<form class="card" method="POST" action="{{ route('admin.clients.store') }}" enctype="multipart/form-data">
  <h2>Tambah klien</h2>
  @csrf
  <div class="form-grid">
    <label class="f">Nama perusahaan
      <input name="name" value="{{ old('name') }}" required>
      @error('name')<span class="err">{{ $message }}</span>@enderror
    </label>
    @include('admin.partials.image-field', ['name' => 'logo', 'label' => 'Logo', 'current' => null])
  </div>
  <div class="form-foot"><button class="btn" type="submit">Tambah</button></div>
</form>

<div class="card">
  <h2>Daftar klien ({{ $clients->count() }})</h2>
  @if($clients->isEmpty())
    <p class="empty">Belum ada klien.</p>
  @else
    <div class="logos">
      @foreach($clients as $c)
        <div>
          @if($c->logo)<img src="{{ asset('storage/' . $c->logo) }}" alt="">@endif
          <b>{{ $c->name }}</b>
          @include('admin.partials.delete', ['action' => route('admin.clients.destroy', $c), 'confirm' => "Hapus {$c->name}?"])
        </div>
      @endforeach
    </div>
  @endif
</div>
@endsection
