<?php
namespace App\Services\Impl;

use App\Models\BankSampah;
use App\Models\Category;
use App\Models\Price;
use App\Models\Trash;
use App\Services\TrashServices;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TrashServicesImpl implements TrashServices
{
    public function categoryBuilder()
    {
        return Category::query();
    }
    public function createCategory(array $data)
    {
        return Category::create($data);
    }

    public function getTrashById(int $id)
    {
        return Trash::with('category')->findOrFail($id);
    }

    public function deleteTrash(int $id)
    {
        $priceTrash = Price::with('trash')->findOrFail($id);

        if ($priceTrash->trash) {
            $priceTrash->trash->delete();
        }
        $priceTrash->delete();
        return session()->flash('success', 'Berhasil Dihapus');
    }

    public function getTrashBuilder()
    {
        $bank = Auth::user()->unit;
        $priceSourceId = $bank->parent_id ?? $bank->id;
        return Trash::with(['category', 'prices' => fn($q) => $q->where('bank_id', $priceSourceId)]);
    }
    public function createJenis(array $data): Trash
    {
        return DB::transaction(function () use ($data) {
            $bankId = Auth::user()->unit->id;
            $trash = Trash::create([
                'nama' => $data['nama'],
                'syarat' => $data['syarat'] ?? null,
                'category_id' => $data['category_id'],
            ]);
            $trash->prices()->create([
                'bank_id' => $bankId,
                'type' => 'induk',
                'harga' => $data['harga'],
            ]);
            return $trash;
        });
    }
    public function updateJenis(array $data, int $id)
    {
        return DB::transaction(function () use ($data, $id) {
            $trash = Trash::findOrFail($id);
            if ($trash) {
                $trash->update([
                    'nama' => $data['nama'],
                    'syarat' => $data['syarat'],
                    'category_id' => $data['category_id'],
                ]);
            }
        });
    }

    public function priceAndTrashList()
    {
        $bank = Auth::user()->unit;
        $induk = BankSampah::whereNull('parent_id')->first();

        if ($bank->use_parent_price) {
            return Price::with(['bank', 'trash'])->where('bank_id', $induk->id);
        }
        $unitPrices = Price::where('bank_id', $bank->id)->pluck('id', 'trash_id');
        $indukPriceIds = Price::where('bank_id', $induk->id)->pluck('id', 'trash_id');

        $mergedIds = $indukPriceIds
            ->mapWithKeys(function ($indukId, $trashId) use ($unitPrices) {
                return [$trashId => $unitPrices->get($trashId, $indukId)];
            })
            ->values();

        return Price::with(['bank', 'trash'])->whereIn('id', $mergedIds);
    }

    public function priceList()
    {
        $bank = Auth::user()->unit;
        $induk = BankSampah::whereNull('parent_id')->first();
        if ($bank->use_parent_price) {
            return Price::with(['bank', 'trash'])
                ->where('bank_id', $induk->id)
                ->get();
        }
        // Ambil price unit, kalau tidak ada fallback ke harga induk
        return Price::with(['bank', 'trash'])
            ->where('bank_id', $induk->id) // base dari induk
            ->get()
            ->map(function ($price) use ($bank) {
                // Cek apakah unit punya harga sendiri untuk trash ini
                $unitPrice = Price::where('trash_id', $price->trash_id)->where('bank_id', $bank->id)->first();

                // Kalau ada, pakai harga unit — kalau tidak, pakai harga induk
                if ($unitPrice) {
                    return $unitPrice->load(['bank', 'trash']);
                }

                return $price;
            });
    }
    public function updatePrice(int $priceId, array $data)
    {
        $price = Price::findOrFail($priceId);
        $bank = Auth::user()->unit;

        // Kalau induk, langsung update dan stop
        if ($bank->jenis == 'induk') {
            $price->update([
                'harga' => $data['value'],
            ]);
            return $price;
        }

        // Logika unit di bawah ini
        if ($data['is_induk']) {
            Price::where('trash_id', $price->trash_id)->where('bank_id', $bank->id)->delete();

            $bank->update([
                'use_parent_price' => true,
            ]);
        } else {
            Price::updateOrCreate(
                [
                    'trash_id' => $price->trash_id,
                    'type' => 'unit',
                    'bank_id' => $bank->id,
                ],
                ['harga' => $data['value']],
            );

            $bank->update(['use_parent_price' => false]);
        }

        return $price;
    }
}
