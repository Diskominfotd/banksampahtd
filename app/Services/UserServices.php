<?php
namespace App\Services;

interface UserServices
{
    public function doLogin(array $data);
    public function register(array $data);
}
