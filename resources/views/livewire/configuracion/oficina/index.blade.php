<?php

use Livewire\Volt\Component;
use Livewire\Attributes\{Layout, Title, Url};
use App\Models\{Oficina, Cargo};
use Livewire\WithPagination;

new
#[Layout('components.layouts.app')]
#[Title('Oficina | SIGEIN OTI')]
class extends Component {

    // Sirve para usar la paginación
    use WithPagination;

    // Define la variables para el Page Header
    public string $titulo_componente = 'Oficina';
    public array $breadcrumbs = [];

    // Define la variable para la cantidad de registros por página
    #[Url(as: 'registros', except: 5)]
    public int $registros = 5;

    // Define la variable para el buscador
    #[Url(as: 'buscador', except: '')]
    public string $search = '';

    // Variables del modal
    public string $titulo_modal = 'Nueva Oficina';
    public string $nombre_modal = 'modal-oficina';
    public string $alerta = '';
    public string $mensaje = '';
    public string $action = '';
    public array $acciones = [];

    // Variables para el formulario
    public string $modo_modal = 'crear';
    public $id_oficina = null;

    // Variables para el formulario
    public string $nombre = '';
    public string $cargo = '';
    public $cargos = [];

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

    public function cargar(string $modo, ?int $id): void
    {
        // $this->reset_modal();
        $this->modo_modal = $modo;
        $this->id_oficina = $id;

        if ($modo === 'crear') {
            $this->titulo_modal = 'Nuevo Registro';
            $this->cargos = Cargo::query()->get();
            $this->dispatch('modal',
                modal: '#'.$this->nombre_modal,
                action: 'show'
            );
        } elseif ($modo === 'edit') {
            //
        } elseif ($modo === 'delete') {
            // $this->titulo_modal = '';
            // $this->alerta = '¡Atención!';
            // $this->mensaje = '¿Está seguro de eliminar el registro?';
            // $this->action = 'eliminar';
        } elseif ($modo === 'status') {
            //
        }
    }

    // Metodo que renderiza la vista
    public function with(): array
    {
        $oficinas = Oficina::query()
            ->with('cargos')
            ->search($this->search)
            ->paginate($this->registros);

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
                    <h5>
                        Listado de Oficinas
                    </h5>
                    <small>
                        Listado de oficinas registradas en el sistema.
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
                            <!-- Tabla Oficina -->
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
                                                    @forelse ($item->cargos()->limit(3)->get() as $cargo)
                                                    <span class="badge text-bg-light text-dark rounded f-12">
                                                        {{ $cargo->nombre_car }}
                                                    </span>
                                                    @empty
                                                    <span class="badge text-bg-light text-dark rounded f-12">
                                                        Sin cargos
                                                    </span>
                                                    @endforelse
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
                                                    <ul class="list-inline me-auto mb-0">
                                                        <li
                                                            class="list-inline-item align-bottom"
                                                            data-bs-toggle="tooltip"
                                                            aria-label="Editar"
                                                            data-bs-original-title="Editar"
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
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div wire:ignore.self id="{{ $nombre_modal }}" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form class="modal-content" wire:submit="guardar">
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
                                Nombre de la oficina <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                class="form-control @if ($errors->has('nombre')) is-invalid @elseif($nombre) is-valid @endif"
                                wire:model.live="nombre" id="nombre" placeholder="Ingrese el nombre de la oficina">
                            <small class="form-text text-muted">
                                Ingrese el nombre de la oficina.
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
                                        Lista de cargos
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
                                        >
                                            <i class="ti ti-plus fs-4"></i>
                                        </button>
                                    </div>
                                </div>
                                {{-- <div class="col-md-8">
                                    <input type="text"
                                        class="form-control @if ($errors->has('cargo')) is-invalid @elseif($cargo) is-valid @endif"
                                        wire:model.live="cargo" id="cargo"
                                        placeholder="Ingrese el cargo de la oficina">
                                </div>
                                <div class="col-md-4">
                                    <button type="button"
                                        class="btn btn-icon btn-light-primary @if (!$cargo) disabled @endif"
                                        wire:click="agregar_cargo">
                                        <i class="ti ti-plus"></i>
                                    </button>
                                </div> --}}
                            </div>
                            <small class="form-text text-muted">
                                Ingrese el cargo de la oficina.
                            </small>
                        </div>
                        <div class="col-md-12">
                            <div class="row gy-1 gx-3">
                                {{-- @foreach ($cargos as $key => $accion)
                                    <div class="col-md-4" wire:key="{{ $key }}">
                                        <div class="form-check mb-2">
                                            <input
                                                class="form-check-input input-light-primary @if ($errors->has('acciones_seleccionadas')) is-invalid @endif"
                                                type="checkbox" id="{{ $accion['nombre'] }}"
                                                wire:model.live="acciones_seleccionadas"
                                                value="{{ $accion['nombre'] }}"
                                                @if ($accion['seleccionado']) checked @endif>
                                            <label class="form-check-label" for="{{ $accion['nombre'] }}">
                                                {{ $accion['descripcion'] }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach --}}
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
    </div>
</div>
