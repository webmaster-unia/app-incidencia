<?php

use Livewire\Volt\Component;
use Livewire\Attributes\{Layout, Title, Url, Validate};
use App\Models\{ActivoInformatico, TipoActivo};
use Illuminate\Support\Str;
use Livewire\WithPagination;

new 
#[Layout('components.layouts.app')]
#[Title('Activos Informaticos | SIGEIN OTI')]
class extends Component {

    // Sirve para usar la paginación
    use WithPagination;

    // Define la variables para el Page Header
    public string $titulo_componente = 'Activo Informático';
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
        $this->titulo_componente = 'Activo Informático';
        $this->breadcrumbs = [
            ['url' => route('inicio.index'), 'title' => 'Inicio'],
            ['url' => '', 'title' => 'Configuración'],
            ['url' => '', 'title' => 'Activo Informático'],
        ];
    }

    // Metodo para actualizar la busqueda
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    // // Metodo para actualizar la cantidad de registros
    // public function updatedRegistros(): void
    // {
    //     $this->resetPage();
    // }

    // // Metodo para cargar los datos de tipos de activos
    // public function cargar(string $action, int $id = 0): void
    // {
    //     $this->action = $action;
    //     $this->acciones = [
    //         'crear' => 'crearActivo',
    //         'editar' => 'editarActivo',
    //         'eliminar' => 'eliminarActivo',
    //         'status' => 'statusActivo',
    //     ];

    //     if ($action === 'crear') {
    //         $this->titulo_modal = 'Nuevo Activo';
    //         $this->nombre = '';
    //         $this->nombreTipo = '';
    //     } else {
    //         $activo = ActivoInformatico::find($id);
    //         $this->nombre = $activo->nombre_ain;
    //         $this->nombreTipo = $activo->tipoActivo->nombre_tac;
    //     }

    //     $this->emit('openModal', $this->nombre_modal);
    // }

    // // Metodo que renderiza la vista
    // public function with(): array
    // {
    //     $activos = ActivoInformatico::query()
    //         ->with('tipoActivo')
    //         ->search($this->search)
    //         ->paginate($this->registros);

    //     return [
    //         'oficinas' => $activos
    //     ];
    // }
   
}; ?>

