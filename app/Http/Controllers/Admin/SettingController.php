<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    use HandlesUploads;

    /** Daftar field pengaturan: key => [label, tipe, grup] */
    public const FIELDS = [
        'site_name' => ['Nama lembaga', 'text', 'Identitas'],
        'site_tagline' => ['Tagline singkat', 'text', 'Identitas'],
        'meta_description' => ['Deskripsi untuk Google (SEO)', 'textarea', 'Identitas'],
        'hero_title' => ['Judul hero', 'text', 'Beranda'],
        'hero_text' => ['Teks hero', 'textarea', 'Beranda'],
        'about_text' => ['Tentang kami', 'textarea', 'Beranda'],
        'vision' => ['Visi', 'textarea', 'Beranda'],
        'mission' => ['Misi (satu per baris)', 'textarea', 'Beranda'],
        'stat_alumni' => ['Jumlah alumni', 'text', 'Angka pencapaian'],
        'stat_experts' => ['Jumlah tenaga ahli', 'text', 'Angka pencapaian'],
        'stat_programs' => ['Jumlah skema', 'text', 'Angka pencapaian'],
        'stat_rating' => ['Penilaian peserta', 'text', 'Angka pencapaian'],
        'whatsapp_1' => ['WhatsApp utama (format 628…)', 'text', 'Kontak'],
        'whatsapp_2' => ['WhatsApp kedua (opsional)', 'text', 'Kontak'],
        'email' => ['Email', 'text', 'Kontak'],
        'address' => ['Alamat', 'textarea', 'Kontak'],
        'maps_link' => ['Link Google Maps', 'text', 'Kontak'],
        'maps_embed' => ['URL embed Google Maps (src iframe)', 'text', 'Kontak'],
        'instagram' => ['Instagram', 'text', 'Media sosial'],
        'facebook' => ['Facebook', 'text', 'Media sosial'],
        'linkedin' => ['LinkedIn', 'text', 'Media sosial'],
        'tiktok' => ['TikTok', 'text', 'Media sosial'],
        'youtube' => ['YouTube', 'text', 'Media sosial'],
    ];

    public function edit()
    {
        return view('admin.settings', ['fields' => self::FIELDS, 'values' => Setting::allCached()]);
    }

    public function update(Request $request)
    {
        $rules = array_fill_keys(array_keys(self::FIELDS), ['nullable', 'string', 'max:5000']);
        $rules['logo'] = ['nullable', 'image', 'max:2048'];
        $rules['hero_image'] = ['nullable', 'image', 'max:4096'];
        $rules['about_image'] = ['nullable', 'image', 'max:4096'];
        $data = $request->validate($rules);

        foreach (['logo', 'hero_image', 'about_image'] as $img) {
            unset($data[$img]);
            if ($request->hasFile($img)) {
                $data[$img] = $this->upload($request, $img, 'settings', Setting::get($img));
            }
        }

        Setting::setMany($data);

        return back()->with('success', 'Pengaturan disimpan.');
    }
}
