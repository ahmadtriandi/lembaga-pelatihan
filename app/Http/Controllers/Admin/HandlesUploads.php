<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

trait HandlesUploads
{
    /** Simpan file baru (dan hapus file lama) lalu kembalikan path-nya. */
    protected function upload(Request $request, string $field, string $folder, ?string $old = null): ?string
    {
        if (! $request->hasFile($field)) {
            return $old;
        }
        $this->deleteFile($old);

        return $request->file($field)->store($folder, 'public');
    }

    protected function deleteFile(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
