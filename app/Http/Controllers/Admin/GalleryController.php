<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    use HandlesUploads;

    public function index()
    {
        return view('admin.galleries', ['galleries' => Gallery::latest()->paginate(24)]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['nullable', 'string', 'max:120'],
            'images' => ['required', 'array', 'max:20'],
            'images.*' => ['image', 'max:4096'],
        ]);

        foreach ($request->file('images') as $file) {
            Gallery::create(['title' => $request->title, 'image' => $file->store('galleries', 'public')]);
        }

        return back()->with('success', 'Foto ditambahkan.');
    }

    public function destroy(Gallery $gallery)
    {
        $this->deleteFile($gallery->image);
        $gallery->delete();

        return back()->with('success', 'Foto dihapus.');
    }
}
