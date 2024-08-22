<?php

use Livewire\Volt\Component;

new class extends Component {
    public function with(): array
    {
        $usuario = auth()->user();
        return [
            'usuario' => $usuario,
        ];
    }
}; ?>

<nav class="pc-sidebar">
    <div class="navbar-wrapper">
        <div class="m-header">
            <a href="{{ route('home.index') }}" class="b-brand d-flex align-items-center gap-2">
                <img src="{{ asset('media/img/logo-unia-2.png') }}" width="25" height="30" alt="logo" />
                <span style="font-weight: 800" class="fs-3 text-indigo-500">SIGEIN OTI</span>
                <span class="badge bg-orange-200 rounded-pill theme-version">v1.0</span>
            </a>
        </div>
        <div class="navbar-content">
            {{-- <livewire:components.card-perfil /> --}}

            <ul class="pc-navbar">
                <!-- Inicio -->
                {{-- @if ($usuario->permiso('inicio-index')) --}}
                <li class="pc-item {{ request()->routeIs('home.*') ? 'active' : '' }}">
                    <a href="{{ route('home.index') }}" class="pc-link">
                        <span class="pc-micon">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-home"></use>
                            </svg>
                        </span>
                        <span class="pc-mtext">Inicio</span>
                    </a>
                </li>
                {{-- @endif --}}
                {{-- <!-- Seguridad -->
                @if ($usuario->permiso('usuario-index') || $usuario->permiso('rol-index') || $usuario->permiso('permiso-index'))
                <li class="pc-item pc-hasmenu {{ request()->routeIs('seguridad.*') ? 'pc-trigger active' : '' }}">
                    <a class="pc-link" style="cursor: pointer;">
                        <span class="pc-micon">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-security-safe"></use>
                            </svg>
                        </span>
                        <span class="pc-mtext">
                            Seguridad
                        </span>
                        <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                    </a>
                    <ul class="pc-submenu">
                        @if ($usuario->permiso('usuario-index'))
                        <li class="pc-item {{ request()->routeIs('seguridad.usuario-*') ? 'active' : '' }}">
                            <a class="pc-link" href="{{ route('seguridad.usuario-index') }}">Usuarios</a>
                        </li>
                        @endif
                        @if ($usuario->permiso('rol-index'))
                        <li class="pc-item {{ request()->routeIs('seguridad.rol-*') ? 'active' : '' }}">
                            <a class="pc-link" href="{{ route('seguridad.rol-index') }}">Roles</a>
                        </li>
                        @endif
                        @if ($usuario->permiso('permiso-index'))
                        <li class="pc-item {{ request()->routeIs('seguridad.permiso-*') ? 'active' : '' }}">
                            <a class="pc-link" href="{{ route('seguridad.permiso-index') }}">Permisos</a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif
                <!-- Resoluciones -->
                @if ($usuario->permiso('resoluciones-index'))
                <li class="pc-item {{ request()->routeIs('resoluciones-*') ? 'active' : '' }}">
                    <a href="{{ route('resoluciones-index') }}" class="pc-link">
                        <span class="pc-micon">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-document"></use>
                            </svg>
                        </span>
                        <span class="pc-mtext">Resoluciones</span>
                    </a>
                </li>
                @endif
                <!-- Gestion de Archivos -->
                @if ($usuario->permiso('documento-index') || $usuario->permiso('tipo-documento-index'))
                <li class="pc-item pc-hasmenu {{ request()->routeIs('gestion-documentos.*') ? 'pc-trigger active' : '' }}">
                    <a class="pc-link" style="cursor: pointer;">
                        <span class="pc-micon">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-folder-open"></use>
                            </svg>
                        </span>
                        <span class="pc-mtext">
                            Gestion de Documentos
                        </span>
                        <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                    </a>
                    <ul class="pc-submenu">
                        @if ($usuario->permiso('documento-index'))
                        <li class="pc-item">
                            <a class="pc-link" href="">Documentos</a>
                        </li>
                        @endif
                        @if ($usuario->permiso('tipo-documento-index'))
                        <li class="pc-item {{ request()->routeIs('gestion-documentos.tipo-documentos-*') ? 'active' : '' }}">
                            <a class="pc-link" href="{{ route('gestion-documentos.tipo-documentos-index') }}">Tipo de Documentos</a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif
                <!-- Organos Emisores -->
                @if ($usuario->permiso('organo-emisor-index'))
                <li class="pc-item {{ request()->routeIs('organos-emisores-*') ? 'active' : '' }}">
                    <a href="{{ route('organos-emisores-index') }}" class="pc-link">
                        <span class="pc-micon">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-notification-status"></use>
                            </svg>
                        </span>
                        <span class="pc-mtext">Organos Emisores</span>
                    </a>
                </li>
                @endif
                <!-- Configuración -->
                @if ($usuario->permiso('manual-index'))
                <li class="pc-item pc-hasmenu {{ request()->routeIs('configuracion.*') ? 'pc-trigger active' : '' }}">
                    <a class="pc-link" style="cursor: pointer;">
                        <span class="pc-micon">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-setting-2"></use>
                            </svg>
                        </span>
                        <span class="pc-mtext">
                            Configuración
                        </span>
                        <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                    </a>
                    <ul class="pc-submenu">
                        @if ($usuario->permiso('manual-index'))
                        <li class="pc-item {{ request()->routeIs('configuracion.manual-*') ? 'active' : '' }}">
                            <a class="pc-link" href="{{ route('configuracion.manual-index') }}">
                                Manuales
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif --}}
                <li class="pc-item">
                    <div class="card border-0 shadow-none drp-upgrade-card mb-0 mx-1"
                        style="background-image: url({{ asset('assets/images/layout/img-profile-card.jpg') }})">
                        <div class="card-body">
                            <h4 class="mb-3 text-dark fw-bold">
                                Primera Univerdad Intercultural del Perú
                                <img src="{{ asset('media/img/peru.png') }}" alt="img" width="26"
                                    class="img-fluid roundes-pill ms-2">
                            </h4>
                            <a href="https://unia.edu.pe" target="_blank"
                                class="btn btn-warning">
                                <svg class="pc-icon me-2">
                                    <use xlink:href="#custom-airplane"></use>
                                </svg>
                                Visita Nuestra Pagina Web
                            </a>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
