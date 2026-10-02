<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FacilityController extends Controller
{
    use HandlesUploads;

    public function index()
    {
        return view('admin.facilities', [
            'facilities' => Facility::orderBy('sort_order')->latest()->get()->groupBy('type'),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type' => ['required', Rule::in(array_keys(Facility::TYPES))],
            'title' => ['required', 'string', 'max:80'],
            'description' => ['nullable', 'string', 'max:200'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'image' => ['required', 'image', 'max:4096'],
        ]);

        $data['sort_order'] ??= 0;
        $data['image'] = $this->upload($request, 'image', 'facilities');
        Facility::create($data);

        return back()->with('success', Facility::TYPES[$data['type']] . ' ditambahkan.');
    }

    public function destroy(Facility $facility)
    {
        $this->deleteFile($facility->image);
        $facility->delete();

        return back()->with('success', Facility::TYPES[$facility->type] . ' dihapus.');
    }
}
