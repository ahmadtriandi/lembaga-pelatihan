@extends('layouts.admin')

@section('title', 'Testimoni')
@section('actions')<a class="btn" href="{{ route('admin.testimonials.create') }}">Tambah testimoni</a>@endsection

@section('content')
<div class="card">
  @if($testimonials->isEmpty())
    <p class="empty">Belum ada testimoni.</p>
  @else
  <div class="table-wrap">
    <table>
      <thead><tr><th>Alumni</th><th>Ulasan</th><th>Rating</th><th>Status</th><th></th></tr></thead>
      <tbody>
      @foreach($testimonials as $t)
        <tr>
          <td><b>{{ $t->name }}</b><span class="sub">{{ $t->company }}</span></td>
          <td style="max-width:380px">{{ \Illuminate\Support\Str::limit($t->content, 90) }}</td>
          <td style="color:#C99400">{{ str_repeat('★', $t->rating) }}</td>
          <td><span class="badge {{ $t->is_active ? '' : 'off' }}">{{ $t->is_active ? 'Tampil' : 'Disembunyikan' }}</span></td>
          <td class="actions">
            <a class="btn light sm" href="{{ route('admin.testimonials.edit', $t) }}">Ubah</a>
            @include('admin.partials.delete', ['action' => route('admin.testimonials.destroy', $t)])
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>
  {{ $testimonials->links() }}
  @endif
</div>
@endsection
