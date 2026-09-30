<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        return view('admin.categories.index', ['categories' => Category::withCount('programs')->orderBy('sort_order')->get()]);
    }

    public function create()
    {
        return view('admin.categories.form', ['category' => new Category(['color' => '#3F7D58'])]);
    }

    public function store(Request $request)
    {
        Category::create($this->validated($request));

        return redirect()->route('admin.categories.index')->with('success', 'Bidang ditambahkan.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.form', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $category->update($this->validated($request, $category));

        return redirect()->route('admin.categories.index')->with('success', 'Bidang diperbarui.');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return back()->with('success', 'Bidang dihapus.');
    }

    private function validated(Request $request, ?Category $category = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:60'],
            'color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);
        $data['slug'] = Str::slug($data['name']);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        $request->validate(['name' => ['unique:categories,name' . ($category ? ',' . $category->id : '')]]);

        return $data;
    }
}
