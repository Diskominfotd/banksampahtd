<?php
namespace App\Services;

use App\Models\Trash;

interface TrashServices
{
    public function categoryBuilder();
    public function createJenis(array $data): Trash;
    public function getTrashBuilder();
    public function priceList();
    public function updatePrice(int $priceId, array $data);
    public function updateJenis(array $data, int $id);
    public function deleteTrash(int $id);
   
}
