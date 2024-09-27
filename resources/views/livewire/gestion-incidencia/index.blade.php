<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;

new
#[Layout('components.layouts.app')]
#[Title('Gestion Incidencia | SIGEIN OTI')]
class extends Component {
    // Sirve para usar la paginación
    use WithPagination;

    // Define la variables para el Page Header
    public string $titulo_componente = 'Gestion Incidencia';
    public array $breadcrumbs = [];
     // Define la variable para la cantidad de registros por página
     #[Url(as: 'registros', except: 5)]
     public int $registros = 5;
     // Define la variable para el buscador
     #[Url(as: 'buscador', except: '')]
     public string $search = '';

}; ?>

<div>
    <x-page.header :breadcrumbs="$breadcrumbs" :titulo="$titulo_componente" />
    <div class="row">
        <div class="col-sm-12">
            <div class="card table-card">
                <div class="card-header">
                    <h5>
                        Listado de Incidencias
                    </h5>
                    <small>
                        Listado de incidencias registradas en el sistema.
                    </small>
                    <div class="card-header-right mt-3 me-3">
                        <button class="btn btn-primary" wire:click="cargar('crear', null)">
                            Nueva Incidencia
                        </button>
                    </div>
                </div>
                <div class="card-body pb-0">
                    <div class="table-responsive">
                        <div class="datatable-wrapper datatable-loading no-footer searchable fixed-columns">
                            <!-- Cantidad de Resgistros y Buscador -->
                            <div class="datatable-top mt-3">
                                <!-- Cantida de registros por página -->
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
                                <!-- Buscador -->
                                <div class="datatable-search">
                                    <input type="search" class="datatable-input" placeholder="Buscar..."
                                        wire:model.live="search">
                                </div>
                            </div>
                            <!-- Tabla Incidencias-->
                            <div class="datatable-container">
                                <table class="table table-hover datatable-table mb-0" id="pc-dt-simple">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th class="col-md-3">DESCRIPCIÓN</th>
                                            <th class="text-center">ESTADO</th>
                                            <th class="text-center">CREADO EN</th>
                                            <th class="text-center">ACCIONES</th>
                                        </tr>
                                    </thead>
                                    {{--  <tbody>
                                        @forelse ($incidencias as $incidencia)
                                        <tr wire:key="{{ $incidencia->id_inc }}">
                                            <td>
                                                {{ $incidencia->id_inc }}
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center justify-content-start gap-3">
                                                    <div class="text-center">
                                                        {{ $incidencia->descripcion_inc }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                @if ($incidencia->estado_inc)
                                                <span class="badge bg-light-success rounded f-12"
                                                    wire:click="cargar('status', {{ $incidencia->id_inc }})"
                                                    style="cursor: pointer;">
                                                    <i class="ti ti-circle-check me-1"></i>
                                                    Activo
                                                </span>
                                                @else
                                                <span class="badge bg-light-danger rounded f-12"
                                                    wire:click="cargar('status', {{ $incidencia->id_inc }})"
                                                    style="cursor: pointer;">
                                                    <i class="ti ti-circle-x me-1"></i>
                                                    Inactivo
                                                </span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                {{ \Carbon\Carbon::parse($incidencia->created_at)->format('d/m/Y') }}
                                            </td>
                                            <td class="text-center">
                                                <ul class="list-inline me-auto mb-0">
                                                    <li class="list-inline-item align-bottom" data-bs-toggle="tooltip"
                                                        aria-label="Editar" data-bs-original-title="Editar"
                                                        wire:click="cargar('editar', {{ $incidencia->id_inc }})">
                                                        <a href="#"
                                                            class="avtar avtar-xs btn-link-secondary btn-pc-default">
                                                            <i class="ti ti-edit-circle f-18"></i>
                                                        </a>
                                                    </li>
                                                    <li class="list-inline-item align-bottom" data-bs-toggle="tooltip"
                                                        aria-label="Eliminar" data-bs-original-title="Eliminar"
                                                        wire:click="cargar('eliminar', {{ $incidencia->id_inc }})">
                                                        <a href="#"
                                                            class="avtar avtar-xs btn-link-danger btn-pc-default">
                                                            <i class="ti ti-trash f-18"></i>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-5">
                                                No hay registros para mostrar.
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>  --}}
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                {{--  <div class="card-footer pb-4">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            @if ($incidencias->hasPages())
                            <div class="d-flex justify-content-between">
                                <div class="d-flex align-items-center text-secondary">
                                    Mostrando {{ $incidencias->firstItem() }} -
                                    {{ $incidencias->lastItem() }}
                                    de {{ $incidencias->total() }} registros
                                </div>
                                <div class="">
                                    {{ $incidencias->links() }}
                                </div>
                            </div>
                            @else
                            <div class="d-flex justify-content-between">
                                <div class="d-flex align-items-center text-secondary">
                                    Mostrando {{ $incidencias->firstItem() }} -
                                    {{ $incidencias->lastItem() }}
                                    de {{ $incidencias->total() }} registros
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>  --}}
            </div>
        </div>
    </div>
</div>
