<?php

use Livewire\Volt\Component;
use Livewire\Attributes\{Layout, Title, Url, Validate};
use App\Models\{ActivoInformatico, TipoActivo, TrabajadorActivo};
use Illuminate\Support\Str;
use Livewire\WithPagination;

new #[Layout('components.layouts.app')] #[Title('Activos Informaticos | SIGEIN OTI')] class extends Component {
    // Sirve para usar la paginación
    use WithPagination;

    // Define la variables para el Page Header
    public string $titulo_componente = 'Roles de Usuario';
    public array $breadcrumbs = [];

    // Define la variable para la cantidad de registros por página
    #[Url(as: 'registros', except: 5)]
    public int $registros = 5;

    // Define la variable para el buscador
    #[Url(as: 'buscador', except: '')]
    public string $search = '';

    // Variables del modal
    public string $titulo_modal = 'Nuevo Activo';
    public string $nombre_modal = 'modal-Activo';
    public string $alerta = '';
    public string $mensaje = '';
    public string $action = '';
    public array $acciones = [];

    // Variables para el formulario
    public string $modo_modal = 'crear';
    public $id_activo = null;
    #[Validate('required')]
    public $nombre = null;
    #[Validate('required')]
    public $tipo = null;
    public string $action_form = 'crear_activo';

    // Metodo que se inicia con el componente
    public function mount(): void
    {
        $this->titulo_componente = 'Activo Informático';
        $this->breadcrumbs = [['url' => route('inicio.index'), 'title' => 'Inicio'], ['url' => '', 'title' => 'Configuración'], ['url' => '', 'title' => 'Activo Informático']];
    }

    // Metodo para resetear el modal
    public function reset_modal(): void
    {
        $this->reset('nombre', 'tipo', 'modo_modal', 'id_activo', 'action_form', 'titulo_modal', 'alerta', 'mensaje', 'action');
        $this->resetErrorBag();
        $this->resetValidation();
    }

    // Metodo para cargar el modal
    public function cargar(string $modo, ?int $id): void
    {
        $this->reset_modal();
        $this->modo_modal = $modo;
        $this->id_activo = $id;

        // Crear activo
        if ($modo === 'crear') {
            // Asignar los valores a las variables
            $this->titulo_modal = 'Nuevo Activo';
            $this->action_form = 'crear_activo';

            // Abrir el modal
            $this->dispatch('modal', modal: '#' . $this->nombre_modal, action: 'show');
            // Editar activo
        } elseif ($modo === 'editar') {
            // Buscar el activo informático
            $data = ActivoInformatico::query()->findOrFail($id);

            // Asignar los valores a las variables
            $this->titulo_modal = 'Editar Activo';
            $this->action_form = 'editar_activo';
            $this->nombre = $data->nombre_ain;
            $this->tipo = $data->id_tac;

            // Abrir el modal
            $this->dispatch('modal', modal: '#' . $this->nombre_modal, action: 'show');
        } elseif ($modo === 'eliminar') {
            // Buscar el activo informático
            $data = ActivoInformatico::query()->findOrFail($id);

            // Verificar si el activo está asociado a un trabajador
            $asociacion = TrabajadorActivo::where('id_ain', $id)->exists();

            if ($asociacion) {
                // Mostrar mensaje de error
                $this->dispatch('toast', text: 'No se puede eliminar el activo porque está asociado a un trabajador.', color: 'danger');
            } else {
                // Asignar los valores a las variables
                $this->titulo_modal = '';
                $this->alerta = '¡Atención!';
                $this->mensaje = '¿Está seguro de eliminar el activo "' . $data->nombre_ain . '"?';
                $this->action = 'eliminar_activo';

                // Abrir el modal
                $this->dispatch('modal', modal: '#alerta', action: 'show');
            }
        } elseif ($modo === 'status') {
            // Buscar el activo informático
            $data = ActivoInformatico::query()->findOrFail($id);

            $this->titulo_modal = '';
            $this->alerta = '¡Atención!';
            $this->mensaje = '¿Está seguro de cambiar el estado del activo "' . $data->nombre_ain . '"?';
            $this->action = 'cambiar_estado_activo';

            // Abrir el modal
            $this->dispatch('modal', modal: '#alerta', action: 'show');
        }
    }

    // Metodo para actualizar la busqueda
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    // Metodo para actualizar la cantidad de registros
    public function updatedRegistros(): void
    {
        $this->resetPage();
    }

    // Metodo para crear un nuevo activo
    public function crear_activo(): void
    {
        // Validar los datos
        $this->validate([
            'nombre' => 'required',
            'tipo' => 'required',
        ]);

        // Crear el activo
        ActivoInformatico::create([
            'nombre_ain' => $this->nombre,
            'id_tac' => $this->tipo,
        ]);

        // Mostrar mensaje de éxito
        $this->dispatch('toast', text: 'El activo informático se ha creado correctamente.', color: 'success');

        // Cerrar el modal
        $this->dispatch('modal', modal: '#' . $this->nombre_modal, action: 'hide');

        // Resetear el modal
        $this->reset_modal();
    }

    // Metodo para editar un activo
    public function editar_activo(): void
    {
        // Validar los datos
        $this->validate([
            'nombre' => 'required',
            'tipo' => 'required',
        ]);

        // Buscar el activo
        $activo = ActivoInformatico::query()->findOrFail($this->id_activo);

        // Actualizar el activo
        $activo->update([
            'nombre_ain' => $this->nombre,
            'id_tac' => $this->tipo,
        ]);

        // Mostrar mensaje de éxito
        $this->dispatch('toast', text: 'El activo informático se ha actualizado correctamente.', color: 'success');

        // Cerrar el modal
        $this->dispatch('modal', modal: '#' . $this->nombre_modal, action: 'hide');

        // Resetear el modal
        $this->reset_modal();
    }

    // Metodo para eliminar un activo
    public function eliminar_activo(): void
    {
        // Buscar el activo
        $activo = ActivoInformatico::query()->findOrFail($this->id_activo);

        // Eliminar el activo
        $activo->delete();

        // Mostrar mensaje de éxito
        $this->dispatch('toast', text: 'El activo informático se ha eliminado correctamente.', color: 'success');

        // Cerrar el modal
        $this->dispatch('modal', modal: '#alerta', action: 'hide');

        // Resetear el modal
        $this->reset_modal();
    }

    // Metodo para modificar el estado del activo
    public function cambiar_estado_activo(): void
    {
        // Buscar el activo
        $activo = ActivoInformatico::query()->findOrFail($this->id_activo);

        // Cambiar el estado del activo
        $activo->update([
            'activo_ain' => !$activo->activo_ain,
        ]);

        // Mostrar mensaje de éxito
        $this->dispatch('toast', text: 'El activo informático se ha actualizado correctamente.', color: 'success');

        // Cerrar el modal
        $this->dispatch('modal', modal: '#alerta', action: 'hide');

        // Resetear el modal
        $this->reset_modal();
    }

    // Metodo que renderiza la vista
    public function with(): array
    {
        $activos = ActivoInformatico::query()
            ->with('tipo_activo')
            ->search($this->search)
            ->paginate($this->registros);
        $nombre = TipoActivo::query()->orderBy('nombre_tac')->get();
        return [
            'activos' => $activos,
            'tipos' => $nombre,
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
                        Listado de Activos Informáticos
                    </h5>
                    <small>
                        Listado de activos informáticos registrados en el sistema.
                    </small>
                    <div class="card-header-right mt-3 me-3">
                        <button class="btn btn-primary" wire:click="cargar('crear', null)">
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
                                    <tbody>
                                        @forelse ($activos as $activo)
                                            <tr wire:key="{{ $activo->id_ain }}">
                                                <td>
                                                    {{ $activo->id_ain }}
                                                </td>
                                                <td>
                                                    {{ $activo->nombre_ain }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $activo->tipo_activo->nombre_tac }}
                                                </td>

                                                <td class="text-center">
                                                    @if ($activo->activo_ain)
                                                        <span class="badge bg-light-success rounded f-12"
                                                            wire:click="cargar('status', {{ $activo->id_ain }})"
                                                            style="cursor: pointer;">
                                                            <i class="ti ti-circle-check me-1"></i>
                                                            Activo
                                                        </span>
                                                    @else
                                                        <span class="badge bg-light-danger rounded f-12"
                                                            wire:click="cargar('status', {{ $activo->id_ain }})"
                                                            style="cursor: pointer;">
                                                            <i class="ti ti-circle-x me-1"></i>
                                                            Inactivo
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <ul class="list-inline me-auto mb-0">
                                                        <li class="list-inline-item align-bottom"
                                                            data-bs-toggle="tooltip" aria-label="Editar"
                                                            data-bs-original-title="Editar"
                                                            wire:click="cargar('editar', {{ $activo->id_ain }})">
                                                            <a href="#"
                                                                class="avtar avtar-xs btn-link-secondary btn-pc-default">
                                                                <i class="ti ti-edit-circle f-18"></i>
                                                            </a>
                                                        </li>
                                                        <li class="list-inline-item align-bottom"
                                                            data-bs-toggle="tooltip" aria-label="Eliminar"
                                                            data-bs-original-title="Eliminar"
                                                            wire:click="cargar('eliminar', {{ $activo->id_ain }})">
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
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer pb-4">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            @if ($activos->hasPages())
                                <div class="d-flex justify-content-between">
                                    <div class="d-flex align-items-center text-secondary">
                                        Mostrando {{ $activos->firstItem() }} -
                                        {{ $activos->lastItem() }}
                                        de {{ $activos->total() }} registros
                                    </div>
                                    <div class="">
                                        {{ $activos->links() }}
                                    </div>
                                </div>
                            @else
                                <div class="d-flex justify-content-between">
                                    <div class="d-flex align-items-center text-secondary">
                                        Mostrando {{ $activos->firstItem() }} -
                                        {{ $activos->lastItem() }}
                                        de {{ $activos->total() }} registros
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal  de Activos-->
    <div wire:ignore.self id="{{ $nombre_modal }}" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form class="modal-content" wire:submit.prevent="{{ $action_form }}">
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
                                Nombre del Activo <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                class="form-control @if ($errors->has('nombre')) is-invalid @elseif($nombre) is-valid @endif"
                                wire:model.live="nombre" id="nombre" placeholder="Ingrese el nombre del activo">
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
                            <label class="form-label" for="tipo">
                                Tipo de Activo <span class="text-danger">*</span>
                            </label>
                            <select
                                class="form-control @if ($errors->has('tipo')) is-invalid @elseif($tipo) is-valid @endif"
                                wire:model.live="tipo" id="tipo">
                                <option value="">Seleccione un tipo de activo</option>
                                @foreach ($tipos as $tipo)
                                    <option value="{{ $tipo->id_tac }}">{{ $tipo->nombre_tac }}</option>
                                @endforeach
                            </select>
                            @error('tipo')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer animate__animated animate__fadeIn animate__faster">
                    <button type="button" class="btn btn-light-danger" data-bs-dismiss="modal"
                        wire:click="reset_modal">
                        Cerrar
                    </button>
                    <button type="submit" class="btn btn-primary" style="width: 100px;"
                        wire:loading.attr="disabled" wire:target="{{ $action_form }}">
                        <span wire:loading.remove wire:target="{{ $action_form }}">
                            Guardar
                        </span>
                        <div class="spinner-border spinner-border-sm" role="status" wire:loading
                            wire:target="{{ $action_form }}">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- Alerta -->
    <div wire:ignore.self id="alerta" class="modal fade" data-bs-backdrop="static" tabindex="-1" role="dialog"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body py-5 px-5">
                    <div class="row">
                        @if ($alerta != '' && $mensaje != '' && $action != '')
                            <div class="col-md-12 animate__animated animate__fadeIn animate__faster">
                                <div class="d-flex flex-column text-center">
                                    <h4 class="text-center">
                                        {{ $alerta }}
                                    </h4>
                                    <h5 class="text-center fw-medium">
                                        {{ $mensaje }}
                                    </h5>
                                    <div class="row g-3 mt-2">
                                        <div class="col-6">
                                            <button type="button" class="btn btn-light-danger w-100"
                                                wire:click="reset_modal" data-bs-dismiss="modal">
                                                Cancelar
                                            </button>
                                        </div>
                                        <div class="col-6">
                                            <button type="button" class="btn btn-primary w-100"
                                                wire:click="{{ $action }}">
                                                Aceptar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="col-md-12">
                                <div class="d-flex justify-content-center py-3">
                                    <div class="spinner-border text-secondary" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