<div>
    <x-page.header :breadcrumbs="$breadcrumbs" :titulo="$titulo_componente" />
    <!-- Formulario -->
    <div class="row">
        <div class="col-sm-12">
            <div class="card table-card">
                <div class="card-header">
                    <h5>
                        Listado de Activos Informáticos
                    </h5>
                    <small>
                        Listado de activos informáticos registrados en el sistema.
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
                                            <th class="col-md-3">NOMBRE</th>
                                            <th class="text-center">TIPO ACTIVO</th>
                                            <th class="text-center">ESTADO</th>
                                            <th class="text-center">ACCIONES</th>
                                        </tr>
                                    </thead>
                                    {{-- <tbody>
                                        @forelse ($activos as $activo)
                                            <tr wire:key="{{ $activo->id_ain }}">
                                                <td>
                                                    {{ $activo->id_ain }}
                                                </td>
                                                <td>
                                                    {{ $activo->nombre_ain }}
                                                </td>
                                                <td>
                                                    {{ $activo->nombre_tac }}
                                                </td>
                                                
                                                <td class="text-center">
                                                    @if ($activo->activo_ain)
                                                        <span
                                                            class="badge bg-light-success rounded f-12"
                                                            wire:click="cargar('status', {{ $activo->id_ain }})"
                                                            style="cursor: pointer;"
                                                        >
                                                            <i class="ti ti-circle-check me-1"></i>
                                                            Activo
                                                        </span>
                                                    @else
                                                        <span
                                                            class="badge bg-light-danger rounded f-12"
                                                            wire:click="cargar('status', {{ $activo->id_ain }})"
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
                                                            wire:click="cargar('editar', {{ $activo->id_ain }})"
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
                                                            wire:click="cargar('eliminar', {{ $activo->id_ain }})"
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
                                    </tbody> --}}
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="card-footer pb-4">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            @if ($oficinas->hasPages())
                                <div class="d-flex justify-content-between">
                                    <div class="d-flex align-items-center text-secondary">
                                        Mostrando {{ $oficinas->firstItem() }} -
                                        {{ $oficinas->lastItem() }}
                                        de {{ $oficinas->total() }} registros
                                    </div>
                                    <div class="">
                                        {{ $oficinas->links() }}
                                    </div>
                                </div>
                            @else
                                <div class="d-flex justify-content-between">
                                    <div class="d-flex align-items-center text-secondary">
                                        Mostrando {{ $oficinas->firstItem() }} -
                                        {{ $oficinas->lastItem() }}
                                        de {{ $oficinas->total() }} registros
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
    <!-- Modal -->
    {{-- <div wire:ignore.self id="{{ $nombre_modal }}" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form class="modal-content" wire:submit="{{ $action_form }}">
                <div class="modal-header animate__animated animate__fadeIn animate__faster">
                    <h5 class="modal-title">
                        {{ $titulo_modal }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        wire:click="reset_modal"></button>
                </div>
                <div class="modal-body animate__animated animate__fadeIn animate__faster">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label" for="nombre">
                                Nombre del tipo activo <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                class="form-control @if ($errors->has('nombre')) is-invalid @elseif($nombre) is-valid @endif"
                                wire:model.live="nombre" id="nombre" placeholder="Ingrese el nombre de la oficina">
                            <small class="form-text text-muted">
                                Ingrese el nombre del activo.
                            </small>
                            @error('nombre')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <div class="row gy-1 gx-3 align-items-center">
                                <div class="col-md-12">
                                    <label class="form-label">
                                        Lista de activos
                                    </label>
                                </div>
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <input
                                            type="text"
                                            class="form-control @if ($errors->has('cargo')) is-invalid @elseif($cargo) is-valid @endif"
                                            wire:model.live="cargo"
                                            id="cargo"
                                            placeholder="Ingrese el cargo de la oficina"
                                        >
                                        <button
                                            class="btn btn-light-primary d-flex align-items-center"
                                            type="button"
                                            wire:click="agregar_cargo"
                                        >
                                            <i class="ti ti-plus fs-4"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="row g-1">
                                @foreach ($cargos as $cargo)
                                    <div class="col-6 col-lg-4">
                                        <div
                                            class="card mb-1"
                                        >
                                            <div
                                                class="py-1 px-2 d-flex justify-content-between align-items-center gap-2"
                                            >
                                                <div>
                                                    <input
                                                        class="form-check-input me-1 @if ($errors->has('cargosSeleccionados')) is-invalid @endif"
                                                        type="checkbox"
                                                        wire:model.live="cargosSeleccionados"
                                                        id="cargo-{{ $cargo->id_car }}"
                                                        value="{{ $cargo->id_car }}"
                                                    >
                                                    <label
                                                        for="cargo-{{ $cargo->id_car }}"
                                                        class="@if ($errors->has('cargosSeleccionados')) text-danger @endif"
                                                    >
                                                        {{ $cargo->nombre_car }}
                                                    </label>
                                                </div>
                                                <button
                                                    type="button"
                                                    class="btn btn-icon btn-link-danger"
                                                    wire:click="eliminar_cargo({{ $cargo->id_car }})"
                                                    wire:confirm="¿Está seguro de eliminar el cargo?"
                                                >
                                                    <i class="ti ti-square-x fs-4 text-danger"></i>
                                                </button>

                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer animate__animated animate__fadeIn animate__faster">
                    <button type="button" class="btn btn-light-danger" data-bs-dismiss="modal"
                        wire:click="reset_modal">
                        Cerrar
                    </button>
                    <button type="submit" class="btn btn-primary" style="width: 100px;"
                        wire:loading.attr="disabled" wire:target="guardar">
                        <span wire:loading.remove wire:target="guardar">
                            Guardar
                        </span>
                        <div class="spinner-border spinner-border-sm" role="status" wire:loading
                            wire:target="guardar">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </button>
                </div>
            </form>
        </div>
    </div> --}}
    
</div>