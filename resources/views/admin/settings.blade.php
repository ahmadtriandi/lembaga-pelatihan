@extends('layouts.admin')

@section('title', 'Pengaturan situs')

@section('content')
<form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
  @csrf @method('PUT')

  <div class="card">
    <h2>Gambar</h2>
    <div class="form-grid">
      @include('admin.partials.image-field', ['name' => 'logo', 'label' => 'Logo', 'current' => $values['logo'] ?? null])
      @include('admin.partials.image-field', ['name' => 'hero_image', 'label' => 'Gambar hero (opsional, menggantikan stempel)', 'current' => $values['hero_image'] ?? null])
      @include('admin.partials.image-field', ['name' => 'about_image', 'label' => 'Foto bagian Tentang kami', 'current' => $values['about_image'] ?? null])
      @include('admin.partials.image-field', ['name' => 'schedule_image', 'label' => 'Gambar jadwal pelatihan (maks. 8 MB)', 'current' => $values['schedule_image'] ?? null])
    </div>
  </div>

  @foreach(collect($fields)->groupBy(fn ($f) => $f[2], true) as $group => $items)
    <div class="card">
      <h2>{{ $group }}</h2>
      <div class="form-grid">
        @foreach($items as $key => $field)
          @php $label = $field[0]; $type = $field[1]; @endphp
          <label class="f {{ $type === 'textarea' ? 'full' : '' }}">{{ $label }}
            @if($type === 'textarea')
              <textarea name="{{ $key }}">{{ old($key, $values[$key] ?? '') }}</textarea>
            @else
              <input name="{{ $key }}" value="{{ old($key, $values[$key] ?? '') }}">
            @endif
            @error($key)<span class="err">{{ $message }}</span>@enderror
          </label>
        @endforeach
      </div>
    </div>
  @endforeach

  <div class="form-foot"><button class="btn" type="submit">Simpan pengaturan</button></div>
</form>
@endsection
