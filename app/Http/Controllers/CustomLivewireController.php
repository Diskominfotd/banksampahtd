<?php

namespace App\Http\Controllers;

use Livewire\Features\SupportFileUploads\FileUploadConfiguration;
use Livewire\Features\SupportFileUploads\FileUploadController;

class CustomLivewireController extends FileUploadController
{
    public function handle(): array
    {
        $disk = FileUploadConfiguration::disk();

        $filePaths = $this->validateAndStore(request('files'), $disk);

        return ['paths' => $filePaths];
    }
}
