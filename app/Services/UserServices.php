<?php
namespace App\Services;

use App\Models\Category;

interface UserServices
{
    public function doLogin(array $data);
    public function register(array $data);
    public function getUserById(int $id);
    public function doLogout();
    public function userBuilder();
    public function createCategory(array $data);
    public function categoriesBuilder();
    public function updateCategory(array $data, int $id);
    public function categoryById(int $id);
    public function delete(int $id);
    public function updateUser(int $id, array $data);
    public function deleteUser(int $id);
    public function getBukuTabunganByUserId(int $id);
    public function getUserByUnitAndBook();
}
