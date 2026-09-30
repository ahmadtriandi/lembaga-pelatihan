<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    use HandlesUploads;

    public function index()
    {
        return view('admin.clients', ['clients' => Client::latest()->get()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'logo' => ['nullable', 'image', 'max:2048'],
        ]);
        $data['logo'] = $this->upload($request, 'logo', 'clients');
        Client::create($data);

        return back()->with('success', 'Klien ditambahkan.');
    }

    public function destroy(Client $client)
    {
        $this->deleteFile($client->logo);
        $client->delete();

        return back()->with('success', 'Klien dihapus.');
    }
}
