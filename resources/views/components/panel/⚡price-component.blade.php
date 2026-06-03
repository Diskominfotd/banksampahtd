<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<div>
    {{-- The only way to do great work is to love what you do. - Steve Jobs --}}
    <div class="desktop-wrapper">
        @include('panel.template.dekstop-navbar')
        <div class="w-main">
            <header class="w-topbar">
                <div id="w-topbar-info">
                    <div class="w-title">Dashboard Pengelola</div>
                    <div class="w-sub">Jumat, 29 Mei 2026 · Bank Sampah Nusantara, Pekanbaru</div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="w-search"><i class="bi bi-search si"></i><input type="text"
                            placeholder="Cari nasabah, setoran..."></div>
                    <div class="d-flex align-items-center gap-2">
                        <div class="text-end">
                            <div class="w-uname">Budi Santoso</div>
                            <div class="w-urole">Pengelola Bank Sampah</div>
                        </div>
                        <div class="avatar avatar-sm">BS</div>
                    </div>
                </div>
            </header>
            <div id="w-harga" class="w-content">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <div>
                        <div style="font-family:'Syne',sans-serif;font-size:14px;font-weight:700">Daftar Harga Beli
                            Sampah</div>
                        <div style="font-size:11px;color:var(--muted)">Berlaku per 20 Mei 2026 · Diperbarui oleh Admin
                        </div>
                    </div>
                    <button class="w-btn w-btn-primary" style="font-size:11px"
                        onclick="openWModal('wm-update-harga')"><i class="bi bi-pencil-fill me-1"></i>Update
                        Harga</button>
                </div>
                <div class="w-panel">
                    <table class="w-tbl">
                        <thead>
                            <tr>
                                <th>Kategori</th>
                                <th>Jenis Sampah</th>
                                <th>Syarat</th>
                                <th>Harga/kg</th>
                                <th>Perubahan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span class="bs bs-ok">Plastik</span></td>
                                <td style="font-size:11px;font-weight:600">Plastik HDPE (botol)</td>
                                <td style="font-size:10px;color:var(--muted)">Bersih & kering</td>
                                <td style="font-family:'Syne',sans-serif;font-weight:700;color:var(--cyan)">Rp 5.000
                                </td>
                                <td style="font-size:10px;color:var(--muted)">Stabil</td>
                            </tr>
                            <tr>
                                <td><span class="bs bs-ok">Plastik</span></td>
                                <td style="font-size:11px;font-weight:600">Plastik PET (botol minum)</td>
                                <td style="font-size:10px;color:var(--muted)">Tanpa tutup & label</td>
                                <td style="font-family:'Syne',sans-serif;font-weight:700;color:var(--cyan)">Rp 6.000
                                </td>
                                <td style="font-size:10px;color:var(--cyan)">↑ dari Rp5.500</td>
                            </tr>
                            <tr>
                                <td><span class="bs bs-ok">Plastik</span></td>
                                <td style="font-size:11px;font-weight:600">Kantong plastik (kresek)</td>
                                <td style="font-size:10px;color:var(--muted)">Sudah dipilah</td>
                                <td style="font-family:'Syne',sans-serif;font-weight:700;color:var(--cyan)">Rp 1.500
                                </td>
                                <td style="font-size:10px;color:var(--muted)">Stabil</td>
                            </tr>
                            <tr>
                                <td><span class="bs bs-new">Kertas</span></td>
                                <td style="font-size:11px;font-weight:600">Kardus (dos)</td>
                                <td style="font-size:10px;color:var(--muted)">Kering, tidak basah</td>
                                <td style="font-family:'Syne',sans-serif;font-weight:700;color:var(--cyan)">Rp 2.000
                                </td>
                                <td style="font-size:10px;color:var(--muted)">Stabil</td>
                            </tr>
                            <tr>
                                <td><span class="bs bs-new">Kertas</span></td>
                                <td style="font-size:11px;font-weight:600">Kertas HVS / Koran</td>
                                <td style="font-size:10px;color:var(--muted)">Tanpa lakban</td>
                                <td style="font-family:'Syne',sans-serif;font-weight:700;color:var(--cyan)">Rp 3.000
                                </td>
                                <td style="font-size:10px;color:var(--muted)">Stabil</td>
                            </tr>
                            <tr>
                                <td><span class="bs bs-purple">Logam</span></td>
                                <td style="font-size:11px;font-weight:600">Besi / Baja</td>
                                <td style="font-size:10px;color:var(--muted)">Bebas karat berat</td>
                                <td style="font-family:'Syne',sans-serif;font-weight:700;color:var(--cyan)">Rp 3.500
                                </td>
                                <td style="font-size:10px;color:var(--muted)">Stabil</td>
                            </tr>
                            <tr>
                                <td><span class="bs bs-purple">Logam</span></td>
                                <td style="font-size:11px;font-weight:600">Aluminium</td>
                                <td style="font-size:10px;color:var(--muted)">Kaleng & lembaran</td>
                                <td style="font-family:'Syne',sans-serif;font-weight:700;color:var(--cyan)">Rp 14.000
                                </td>
                                <td style="font-size:10px;color:var(--red)">↓ dari Rp15.000</td>
                            </tr>
                            <tr>
                                <td><span class="bs bs-orange">Elektronik</span></td>
                                <td style="font-size:11px;font-weight:600">E-waste (HP, kabel, PCB)</td>
                                <td style="font-size:10px;color:var(--muted)">Dikirim ke mitra</td>
                                <td style="font-family:'Syne',sans-serif;font-weight:700;color:var(--cyan)">Rp 20.000
                                </td>
                                <td style="font-size:10px;color:var(--cyan)">↑ dari Rp18.000</td>
                            </tr>
                            <tr>
                                <td><span class="bs bs-err">Kaca</span></td>
                                <td style="font-size:11px;font-weight:600">Kaca / Botol kaca</td>
                                <td style="font-size:10px;color:var(--muted)">Tidak pecah</td>
                                <td style="font-family:'Syne',sans-serif;font-weight:700;color:var(--cyan)">Rp 3.000
                                </td>
                                <td style="font-size:10px;color:var(--muted)">Stabil</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
