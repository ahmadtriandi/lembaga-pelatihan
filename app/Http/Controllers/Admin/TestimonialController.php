<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    use HandlesUploads;

    public function index()
    {
        return view('admin.testimonials.index', ['testimonials' => Testimonial::latest()->paginate(15)]);
    }

    public function create()
    {
        return view('admin.testimonials.form', ['testimonial' => new Testimonial(['rating' => 5, 'is_active' => true])]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['photo'] = $this->upload($request, 'photo', 'testimonials');
        Testimonial::create($data);

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimoni ditambahkan.');
    }

    public function edit(Testimonial $testimonial)
    {
        return view('admin.testimonials.form', compact('testimonial'));
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $data = $this->validated($request);
        $data['photo'] = $this->upload($request, 'photo', 'testimonials', $testimonial->photo);
        $testimonial->update($data);

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimoni diperbarui.');
    }

    public function destroy(Testimonial $testimonial)
    {
        $this->deleteFile($testimonial->photo);
        $testimonial->delete();

        return back()->with('success', 'Testimoni dihapus.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'company' => ['nullable', 'string', 'max:150'],
            'program' => ['nullable', 'string', 'max:150'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'content' => ['required', 'string', 'max:1000'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ]);
        unset($data['photo']);
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
