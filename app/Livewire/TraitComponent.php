<?php

namespace App\Livewire;

trait TraitComponent
{
    public function alertNotAlowed()
    {
        if (session()->has('failed')) {
            $message = json_encode(session('failed'));
            $this->js(
                <<<JS
                     let message = $message;
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: message,
                    });
                JS
                ,
            );
        }
    }
    public function alertPopUp()
    {
        if (session()->has('success')) {
            $message = json_encode(session('success'));
            $this->js(
                <<<JS
                  let message = $message;
                    Swal.fire({
                    icon: "success",
                    title: "Berhasil",
                    text: message,
                });
                JS
                ,
            );
        }
        if (session()->has('error')) {
            $message = json_encode(session('error'));
            $this->js(
                <<<JS
                let message = $message;
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: message,
                });
                JS
                ,
            );
        }
        if (session()->has('import_errors')) {
            $rows = session('import_errors');
            $listHtml = collect($rows)->map(fn($r) => '<li style="text-align:left;">' . e($r) . '</li>')->implode('');
            $html = json_encode('<ul style="max-height:300px;overflow-y:auto;padding-left:1.2rem;">' . $listHtml . '</ul>');

            $this->js(
                <<<JS
                Swal.fire({
                    icon: "warning",
                    title: "Import Gagal",
                    html: $html,
                    width: 600,
                });
                JS
                ,
            );
        }
    }
    public function alert()
    {
        if (session()->has('success')) {
            $message = json_encode(session('success'));
            $this->js(
                <<<JS
                  let message = $message;
                    Swal.fire({
                          toast: true,
                          position: 'top-end',
                          icon: 'success',
                          title: message,
                          showConfirmButton: false,
                          timerProgressBar: true,
                          timer: 3000
                    });
                JS
                ,
            );
        }
        if (session()->has('error')) {
            $message = json_encode(session('error'));
            $this->js(
                <<<JS
                  let message = $message;
                    Swal.fire({
                          toast: true,
                          position: 'top-end',
                          icon: 'error',
                          title: message,
                          showConfirmButton: false,
                          timerProgressBar: true,
                          timer: 3000
                    });
                JS
                ,
            );
        }
    }
}
