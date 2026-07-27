<?php

namespace App\Http\Controllers;

use Livewire\Features\SupportFileUploads\FileUploadConfiguration;
use Livewire\Features\SupportFileUploads\FileUploadController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CustomLivewireController extends FileUploadController
{
    public function handle(): array
    {
        $disk = config('livewire.temporary_file_upload.disk', 'local');
        $filePaths = $this->validateAndStore(request('files'), $disk);
        return ['paths' => $filePaths];
    }
}
