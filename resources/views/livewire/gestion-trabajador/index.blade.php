<?php

use Livewire\Volt\Component;
use Livewire\Attributes\{Layout, Title, Url, Validate};
use App\Models\{Trabajador, TrabajadorActivo};
use Illuminate\Support\Str;
use Livewire\WithPagination;

new 
#[Layout('components.layouts.app')]
#[Title('Activos Informaticos | SIGEIN OTI')]
class extends Component {

    // Sirve para usar la paginación
    use WithPagination;

    // Define la variables para el Page Header
    public string $titulo_componente = 'Gestión de Trabajadores';
    public array $breadcrumbs = [];

    // Define la variable para la cantidad de registros por página
    #[Url(as: 'registros', except: 5)]
    public int $registros = 5;
    
    // Define la variable para el buscador
    #[Url(as: 'buscador', except: '')]
    public string $search = '';

    // Metodo que se inicia con el componente
    public function mount(): void
    {
        $this->titulo_componente = 'Gestión de Trabajadores';
        $this->breadcrumbs = [
            ['url' => route('inicio.index'), 'title' => 'Inicio'],
            ['url' => '', 'title' => 'Configuración'],
            ['url' => '', 'title' => 'Gestion de Trabajador'],
        ];
    }

    public function with(): array
    {
        $trabajadores = Trabajador::query()
            ->search($this->search)
            ->paginate($this->registros);

        return [
            'trabajadores' => $trabajadores,
        ];
    }

}; ?>

<div>
    <x-page.header :breadcrumbs="$breadcrumbs" :titulo="$titulo_componente" />
    <!-- Formulario -->
    <div class="row">
        <div class="col-sm-12">
            <div class="card table-card">
                <div class="card-header">
                    <h5>
                        Listado de Trabajadores
                    </h5>
                    <small>
                        Listado de trabajadores registrados en el sistema.
                    </small>
                    <div class="card-header-right mt-3 me-3">
                        <button
                            class="btn btn-primary"
                            wire:click="cargar('crear', null)"
                        >
                            Nuevo Registro
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
                                        <select
                                            class="datatable-selector"
                                            wire:model.live="registros"
                                        >
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
                                    <input
                                        type="search"
                                        class="datatable-input"
                                        placeholder="Buscar..."
                                        wire:model.live="search"
                                    >
                                </div>
                            </div>
                            <!-- Tabla Activos -->
                            <div class="datatable-container">
                                <table class="table table-hover datatable-table mb-0" id="pc-dt-simple">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th class="col-md-3">TRABAJADOR</th>
                                            <th class="text-center">OFICINA</th>
                                            <th class="text-center">CARGO</th>
                                            <th class="text-center">F. CREACION</th>
                                            <th class="text-center">ESTADO</th>
                                            <th class="text-center">ACCIONES</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($trabajadores as $trabajador)
                                            <tr wire:key="{{ $trabajador->id_tra }}">
                                                <td>
                                                    {{ $trabajador->id_tra }}
                                                </td>
                                                <td>
                                                    {{ $trabajador->nombres_tra }}
                                                </td>
                                                <td>
                                                    {{ $trabajador->oficina_cargo->oficina->nombre_ofi }}
                                                </td>
                                                <td>
                                                    {{ $trabajador->oficina_cargo->cargo->nombre_car }}
                                                </td>
                                                <td class="text-center">
                                                    {{ \Carbon\Carbon::parse($trabajador->created_at)->format('d/m/Y') }}
                                                </td>
                                                <td class="text-center">
                                                    @if ($trabajador->activo_tra)
                                                        <span
                                                            class="badge bg-light-success rounded f-12"
                                                            wire:click="cargar('status', {{ $trabajador->id_tra }})"
                                                            style="cursor: pointer;"
                                                        >
                                                            <i class="ti ti-circle-check me-1"></i>
                                                            Activo
                                                        </span>
                                                    @else
                                                        <span
                                                            class="badge bg-light-danger rounded f-12"
                                                            wire:click="cargar('status', {{ $trabajador->id_tra }})"
                                                            style="cursor: pointer;"
                                                        >
                                                            <i class="ti ti-circle-x me-1"></i>
                                                            Inactivo
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <ul class="list-inline me-auto mb-0">
                                                        <li
                                                            class="list-inline-item align-bottom"
                                                            data-bs-toggle="tooltip"
                                                            aria-label="Editar"
                                                            data-bs-original-title="Editar"
                                                            wire:click="cargar('editar', {{ $trabajador->id_ain }})"
                                                        >
                                                            <a
                                                                href="#"
                                                                class="avtar avtar-xs btn-link-secondary btn-pc-default"
                                                            >
                                                                <i class="ti ti-edit-circle f-18"></i>
                                                            </a>
                                                        </li>
                                                        <li
                                                            class="list-inline-item align-bottom"
                                                            data-bs-toggle="tooltip"
                                                            aria-label="Eliminar"
                                                            data-bs-original-title="Eliminar"
                                                            wire:click="cargar('eliminar', {{ $trabajador->id_ain }})"
                                                        >
                                                            <a
                                                                href="#"
                                                                class="avtar avtar-xs btn-link-danger btn-pc-default"
                                                            >
                                                                <i class="ti ti-trash f-18"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </td>
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
                <div class="card-footer pb-4">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            @if ($trabajadores->hasPages())
                                <div class="d-flex justify-content-between">
                                    <div class="d-flex align-items-center text-secondary">
                                        Mostrando {{ $trabajadores->firstItem() }} -
                                        {{ $trabajadores->lastItem() }}
                                        de {{ $trabajadores->total() }} registros
                                    </div>
                                    <div class="">
                                        {{ $trabajadores->links() }}
                                    </div>
                                </div>
                            @else
                                <div class="d-flex justify-content-between">
                                    <div class="d-flex align-items-center text-secondary">
                                        Mostrando {{ $trabajadores->firstItem() }} -
                                        {{ $trabajadores->lastItem() }}
                                        de {{ $trabajadores->total() }} registros
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
