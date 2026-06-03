<?php
namespace App\Services;

interface UserServices
{
    public function doLogin(array $data);
    public function register(array $data);
    public function getUserById(int $id);
    public function doLogout();
    public function userBuilder();
    public function createCategory(array $data);
    public function categoriesBuilder();
}
