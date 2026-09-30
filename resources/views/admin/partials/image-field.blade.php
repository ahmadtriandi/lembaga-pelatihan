{{-- Parameter: $name, $label, $current (path atau null) --}}
<label class="f">{{ $label }}
  <input type="file" name="{{ $name }}" accept="image/*" data-preview="prev-{{ $name }}">
  <small>JPG/PNG/WebP. Kosongkan jika tidak ingin mengganti.</small>
  @error($name)<span class="err">{{ $message }}</span>@enderror
  <img id="prev-{{ $name }}" class="preview" src="{{ $current ? asset('storage/' . $current) : '' }}" alt="" @if(!$current) hidden @endif>
</label>
