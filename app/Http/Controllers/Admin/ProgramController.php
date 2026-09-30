<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Program;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    use HandlesUploads;

    public function index(Request $request)
    {
        $programs = Program::with('category')
            ->when($request->q, fn ($q, $s) => $q->where('name', 'like', "%{$s}%"))
            ->when($request->category, fn ($q, $c) => $q->where('category_id', $c))
            ->orderBy('sort_order')->paginate(15)->withQueryString();

        return view('admin.programs.index', ['programs' => $programs, 'categories' => Category::orderBy('sort_order')->get()]);
    }

    public function create()
    {
        return view('admin.programs.form', [
            'program' => new Program([
                'is_active' => true,
                'requirements' => "Scan ijazah terakhir\nScan KTP\nPas foto terbaru\nCV terbaru\nSurat keterangan kerja\nJob desc\nLaporan pekerjaan\nPortofolio (opsional)",
                'facilities' => "Sertifikat pelatihan\nSertifikat kompetensi\nTraining kit\nMakan siang & coffee break (offline)\nModul materi",
            ]),
            'categories' => Category::orderBy('sort_order')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['image'] = $this->upload($request, 'image', 'programs');
        Program::create($data);

        return redirect()->route('admin.programs.index')->with('success', 'Program ditambahkan.');
    }

    public function edit(Program $program)
    {
        return view('admin.programs.form', ['program' => $program, 'categories' => Category::orderBy('sort_order')->get()]);
    }

    public function update(Request $request, Program $program)
    {
        $data = $this->validated($request);
        $data['image'] = $this->upload($request, 'image', 'programs', $program->image);
        $program->update($data);

        return redirect()->route('admin.programs.index')->with('success', 'Program diperbarui.');
    }

    public function destroy(Program $program)
    {
        $this->deleteFile($program->image);
        $program->delete();

        return back()->with('success', 'Program dihapus.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'category_id' => ['nullable', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:200'],
            'level' => ['nullable', 'string', 'max:50'],
            'duration' => ['nullable', 'string', 'max:100'],
            'price' => ['nullable', 'integer', 'min:0'],
            'units' => ['nullable', 'string'],
            'qualifications' => ['nullable', 'string'],
            'requirements' => ['nullable', 'string'],
            'facilities' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'max:3072'],
        ]);
        unset($data['image']);
        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }
}
