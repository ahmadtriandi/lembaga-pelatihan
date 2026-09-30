<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\Program;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@namalembaga.co.id'],
            ['name' => 'Administrator', 'password' => Hash::make('password123')]
        );

        Setting::setMany([
            'site_name' => 'Mandala Daya Selaras',
            'site_tagline' => 'Pelatihan & Sertifikasi',
            'meta_description' => 'Pelatihan dan sertifikasi kompetensi bidang lingkungan hidup dan energi.',
            'hero_title' => 'Kompeten di lapangan, diakui secara nasional.',
            'hero_text' => 'Program pelatihan dan uji kompetensi untuk pengelola air limbah, emisi udara, limbah B3, sampah, LCA, dan energi — dibimbing praktisi, siap untuk kebutuhan PROPER perusahaan Anda.',
            'about_text' => "Lestari Kompetensi adalah lembaga pelatihan berbasis kompetensi untuk tenaga kerja di sektor lingkungan hidup, industri, dan energi.\n\nKami membantu perusahaan menyiapkan personel yang dipersyaratkan regulasi. Setiap program diakhiri uji kompetensi, sehingga peserta pulang dengan bukti keahlian yang bisa dipakai untuk dokumen perusahaan.",
            'vision' => 'Menjadi mitra pengembangan SDM lingkungan dan energi yang paling dipercaya industri di Indonesia.',
            'mission' => "Menyelenggarakan pelatihan berbasis standar kompetensi nasional.\nMenghadirkan instruktur yang berpengalaman di lapangan.\nMemberi layanan pendaftaran dan administrasi yang cepat.\nMemperluas jangkauan pelatihan ke seluruh wilayah Indonesia.",
            'stat_alumni' => '500',
            'stat_experts' => '25+',
            'stat_programs' => '12+',
            'stat_rating' => '4,9/5',
            'whatsapp_1' => '085212522925',
            'whatsapp_2' => '082124426855',
            'email' => 'marketing@training-mds.com',
            'address' => "Nama gedung, lantai & unit\nJalan, kecamatan, kota",
            'maps_link' => '',
            'maps_embed' => '',
            'instagram' => '#', 'facebook' => '#', 'linkedin' => '#', 'tiktok' => '', 'youtube' => '',
        ]);

        $cats = [];
        foreach ([['Air', '#2F80A8'], ['Udara', '#7A8FA6'], ['Limbah B3', '#C0503A'], ['Sampah', '#8A6B3F'], ['LCA', '#3F7D58'], ['Energi', '#D39A1B']] as $i => [$name, $color]) {
            $cats[$name] = Category::updateOrCreate(['slug' => Str::slug($name)], ['name' => $name, 'color' => $color, 'sort_order' => $i])->id;
        }

        $req = "Scan ijazah terakhir\nScan KTP\nPas foto terbaru\nCV terbaru\nSurat keterangan kerja\nJob desc\nLaporan pekerjaan (SOP, logbook, dll.)\nPortofolio (opsional)";
        $fac = "Sertifikat pelatihan\nSertifikat kompetensi\nTraining kit\nMakan siang & coffee break (offline)\nModul materi";

        $programs = [
            ['Air', 'Manajer', '3 hari', 'Penanggung Jawab Pengendalian Pencemaran Air', "Identifikasi sumber pencemaran air limbah\nPenilaian tingkat pencemaran\nPengoperasian IPAL\nPemantauan kualitas air limbah\nK3 dalam pengolahan air limbah", "S1/S2 dengan pengalaman kerja di bidang terkait\nD3 dengan pengalaman kerja lebih panjang\nMemiliki sertifikat pelatihan terkait"],
            ['Air', 'Operator', '3 hari', 'Penanggung Jawab Operasional Pengolahan Air Limbah', "Hukum lingkungan\nPengoperasian dan perawatan IPAL\nIdentifikasi bahaya dan K3", "D3/SMA/SMK dengan pengalaman kerja di bidang terkait"],
            ['Udara', 'Manajer', '3 hari', 'Penanggung Jawab Pengendalian Pencemaran Udara', "Identifikasi sumber emisi\nPengendalian pencemaran udara\nPemantauan emisi\nK3 pengendalian emisi", "S1/S2 dengan pengalaman kerja di bidang terkait"],
            ['Limbah B3', 'Manajer', '3 hari', 'Pengelolaan Limbah B3', "Identifikasi sumber limbah B3\nAnalisis dan evaluasi limbah B3\nSistem tanggap darurat\nK3 pengelolaan limbah B3", "S1/S2 dengan pengalaman kerja di bidang terkait"],
            ['Sampah', 'Manajer', '3 hari', 'Pengawasan Pengolahan Sampah Non-B3', "Identifikasi timbulan sampah\nPerencanaan dan pengolahan\nMinimasi sampah\nK3", "D3/SMA/SMK dengan pengalaman di bidang persampahan"],
            ['LCA', 'Manajer', '5 hari', 'Penilaian Daur Hidup (LCA)', "Dasar-dasar LCA\nPenentuan lingkup\nInventori\nPenilaian dengan perangkat lunak\nInterpretasi & pelaporan", "S1/S2 sains/teknik dengan pengalaman kerja"],
            ['Energi', 'Manajer', '3 hari', 'Manajer Energi Industri & Bangunan Gedung', "Prinsip penghematan energi\nKebijakan dan perencanaan energi\nEvaluasi dan tinjauan manajemen", "S1/D3 teknik dengan pengalaman di bidang energi"],
            ['Energi', 'Auditor', '4 hari', 'Auditor Energi', "Perencanaan audit energi\nPengumpulan data\nPengukuran parameter energi\nSurvei lapangan", "S1/D3 teknik dengan pengalaman di bidang energi"],
        ];
        foreach ($programs as $i => [$cat, $level, $dur, $name, $units, $qual]) {
            Program::updateOrCreate(['name' => $name], [
                'category_id' => $cats[$cat], 'level' => $level, 'duration' => $dur, 'price' => null,
                'units' => $units, 'qualifications' => $qual, 'requirements' => $req, 'facilities' => $fac,
                'is_active' => true, 'sort_order' => $i,
            ]);
        }

        Testimonial::firstOrCreate(['name' => 'Nama Alumni'], [
            'company' => 'Nama Perusahaan', 'program' => 'Pengendalian Pencemaran Air', 'rating' => 5,
            'content' => 'Contoh testimoni. Ganti dengan ulasan asli peserta melalui dashboard.',
        ]);

        Post::firstOrCreate(['slug' => 'contoh-artikel-pertama'], [
            'title' => 'Siapa saja yang wajib punya sertifikat pengendalian pencemaran air?',
            'excerpt' => 'Contoh artikel. Tulis dan terbitkan artikel dari dashboard.',
            'body' => "<p>Ini contoh isi artikel. Anda bisa menulis artikel baru dari menu Artikel di dashboard.</p>",
            'published_at' => now(),
        ]);
    }
}
