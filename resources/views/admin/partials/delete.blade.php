<form method="POST" action="{{ $action }}" data-confirm="{{ $confirm ?? 'Hapus data ini?' }}">
  @csrf @method('DELETE')
  <button class="btn danger" type="submit">{{ $label ?? 'Hapus' }}</button>
</form>
