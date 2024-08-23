<?php

use Livewire\Volt\Component;
use Livewire\Attributes\{Layout, Title};
use App\Models\{Oficina};

new
#[Layout('components.layouts.app')]
#[Title('Oficina | SIGEIN OTI')]
class extends Component {

    // Define la variables para el Page Header
    public string $titulo_componente = 'Oficina';
    public array $breadcrumbs = [];

    // Metodo que se inicia con el componente
    public function mount(): void
    {
        $this->titulo_componente = 'Oficina';
        $this->breadcrumbs = [
            ['url' => route('inicio.index'), 'title' => 'Inicio'],
            ['url' => '', 'title' => 'Configuración'],
            ['url' => '', 'title' => 'Oficina']
        ];
    }

    // Metodo que renderiza la vista
    public function with(): array
    {
        $oficinas = Oficina::all();

        return [
            'oficinas' => $oficinas
        ];
    }
}; ?>

<div>
    <x-page.header :breadcrumbs="$breadcrumbs" :titulo="$titulo_componente" />
    <div class="row">
        <div class="col-sm-12">
            <div class="card table-card">
                <div class="card-header">
                    <h5>asd</h5>
                    <small>
                        asd
                    </small>
                    <div class="card-header-right mt-3 me-3">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-usuario"
                            wire:click="cargar('create', null)">
                            Nuevo Registro
                        </button>
                    </div>
                </div>
                <div class="card-body pb-0">
                    <div class="table-responsive">
                        <div class="datatable-wrapper datatable-loading no-footer searchable fixed-columns">
                            <div class="datatable-top mt-3">
                                <div class="datatable-dropdown">
                                    <label>
                                        <select class="datatable-selector" wire:model.live="registros">
                                            <option value="5">5</option>
                                            <option value="10">10</option>
                                            <option value="15">15</option>
                                            <option value="20">20</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select> registros por página
                                    </label>
                                </div>
                                <div class="datatable-search">
                                    <input class="datatable-input" placeholder="Buscar..." type="search"
                                        wire:model.live="search">
                                </div>
                            </div>
                            <div class="datatable-container">
                                <table class="table table-hover datatable-table mb-0" id="pc-dt-simple">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th class="col-md-3">NOMBRE</th>
                                            <th>CARGOS</th>
                                            <th class="text-center">ESTADO</th>
                                            <th class="text-center">ACCIONES</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($oficinas as $item)
                                            <tr wire:key="{{ $item->id_ofi }}">
                                                <td>
                                                    {{ $item->id_ofi }}
                                                </td>
                                                <td>
                                                    {{ $item->nombre_ofi }}
                                                </td>
                                                <td>
                                                    -
                                                </td>
                                                <td class="text-center">
                                                    @if ($item->activo_ofi)
                                                        <span
                                                            class="badge bg-light-success rounded f-12"
                                                        >
                                                            <i class="ti ti-circle-check me-1"></i>
                                                            Activo
                                                        </span>
                                                    @else
                                                        <span
                                                            class="badge bg-light-danger rounded f-12"
                                                        >
                                                            <i class="ti ti-circle-x me-1"></i>
                                                            Inactivo
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    acciones
                                                </td>
                                                {{-- <td>
                                                    {{ $item->id }}
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center justify-content-start gap-3">
                                                        <img src="{{ $item->avatar }}" alt="user image"
                                                            class="img-radius wid-40 rounded-circle">
                                                        <div class="d-inline-block">
                                                            <span class="fs-6 fw-bold">
                                                                {{ $item->nombre }}
                                                            </span>
                                                            <br>
                                                            <span class="text-muted">
                                                                {{ $item->correo }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    {{ $item->oficina }}
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge text-bg-light text-dark rounded f-12">
                                                        {{ $item->rol->nombre }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    {{ convertirFechaHora($item->created_at) }}
                                                </td>
                                                <td class="text-center">
                                                    @if ($item->activo)
                                                        <span class="badge bg-light-success rounded f-12"
                                                            @if ($usuario_auth->permiso('usuario-status')) data-bs-toggle="modal" data-bs-target="#alerta"
                                                            style="cursor: pointer;"
                                                            wire:click="cargar('status', {{ $item->id }})" @endif>
                                                            Activo
                                                        </span>
                                                    @else
                                                        <span class="badge bg-light-danger rounded f-12"
                                                            @if ($usuario_auth->permiso('usuario-status')) data-bs-toggle="modal" data-bs-target="#alerta"
                                                            style="cursor: pointer;"
                                                            wire:click ="cargar('status', {{ $item->id }})" @endif>
                                                            Inactivo
                                                        </span>
                                                    @endif
                                                </td>
                                                @if (
                                                    $usuario_auth->permiso('usuario-asignar-organos') ||
                                                        $usuario_auth->permiso('usuario-edit') ||
                                                        $usuario_auth->permiso('usuario-delete'))
                                                    <td class="text-center">
                                                        <ul class="list-inline me-auto mb-0">
                                                            @if ($usuario_auth->permiso('usuario-asignar-organos'))
                                                                <li class="list-inline-item align-bottom"
                                                                    data-bs-toggle="tooltip"
                                                                    aria-label="Asignar Organos Emisores"
                                                                    data-bs-original-title="Asignar Organos Emisores">
                                                                    <a href="#"
                                                                        class="avtar avtar-xs btn-link-warning btn-pc-default"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#modal-asignar"
                                                                        wire:click="cargar('asignar', {{ $item->id }})">
                                                                        <i class="ti ti-chart-candle f-18"></i>
                                                                    </a>
                                                                </li>
                                                            @endif
                                                            @if ($usuario_auth->permiso('usuario-edit'))
                                                                <li class="list-inline-item align-bottom"
                                                                    data-bs-toggle="tooltip" aria-label="Editar"
                                                                    data-bs-original-title="Editar">
                                                                    <a href="#"
                                                                        class="avtar avtar-xs btn-link-info btn-pc-default"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#modal-usuario"
                                                                        wire:click="cargar('edit', {{ $item->id }})">
                                                                        <i class="ti ti-edit-circle f-18"></i>
                                                                    </a>
                                                                </li>
                                                            @endif
                                                            @if ($usuario_auth->permiso('usuario-delete'))
                                                                <li class="list-inline-item align-bottom"
                                                                    data-bs-toggle="tooltip" aria-label="Eliminar"
                                                                    data-bs-original-title="Eliminar">
                                                                    <a href="#"
                                                                        class="avtar avtar-xs btn-link-danger btn-pc-default"
                                                                        data-bs-toggle="modal" data-bs-target="#alerta"
                                                                        wire:click="cargar('delete', {{ $item->id }})">
                                                                        <i class="ti ti-trash f-18"></i>
                                                                    </a>
                                                                </li>
                                                            @endif
                                                        </ul>
                                                    </td>
                                                @endif --}}
                                            </tr>
                                        @empty
                                            <tr>
                                                <td
                                                    colspan="5"
                                                    class="text-center text-muted py-5"
                                                >
                                                    No hay registros para mostrar.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="card-footer pb-4">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            @if ($usuarios->hasPages())
                                <div class="d-flex justify-content-between">
                                    <div class="d-flex align-items-center text-secondary">
                                        Mostrando {{ $usuarios->firstItem() }} -
                                        {{ $usuarios->lastItem() }}
                                        de {{ $usuarios->total() }} registros
                                    </div>
                                    <div class="">
                                        {{ $usuarios->links() }}
                                    </div>
                                </div>
                            @else
                                <div class="d-flex justify-content-between">
                                    <div class="d-flex align-items-center text-secondary">
                                        Mostrando {{ $usuarios->firstItem() }} -
                                        {{ $usuarios->lastItem() }}
                                        de {{ $usuarios->total() }} registros
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
</div>
