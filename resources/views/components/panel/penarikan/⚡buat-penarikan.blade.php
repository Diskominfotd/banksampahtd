<?php

use Livewire\Component;
use App\Services\UserServices;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    protected UserServices $userService;
    public array $nasabah = [];
    public array $selectedNasabah = [];
    public function boot(UserServices $userService)
    {
        $this->userService = $userService;
    }

    public function getNasabah()
    {
        $data = $this->userService->getUserByUnitAndBook()
        ->latest()->paginate(10);
        $this->nasabah = $data->items();
    }
    public function pilihNasabah($id)
    {
        $item = collect($this->nasabah)->firstWhere('id', $id);
           dd(json_encode($item));
        $this->selectedNasabah[] = [
            'name' => $item->name,
        ];
     
        $this->dispatch('close-modal');
    }
};
?>

<div>
    {{-- Do what you can, with what you have, where you are. - Theodore Roosevelt --}}
    <div class="desktop-wrapper">
        @include('components.⚡dekstop-navbar')
        <div class="w-main">
            @include('components.⚡dekstop-header')
            <div id="w-harga" class="w-content">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <div>
                        <div style="font-family:'Syne',sans-serif;font-size:14px;font-weight:700">Buat Transaksi
                            Penarikan Saldo</div>
                        <div style="font-size:11px;color:var(--muted)">Berlaku per 20 Mei 2026 · Diperbarui oleh Admin
                        </div>
                    </div>
                </div>
                <div class="m-2">
                    <div class="w-panel">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <div style="font-size:12px;font-weight:700">Item Setoran</div>
                                <div style="font-size:10px;color:var(--muted)">Tambahkan jenis sampah yang disetor
                                </div>
                            </div>
                            <button wire:click="getNasabah" data-bs-toggle="modal" data-bs-target="#wm-pilih-nasabah"
                                class="w-btn w-btn-primary" class="w-btn w-btn-primary"
                                style="font-size:11px;width:auto;padding:6px 12px">
                                <i class="bi bi-plus-lg me-1"></i> Pilih Nasabah
                            </button>
                        </div>
                        <table class="w-tbl">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama</th>
                                    <th>Rekening</th>
                                    <th>Saldo</th>
                                    <th>Jumlah Penarikan</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($selectedNasabah as $i => $c)
                                    <tr wire:key="cart-{{ $i }}">
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $c['name'] }}</td>
                                        <td>
                                            {{-- <button wire:click="removeCart({{ $i }})"
                                                class="btn btn-sm btn-link text-danger p-0">
                                                <i class="bi bi-trash3"></i>
                                            </button> --}}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6"
                                            style="text-align:center;padding:28px 10px;color:var(--muted);font-size:11px">
                                            <i class="bi bi-inbox"
                                                style="font-size:20px;display:block;margin-bottom:6px"></i>
                                            Belum ada item ditambahkan
                                        </td>
                                    </tr>
                                @endforelse
                                {{-- @if (count($selectedNasabah) > 0)
                                    <tr style="font-weight:700;font-size:12px">
                                        <td colspan="4" style="text-align:right">Total</td>
                                    </tr>
                                @endif --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="wm-pilih-nasabah" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content w-modal">
                <div class="w-modal-header">
                    <div class="w-modal-title">Pilih Nasabah</div>
                    <div class="w-modal-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></div>
                </div>
                <div class="px-3 py-2 border-bottom">
                    <input type="text" wire:model.live="searchNasabah" class="form-control form-control-sm"
                        placeholder="Cari nama atau unit..." />
                </div>
                <div wire:loading.flex wire:target="getNasabah" class="justify-content-center align-items-center"
                    style="position:absolute;inset:0;background:rgba(255,255,255,0.6);z-index:10;border-radius:inherit">
                    <div class="spinner-border text-success"></div>
                </div>
                <div class="w-modal-body" style="overflow-y: auto; max-height: 60vh;">
                    <div class="d-flex flex-column gap-2">
                        @foreach ($this->nasabah as $n)
                            <div class="w-row" wire:click="pilihNasabah({{ $n->id }})" style="cursor:pointer"
                                wire:key="nasabah-{{ $n->id }}">
                                <div class="avatar" style="width:28px;height:28px;font-size:10px">
                                    {{ strtoupper($n->initials()) }}
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <div class="w-row-title">{{ ucfirst($n->name) }}</div>
                                    <div class="w-row-meta">
                                        @foreach ($n->bukutabungans as $bk)
                                            {{ $bk->nomor_rekening }}
                                        @endforeach
                                    </div>
                                </div>
                                <span class="{{ $n->status == 'active' ? 'bs bs-green' : 'bs bs-warn' }}">
                                    {{ ucfirst($n->status) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
