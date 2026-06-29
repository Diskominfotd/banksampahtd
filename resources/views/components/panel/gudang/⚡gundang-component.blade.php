<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<div>
    {{-- An unexamined life is not worth living. - Socrates --}}
    {{-- ======= MOBILE ======= --}}
    @include('panel.gudang.⚡mobile-mode')

    {{-- ======= DESKTOP ======= --}}
    @include('panel.gudang.⚡dekstop-mode')

</div>
