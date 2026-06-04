<?php
namespace App\Services;

use App\Models\Trash;

interface TrashServices
{
    public function categoryBuilder();
    public function createJenis(array $data): Trash;
    public function getTrashBuilder();
}
