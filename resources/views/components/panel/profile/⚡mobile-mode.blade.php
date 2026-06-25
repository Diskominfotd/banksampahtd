<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<div>
    {{-- I have not failed. I've just found 10,000 ways that won't work. - Thomas Edison --}}
  <div id="m-nasabah">
        <div class="m-page-header">
            <div class="ph-title">Profil & Setelan</div>
        </div>
        <div class="m-body" style="padding-top:16px">
            <div class="d-flex align-items-center gap-3 p-3 mb-4"
                style="background:#fff;border:1px solid var(--border);border-radius:16px">
                <div class="avatar" style="width:52px;height:52px;font-size:18px">
                    {{ strtoupper(Auth::user()->initials()) }}</div>
                <div class="flex-grow-1">
                    <div style="font-family:'Syne',sans-serif;font-size:15px;font-weight:700">
                        {{ ucfirst(Auth::user()->name) }}</div>
                    <div style="font-size:11px;color:var(--muted)">{{ ucfirst(Auth::user()->roles->first()->name) }}
                    </div>
                    <div style="font-size:10px;color:var(--cyan);margin-top:2px">
                        {{ ucfirst(Auth::user()->unit->name) }}
                    </div>
                </div>
                <button class="btn-tx" onclick="openDetail('m-edit-profil')">Edit</button>
                <button class="btn-tx" onclick="openDetail('m-edit-profil')">Ubah Password</button>
            </div>
            @if (Auth::user()->hasRole(['supervisor']))
                <div class="d-flex align-items-center justify-content-between">
                    <div class="sec-lbl">Informasi Unit</div>
                    <button class="btn-tx" onclick="openDetail('m-edit-profil')">Edit</button>
                </div>
                <div class="d-flex flex-column gap-2 mb-3">
                    <div class="detail-field"><span class="df-key">Nama Bank Sampah</span><span
                            class="df-val">{{ $namaBank }}</span></div>
                    <div class="detail-field"><span class="df-key">Kode Unit</span><span
                            class="df-val">{{ $kodeBank }}</span>
                    </div>
                    <div class="detail-field"><span class="df-key">Lokasi</span>
                        <span class="df-val">
                            Tanah Datar, Sumatera Barat
                        </span>
                    </div>
                    <div class="detail-field"><span class="df-key">Jam Operasional</span><span class="df-val">Sen–Jum,
                            {{ $jamBuka }}–{{ $jamTutup }}</span></div>
                    <div class="detail-field"><span class="df-key">Total Nasabah</span><span
                            class="df-val">{{ $this->nasabah }} nasabah
                            aktif</span></div>
                </div>
            @endif
        </div>
        @include('components.⚡mobile-bottombar')
    </div>
</div>
