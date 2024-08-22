<?php

use Livewire\Volt\Component;
use App\Models\Usuario;
use Livewire\Attributes\On;

new class extends Component {
    public function cerrarSesion(): void
    {
        // Se cierra la sesión
        auth()->logout();

        // Se redirecciona al login
        redirect(route('login'));
    }

    #[On('update-card-perfil')]
    public function with(): array
    {
        $usuario = Usuario::query()
            ->where('id_usu', auth()->id())
            ->with('rol')
            ->with('trabajador')
            ->first();

        return [
            'usuario' => $usuario,
        ];
    }
}; ?>

<div class="card pc-user-card">
    <div class="card-body">
        <div class="d-flex align-items-center">
            <div class="flex-shrink-0">
                <img src="{{ $usuario->foto_usu }}" alt="user-image"
                    class="user-avtar wid-40 rounded-circle" />
            </div>
            <div class="flex-grow-1 ms-3 me-2">
                <h6 class="mb-0">
                    {{ $usuario->trabajador->nombre_apellido }}
                </h6>
                <small>
                    {{ $usuario->rol->nombre_rol }}
                </small>
            </div>
            <a class="btn btn-icon btn-link-secondary avtar" data-bs-toggle="collapse"
                href="#pc_sidebar_userlink">
                <svg class="pc-icon">
                    <use xlink:href="#custom-sort-outline"></use>
                </svg>
            </a>
        </div>
        <div class="collapse pc-user-links" id="pc_sidebar_userlink">
            <div class="pt-3">
                <a href="">
                    <i class="ti ti-user"></i>
                    <span>Mi perfil</span>
                </a>
                <a
                    style="cursor: pointer;"
                    wire:click="cerrarSesion"
                >
                    <i class="ti ti-power"></i>
                    <span>Cerrar sesión</span>
                </a>
            </div>
        </div>
    </div>
</div>
