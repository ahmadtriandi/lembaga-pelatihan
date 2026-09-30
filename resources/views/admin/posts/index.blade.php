@extends('layouts.admin')

@section('title', 'Artikel')
@section('actions')<a class="btn" href="{{ route('admin.posts.create') }}">Tulis artikel</a>@endsection

@section('content')
<div class="card">
  @if($posts->isEmpty())
    <p class="empty">Belum ada artikel. Artikel membantu website Anda ditemukan di Google.</p>
  @else
  <div class="table-wrap">
    <table>
      <thead><tr><th></th><th>Judul</th><th>Status</th><th></th></tr></thead>
      <tbody>
      @foreach($posts as $p)
        @php $live = $p->published_at && $p->published_at->isPast(); @endphp
        <tr>
          <td>@if($p->image)<img class="thumb" src="{{ asset('storage/' . $p->image) }}" alt="">@else<div class="thumb"></div>@endif</td>
          <td><b>{{ $p->title }}</b><span class="sub">/artikel/{{ $p->slug }}</span></td>
          <td>
            @if($live)<span class="badge">Terbit {{ $p->published_at->format('d/m/Y') }}</span>
            @elseif($p->published_at)<span class="badge dihubungi">Terjadwal {{ $p->published_at->format('d/m/Y H:i') }}</span>
            @else<span class="badge off">Draf</span>@endif
          </td>
          <td class="actions">
            @if($live)<a class="btn light sm" href="{{ route('post', $p) }}" target="_blank">Lihat</a>@endif
            <a class="btn light sm" href="{{ route('admin.posts.edit', $p) }}">Ubah</a>
            @include('admin.partials.delete', ['action' => route('admin.posts.destroy', $p), 'confirm' => 'Hapus artikel ini?'])
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>
  {{ $posts->links() }}
  @endif
</div>
@endsection
