<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    use HandlesUploads;

    public function index()
    {
        return view('admin.posts.index', ['posts' => Post::latest()->paginate(15)]);
    }

    public function create()
    {
        return view('admin.posts.form', ['post' => new Post(['published_at' => now()])]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['image'] = $this->upload($request, 'image', 'posts');
        Post::create($data);

        return redirect()->route('admin.posts.index')->with('success', 'Artikel disimpan.');
    }

    public function edit(Post $post)
    {
        return view('admin.posts.form', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $data = $this->validated($request, $post);
        $data['image'] = $this->upload($request, 'image', 'posts', $post->image);
        $post->update($data);

        return redirect()->route('admin.posts.index')->with('success', 'Artikel diperbarui.');
    }

    public function destroy(Post $post)
    {
        $this->deleteFile($post->image);
        $post->delete();

        return back()->with('success', 'Artikel dihapus.');
    }

    private function validated(Request $request, ?Post $post = null): array
    {
        $request->merge(['slug' => Str::slug($request->input('slug') ?: $request->input('title'))]);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'slug' => ['required', 'string', 'max:220', Rule::unique('posts', 'slug')->ignore($post?->id)],
            'excerpt' => ['nullable', 'string', 'max:300'],
            'body' => ['nullable', 'string'],
            'published_at' => ['nullable', 'date'],
            'image' => ['nullable', 'image', 'max:3072'],
        ]);
        unset($data['image']);
        if ($request->boolean('draft')) {
            $data['published_at'] = null;
        }

        return $data;
    }
}
