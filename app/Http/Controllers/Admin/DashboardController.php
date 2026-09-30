<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Program;
use App\Models\Registration;
use App\Models\Testimonial;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view('admin.dashboard', [
            'stats' => [
                'Pendaftar baru' => Registration::where('status', 'baru')->count(),
                'Total pendaftar' => Registration::count(),
                'Program aktif' => Program::where('is_active', true)->count(),
                'Artikel terbit' => Post::published()->count(),
                'Testimoni' => Testimonial::count(),
            ],
            'latest' => Registration::with('program')->latest()->take(8)->get(),
            'perProgram' => Program::withCount('registrations')->orderByDesc('registrations_count')->take(5)->get(),
        ]);
    }
}
