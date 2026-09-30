@extends('layouts.admin')

@php
    $phone = preg_replace('/\D/', '', $r->phone);
    if (str_starts_with($phone, '0')) { $phone = '62' . substr($phone, 1); }
    $greeting = "Halo {$r->name}, terima kasih sudah mendaftar"
        . ($r->program ? " program {$r->program->name}" : '')
        . ' di ' . ($site['site_name'] ?? 'kami') . '. ';
@endphp

@section('title', $r->name)
@section('actions')
  <a class="btn" href="https://api.whatsapp.com/send?phone={{ $phone }}&text={{ rawurlencode($greeting) }}" target="_blank">Chat via WhatsApp</a>
@endsection

@section('content')
<div class="grid2">
  <div class="card">
    <h2>Data pendaftar</h2>
    <dl class="detail">
      <dt>Nama</dt><dd>{{ $r->name }}</dd>
      <dt>WhatsApp</dt><dd>{{ $r->phone }}</dd>
      <dt>Email</dt><dd>{{ $r->email ?: '—' }}</dd>
      <dt>Perusahaan</dt><dd>{{ $r->company ?: '—' }}</dd>
      <dt>Program</dt><dd>{{ $r->program?->name ?? 'Konsultasi (belum memilih)' }}</dd>
      <dt>Jenis kelas</dt><dd>{{ ucfirst($r->class_type) }}</dd>
      <dt>Pesan</dt><dd style="white-space:pre-line">{{ $r->message ?: '—' }}</dd>
      <dt>Masuk</dt><dd>{{ $r->created_at->translatedFormat('d F Y, H:i') }}</dd>
    </dl>
  </div>
  <div>
    <form class="card" method="POST" action="{{ route('admin.registrations.update', $r) }}">
      @csrf @method('PATCH')
      <h2>Status</h2>
      <label class="f">Ubah status
        <select name="status">
          @foreach(\App\Models\Registration::STATUSES as $k => $v)
            <option value="{{ $k }}" @selected($r->status === $k)>{{ $v }}</option>
          @endforeach
        </select>
      </label>
      <div class="form-foot"><button class="btn" type="submit">Simpan status</button></div>
    </form>
    <div class="card">
      <a class="btn light" href="{{ route('admin.registrations.index') }}">Kembali ke daftar</a>
      <div style="margin-top:10px">
        @include('admin.partials.delete', ['action' => route('admin.registrations.destroy', $r), 'label' => 'Hapus data pendaftar', 'confirm' => 'Hapus data pendaftar ini?'])
      </div>
    </div>
  </div>
</div>
@endsection
