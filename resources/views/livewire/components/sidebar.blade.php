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
            <a href="{{ route('inicio.index') }}" class="b-brand d-flex align-items-center gap-2">
                <img src="{{ asset('media/img/logo-unia-2.png') }}" width="25" height="30" alt="logo" />
                <span style="font-weight: 800" class="fs-3 text-indigo-500">SIGEIN OTI</span>
                <span class="badge bg-orange-200 rounded-pill theme-version">v1.0</span>
            </a>
        </div>
        <div class="navbar-content">
            <livewire:components.card-perfil />

            <ul class="pc-navbar">
                <!-- Inicio -->
                <li class="pc-item {{ request()->routeIs('inicio.*') ? 'active' : '' }}">
                    <a href="{{ route('inicio.index') }}" class="pc-link">
                        <span class="pc-micon">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-home"></use>
                            </svg>
                        </span>
                        <span class="pc-mtext">Inicio</span>
                    </a>
                </li>
                <!-- Seguridad -->
                <li class="pc-item pc-hasmenu">
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
                        <!-- Usuarios -->
                        <li class="pc-item {{ request()->routeIs('seguridad.usuario.*') ? 'active' : ''}}">
                            <a class="pc-link" href="{{ route('seguridad.usuario.index') }}">
                                Usuarios
                            </a>
                        </li>
                        <!-- Roles -->
                        <li class="pc-item {{ request()->routeIs('seguridad.rol.*') ? 'active' : '' }}">
                            <a class="pc-link" href="{{ route('seguridad.rol.index') }}">
                                Roles
                            </a>
                        </li>
                        <!-- Permisos -->
                        <li class="pc-item {{ request()->routeIs('seguridad.permiso.*') ? 'active' : '' }}">
                            <a class="pc-link" href="{{ route('seguridad.permiso.index') }}">
                                Permisos
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- Gestion de Trabajador -->
                <li class="pc-item">
                    <a href="" class="pc-link">
                        <span class="pc-micon">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-user-add"></use>
                            </svg>
                        </span>
                        <span class="pc-mtext">Gestión de Trabajadores</span>
                    </a>
                </li>
                <!-- Gestion de Incidencia -->
                <li class="pc-item {{ request()->routeIs('incidencia.*') ? 'active' : '' }}">
                    <a href="{{ route('gestion-incidencia.index') }}" class="pc-link">
                        <span class="pc-micon">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-folder-open"></use>
                            </svg>
                        </span>
                        <span class="pc-mtext">Gestión de Incidencias</span>
                    </a>
                </li>
                <!-- Configuración -->
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
                        <!-- Activos Informáticos -->
                        <li class="pc-item">
                            <a class="pc-link" href="">
                                Activos Informáticos
                            </a>
                        </li>
                        <!-- Oficinas -->
                        <li class="pc-item {{ request()->routeIs('configuracion.oficina.*') ? 'active' : '' }}">
                            <a class="pc-link" href="{{ route('configuracion.oficina.index') }}">
                                Oficinas
                            </a>
                        </li>
                        <!-- Complejidad Incidencia -->
                        <li class="pc-item {{ request()->routeIs('configuracion.complejidad-incidencia.*') ? 'active' : '' }}">
                            <a class="pc-link" href="{{ route('configuracion.complejidad-incidencia.index') }}">
                                Complejidad Incidencia
                            </a>
                        </li>
                    </ul>
                </li>
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
