<?php

use Livewire\Volt\Component;
use Livewire\Attributes\{Layout, Title, Validate};
use App\Models\{Usuario};
use Illuminate\Support\Facades\Hash;

new #[Layout('components.layouts.auth')] #[Title('Login | SIGEIN OTI')] class extends Component {
    #[Validate('required|email')]
    public string $correo = '';
    #[Validate('required')]
    public string $contrasena = '';
    public $recuerdame = false;

    public string $typeInput = 'password';
    public string $icon = 'feather icon-eye';

    public function login()
    {
        sleep(2);

        // $this->validate();

        // $usuario = Usuario::query()
        //     ->where('correo', $this->correo)
        //     ->first();

        // if (!$usuario) {
        //     $this->dispatch('toast', text: 'Credenciales incorrectas.', color: 'danger');
        //     return;
        // }

        // if (!Hash::check($this->contrasena, $usuario->password)) {
        //     $this->dispatch('toast', text: 'Credenciales incorrectas.', color: 'danger');
        //     return;
        // }

        // auth()->login($usuario);

        // return redirect(route('home-index'));
    }

    public function changeTypeInput(): void
    {
        $this->typeInput = $this->typeInput === 'password' ? 'text' : 'password';

        $this->icon = $this->typeInput === 'password' ? 'feather icon-eye' : 'feather icon-eye-off';
    }
}; ?>

<div class="auth-form">
    <div
        class="card my-5 animate__animated animate__fadeIn animate__faster"
        {{-- style="background-image: url('{{ asset('media/img/fondo-unia.webp') }}'); background-size: cover; background-repeat: no-repeat; background-attachment: fixed; background-position: center;" --}}
    >
        <form class="card-body" wire:submit="login">
            <div class="text-center">
                <img src="{{ asset('media/img/logo-unia-2.png') }}" alt="img" class="img-fluid mb-4" width="90" />
                <p class="fs-7 fw-semibold text-gray-600">
                    Bienvenido al Sistema de Gestión de Incidencias de la Universidad Nacional
                    Intercultural de la Amazonía - UNIA
                </p>
            </div>
            <h3 class="text-center f-w-500 fw-bold my-4">
                Inicie Sesión con su Cuenta
            </h3>
            <div class="mb-3">
                <input type="text" wire:model.live="correo"
                    class="form-control @if ($errors->has('correo')) is-invalid @endif" id="correo"
                    placeholder="Correo Electrónico" />
                @error('correo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <div class="input-group search-form">
                    <input type="{{ $typeInput }}" wire:model.live="contrasena"
                        class="form-control @if ($errors->has('contrasena')) is-invalid @endif" id="contrasena"
                        placeholder="Contraseña">
                    <span class="input-group-text bg-transparent" style="cursor: pointer;" wire:click="changeTypeInput">
                        <i class="{{ $icon }} text-muted"></i>
                    </span>
                </div>
                @error('contrasena')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="d-flex mt-1 justify-content-between align-items-center">
                <div class="form-check">
                    <input class="form-check-input input-primary" type="checkbox" id="recuerdame" wire:model.live="recuerdame" />
                    <label class="form-check-label text-muted" for="recuerdame">
                        Recuerdame
                    </label>
                </div>
                <h6 class="text-secondary f-w-400 mb-0">
                    <a href="#">
                        ¿Olvidaste tu contrasena?
                    </a>
                </h6>
            </div>
            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="login">
                    <span wire:loading.remove wire:target="login">
                        Ingresar
                    </span>
                    <div
                        wire:loading
                        wire:target="login"
                        wire:loading.class="d-flex justify-content-center align-items-center gap-2"
                    >
                        <span class="spinner-border spinner-border-sm" role="status"></span>
                        Cargando...
                    </div>
                </button>
            </div>
        </form>
        <div class="text-center text-muted mb-4">
            © {{ date('Y') }} Oficina de Tecnologías de la Información - UNIA
        </div>
    </div>
</div>
