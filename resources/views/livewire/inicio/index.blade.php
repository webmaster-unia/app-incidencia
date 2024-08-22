<?php

use Livewire\Volt\Component;
use Livewire\Attributes\{Layout, Title};


new
#[Layout('components.layouts.app')]
#[Title('Home | SIGEIN OTI')]
class extends Component {
    //
}; ?>

<div>
    <div class="row g-3">
        <div class="col-12">
            <div class="card welcome-banner bg-purple-800">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-7">
                            <div class="p-4 text-sm-start text-center">
                                <h2 class="text-white fs-1">
                                    Bienvenido al Sistema de Gestión de Incidencias
                                </h2>
                                <p class="text-white">
                                    Universidad Nacional Intercultural de la Amazonía - UNIA
                                </p>
                            </div>
                        </div>
                        <div class="col-sm-5 text-center text-sm-end">
                            <div class="px-5 py-3">
                                <img src="{{ asset('media/img/logo-unia-2.png') }}" alt="img" width="130"
                                    class="img-fluid" style="min-width: 80px;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
