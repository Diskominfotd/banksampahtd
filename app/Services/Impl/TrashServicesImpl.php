<?php
namespace App\Services\Impl;

use App\Models\Category;
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
    public function getTrashBuilder()
    {
        $bank = Auth::user()->unit;

        $priceSourceId = $bank->use_parent_price ? $bank->parent_id : $bank->id;

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
                'harga' => $data['harga'],
            ]);
            return $trash;
        });
    }
}
