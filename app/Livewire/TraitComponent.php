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
