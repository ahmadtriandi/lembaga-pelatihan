@extends('layouts.site')

@section('title', $post->title . ' — ' . ($site['site_name'] ?? ''))
@section('description', $post->excerpt ?? '')

@section('content')
<section>
  <div class="wrap">
    <article class="article">
      <a href="{{ route('posts') }}">Semua artikel</a>
      <h1>{{ $post->title }}</h1>
      <time style="color:var(--muted)" datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->translatedFormat('d F Y') }}</time>
      @if($post->image)
        <div class="cover"><img src="{{ asset('storage/' . $post->image) }}" alt=""></div>
      @endif
      {{-- Isi artikel ditulis admin (HTML). Hanya admin tepercaya yang boleh punya akses dashboard. --}}
      <div class="content">{!! $post->body !!}</div>
    </article>
  </div>
</section>

@if($others->isNotEmpty())
<section style="padding-top:0">
  <div class="wrap">
    <h2>Artikel lainnya</h2>
    @include('site.partials.post-grid', ['items' => $others])
  </div>
</section>
@endif
@endsection
