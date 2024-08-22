<?php

use Livewire\Volt\Component;
use App\Models\Usuario;
use Livewire\Attributes\On;

new class extends Component {
    public function logout(): void
    {
        auth()->logout();
        redirect()->route('login');
    }

    #[On('update-card-perfil')]
    public function with(): array
    {
        $usuario = Usuario::query()
            ->where('id', auth()->id())
            ->with('rol')
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
                <img src="{{ $usuario->avatar }}" alt="user-image"
                    class="user-avtar wid-40 rounded-circle" />
            </div>
            <div class="flex-grow-1 ms-3 me-2">
                <h6 class="mb-0">
                    {{ $usuario->nombre }}
                </h6>
                <small>
                    {{ $usuario->rol->nombre }}
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
                {{-- <a href="">
                    <i class="ti ti-user"></i>
                    <span>Mi cuenta</span>
                </a> --}}
                <a style="cursor: pointer;" wire:click="logout">
                    <i class="ti ti-power"></i>
                    <span>Cerrar sesión</span>
                </a>
            </div>
        </div>
    </div>
</div>
