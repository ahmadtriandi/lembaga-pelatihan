@extends('layouts.site')

@section('title', 'Artikel — ' . ($site['site_name'] ?? ''))

@section('content')
<section>
  <div class="wrap">
    <h1 style="font-size:clamp(2rem,4.5vw,3rem);margin-bottom:.5rem">Artikel</h1>
    <p class="lead">Informasi seputar regulasi, sertifikasi, dan pengembangan kompetensi.</p>
    @if($posts->isEmpty())
      <p style="margin-top:2rem">Belum ada artikel yang diterbitkan.</p>
    @else
      @include('site.partials.post-grid', ['items' => $posts])
      {{ $posts->links('site.partials.pagination') }}
    @endif
  </div>
</section>
@endsection
