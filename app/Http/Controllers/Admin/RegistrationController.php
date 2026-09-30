<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RegistrationController extends Controller
{
    public function index(Request $request)
    {
        $registrations = Registration::with('program')
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->when($request->program, fn ($q, $p) => $q->where('program_id', $p))
            ->when($request->q, fn ($q, $s) => $q->where(fn ($w) => $w
                ->where('name', 'like', "%{$s}%")
                ->orWhere('company', 'like', "%{$s}%")
                ->orWhere('phone', 'like', "%{$s}%")))
            ->latest()->paginate(20)->withQueryString();

        return view('admin.registrations.index', [
            'registrations' => $registrations,
            'programs' => Program::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function show(Registration $registration)
    {
        return view('admin.registrations.show', ['r' => $registration->load('program')]);
    }

    public function update(Request $request, Registration $registration)
    {
        $data = $request->validate(['status' => ['required', Rule::in(array_keys(Registration::STATUSES))]]);
        $registration->update($data);

        return back()->with('success', 'Status diperbarui.');
    }

    public function destroy(Registration $registration)
    {
        $registration->delete();

        return redirect()->route('admin.registrations.index')->with('success', 'Data pendaftar dihapus.');
    }

    /** Unduh semua pendaftar (sesuai filter) sebagai CSV untuk dibuka di Excel. */
    public function export(Request $request)
    {
        $rows = Registration::with('program')
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->latest()->get();

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['Tanggal', 'Nama', 'WhatsApp', 'Email', 'Perusahaan', 'Program', 'Kelas', 'Status', 'Pesan'], ';');
            foreach ($rows as $r) {
                fputcsv($out, [
                    $r->created_at->format('d/m/Y H:i'), $r->name, $r->phone, $r->email, $r->company,
                    $r->program?->name, $r->class_type, Registration::STATUSES[$r->status] ?? $r->status, $r->message,
                ], ';');
            }
            fclose($out);
        }, 'pendaftar-' . now()->format('Ymd') . '.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
