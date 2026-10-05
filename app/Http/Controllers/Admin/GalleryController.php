<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class GalleryController extends Controller
{
    use HandlesUploads;

    public function index()
    {
        return view('admin.galleries', [
            'galleries' => Gallery::orderBy('sort_order')->latest()->paginate(24),
        ]);
    }

    /** Unggah satu atau beberapa foto sekaligus. */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['nullable', 'string', 'max:120'],
            'images' => ['required', 'array', 'max:20'],
            'images.*' => ['image', 'max:4096'],
        ]);

        foreach ($request->file('images') as $file) {
            Gallery::create([
                'type' => 'image',
                'title' => $request->title,
                'image' => $file->store('galleries', 'public'),
            ]);
        }

        return back()->with('success', 'Foto ditambahkan.');
    }

    /** Tambahkan satu video YouTube. */
    public function storeVideo(Request $request)
    {
        $request->validate([
            'title' => ['nullable', 'string', 'max:120'],
            'video_url' => ['required', 'string', 'max:255'],
        ]);

        if (! Gallery::youtubeId($request->video_url)) {
            throw ValidationException::withMessages([
                'video_url' => 'Tautan YouTube tidak dikenali. Contoh: https://www.youtube.com/watch?v=XXXXXXXXXXX',
            ]);
        }

        Gallery::create([
            'type' => 'video',
            'title' => $request->title,
            'video_url' => $request->video_url,
        ]);

        return back()->with('success', 'Video ditambahkan.');
    }

    /** Ubah urutan tampil. Angka kecil tampil lebih dulu. */
    public function sort(Request $request, Gallery $gallery)
    {
        $data = $request->validate(['sort_order' => ['required', 'integer', 'min:0', 'max:9999']]);
        $gallery->update($data);

        return back()->with('success', 'Urutan diperbarui.');
    }

    public function destroy(Gallery $gallery)
    {
        $this->deleteFile($gallery->image);
        $gallery->delete();

        return back()->with('success', 'Item galeri dihapus.');
    }
}
