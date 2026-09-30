<div class="posts">
  @foreach($items as $post)
    <a class="post" href="{{ route('post', $post) }}">
      <div class="thumb">
        @if($post->image)
          <img src="{{ asset('storage/' . $post->image) }}" alt="" loading="lazy">
        @else
          <svg viewBox="0 0 160 100" aria-hidden="true"><rect width="160" height="100" fill="#0F3D3E"/><path d="M0 70q40-20 80 0t80 0v30H0z" fill="#3F7D58"/><circle cx="120" cy="30" r="12" fill="#E8B930"/></svg>
        @endif
      </div>
      <div class="txt">
        <time datetime="{{ $post->published_at?->toDateString() }}">{{ $post->published_at?->translatedFormat('d F Y') }}</time>
        <h3>{{ $post->title }}</h3>
      </div>
    </a>
  @endforeach
</div>
