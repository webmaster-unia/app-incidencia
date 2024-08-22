<?php

use Livewire\Volt\Component;

new class extends Component {

}; ?>

<header class="pc-header"
    style="background-image: url('{{ asset('media/img/fondo-unia.webp') }}'); background-size: cover; background-repeat: no-repeat; background-attachment: fixed; background-position: center;">
    <div class="header-wrapper"> <!-- [Mobile Media Block] start -->
        <div class="me-auto pc-mob-drp">
            <ul class="list-unstyled">
                <!-- ======= Menu collapse Icon ===== -->
                <li class="pc-h-item pc-sidebar-collapse">
                    <a style="cursor: pointer;" class="pc-head-link ms-0" id="sidebar-hide">
                        <i class="ti ti-menu-2"></i>
                    </a>
                </li>
                <li class="pc-h-item pc-sidebar-popup">
                    <a style="cursor: pointer;" class="pc-head-link ms-0" id="mobile-collapse">
                        <i class="ti ti-menu-2"></i>
                    </a>
                </li>
                <li class="pc-h-item">
                    <span class="fs-4 fw-bold">
                        Sistema de Gestión de Incidencias - UNIA
                    </span>
                </li>
            </ul>
        </div>
    </div>
</header>
