<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Client;
use App\Models\Facility;
use App\Models\Gallery;
use App\Models\Post;
use App\Models\Program;
use App\Models\Registration;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function home()
    {
        return view('site.home', [
            'categories' => Category::orderBy('sort_order')->get(),
            'programs' => Program::with('category')->where('is_active', true)->orderBy('sort_order')->get(),
            'facilities' => Facility::orderBy('sort_order')->latest()->get()->groupBy('type'),
            'galleries' => Gallery::orderBy('sort_order')->latest()->take(24)->get(),
            'clients' => Client::latest()->get(),
            'testimonials' => Testimonial::where('is_active', true)->latest()->take(6)->get(),
            'posts' => Post::published()->latest('published_at')->take(3)->get(),
        ]);
    }

    public function posts()
    {
        return view('site.posts', [
            'posts' => Post::published()->latest('published_at')->paginate(9),
        ]);
    }

    public function post(Post $post)
    {
        abort_unless($post->published_at && $post->published_at->isPast(), 404);

        return view('site.post', [
            'post' => $post,
            'others' => Post::published()->whereKeyNot($post->id)->latest('published_at')->take(3)->get(),
        ]);
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'program_id' => ['nullable', 'exists:programs,id'],
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:100'],
            'company' => ['nullable', 'string', 'max:150'],
            'class_type' => ['required', 'in:offline,online'],
            'message' => ['nullable', 'string', 'max:1000'],
        ]);

        Registration::create($data);

        return redirect()->to(url('/') . '#daftar')
            ->with('registered', 'Terima kasih, pendaftaran Anda sudah kami terima. Admin akan menghubungi Anda melalui WhatsApp.');
    }
}
