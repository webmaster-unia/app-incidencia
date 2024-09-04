<?php

use Livewire\Volt\Component;
use Livewire\Attributes\{Layout, Title, Url, Validate};
use App\Models\{Permiso, accion, RolPermiso};
use Livewire\WithPagination;

new
#[Layout('components.layouts.app')]
#[Title('Permisos | SIGEIN OTI')]
class extends Component {
   // Define la variables para el Page Header
   public string $titulo_componente = 'Permisos';
    public array $breadcrumbs = [];
    // Define la variable para la cantidad de registros por página
    #[Url(as: 'registros', except: 5)]
    public int $registros = 5;
    // Define la variable para el buscador
    #[Url(as: 'buscador', except: '')]
    public string $search = '';
    // Variables del modal
    public string $titulo_modal = 'Nueva Permiso';
    public string $nombre_modal = 'modal-permiso';
    public string $alerta = '';
    public string $mensaje = '';
    public string $action = '';
    public array $acciones = [];

    // Variables para el formulario
    public string $modo_modal = 'crear';
    public $id_permiso = null;
    public string $action_form = 'crear_permiso';

    // Variables para el formulario
    #[Validate('required|string|max:255')]
    public string $nombre = '';
     // Metodo que se inicia con el componente
     public function mount(): void
    {
        $this->titulo_componente = 'Permisos';
        $this->breadcrumbs = [
            ['url' => route('inicio.index'), 'title' => 'Inicio'],
            ['url' => '', 'title' => 'Seguridad'],
            ['url' => '', 'title' => 'Permisos']
        ];
    }
    public function reset_modal(): void
    {
        $this->reset(
            'nombre',
            'modo_modal',
            'id_permiso',
            'action_form',
            'titulo_modal',
            'alerta',
            'mensaje',
            'action'
        );
        $this->resetErrorBag();
        $this->resetValidation();
    }
    // Metodo que carga el modal
    public function cargar(string $modo, ?int $id): void
    {
        $this->reset_modal();
        $this->modo_modal = $modo;
        $this->id_permiso = $id;
        if ($modo == 'crear') {
            // Asignar los valores a las variables
            $this->titulo_modal = 'Nuevo Registro';
            $this->action_form = 'crear_permiso';
            // Abrir el modal
        $this->dispatch('modal',
                modal: '#'.$this->nombre_modal,
                action: 'show'
            );
        } elseif ($modo == 'editar') {
            // Buscar permiso
            $data = Permiso::query()
                ->findOrFail($id);

            // Asignar los valores a las variables
            $this->titulo_modal = 'Editar Registro';
            $this->action_form = 'editar_permiso';
            $this->nombre = $data->nombre_per;
            $this->slug = $data->slug_per;

            // Abrir el modal
            $this->dispatch('modal',
                modal: '#'.$this->nombre_modal,
                action: 'show'
            );
        } elseif ($modo == 'eliminar') {
           // Buscar permiso
            $data = Permiso::query()
                ->findOrFail($id);

            $this->titulo_modal = '';
            $this->alerta = '¡Atención!';
            $this->mensaje = '¿Está seguro de eliminar el permiso "' . $data->nombre_per. '"?';
            $this->action = 'eliminar_permiso';

            // Abrir el modal
            $this->dispatch('modal',
                modal: '#alerta',
                action: 'show'
            );
        } elseif ($modo == 'status') {
            // Buscar la permiso
            $data = Permiso::query()
                ->findOrFail($id);

            $this->titulo_modal = '';
            $this->alerta = '¡Atención!';
            $this->mensaje = $data->activo_per
                ? '¿Está seguro de desactivar el permiso "' . $data->nombre_per. '"?'
                : '¿Está seguro de activar el permiso "' . $data->nombre_per. '"?';
            $this->action = 'cambiar_estado_permiso';

            // Abrir el modal
            $this->dispatch('modal',
                modal: '#alerta',
                action: 'show'
            );
        }
        

        
    }
    // Metodo para agregar un nuevo registro de permiso
public function agregar_permiso(): void
{
    // Validar el campo permiso
    $this->validate([
        'permiso' => 'required|string|max:255'
    ]);

    // Crear el permiso
    $permiso = new Permiso();
    $permiso->nombre_per = $this->permiso;
    $permiso->activo_per = true;
    $permiso->save();

    // Actualizar la lista de permisos
    $this->permisos = Permiso::query()
        ->where('activo_per', true)
        ->get();

    // Limpiar el campo permiso
    $this->reset('permiso');
}

// Metodo para eliminar un registro de permiso
public function eliminar_permiso(): void
{
    // Buscar el permiso
    $permiso = Permiso::query()
        ->findOrFail($this->id_permiso);

    $acciones_count = $permiso->acciones()->count(); 
    if ($acciones_count > 0) {
        // Mostrar mensaje de error
        $this->dispatch(
            'toast',
            text: 'No se puede eliminar el permiso "' . $permiso->nombre_per . '" porque está asociado a una acción.',
            color: 'danger'
        );
        return;
    } else {
        // Eliminar el permiso
        $permiso->delete();

        // Mostrar mensaje de éxito
        $this->dispatch(
            'toast',
            text: 'El permiso "' . $permiso->nombre_per . '" ha sido eliminado correctamente.',
            color: 'success'
        );
    // Abrir el modal
    $this->dispatch('modal',
                modal: '#alerta',
                action: 'hide'
            );
    }
}

// Metodo para crear un nuevo permiso
public function crear_permiso(): void
{
    // Validar los campos
    $this->validate([
        'nombre' => 'required|string|max:255'
    ]);

    // Creamos el permiso
    $permiso = new Permiso();
    $permiso->nombre_per = $this->nombre;
    $permiso->activo_per = true;
    $permiso->save();

    // Mostrar mensaje de éxito
    $this->dispatch(
        'toast',
        text: 'El permiso "' . $this->nombre. '" ha sido creado correctamente.',
        color: 'success'
    );

    // Cerrar el modal
    $this->dispatch('modal',
        modal: '#'.$this->nombre_modal,
        action: 'hide'
    );

    // Limpiar los campos
    $this->reset_modal();
}

// Metodo para editar un permiso
public function editar_permiso(): void
{
    // Validar los campos
    $this->validate([
        'nombre' => 'required|string|max:255'
    ]);

    // Editamos el permiso
    $permiso = Permiso::query()
        ->findOrFail($this->id_permiso);
    $permiso->nombre_per = $this->nombre;
    $permiso->save();

    // Mostrar mensaje de éxito
    $this->dispatch(
        'toast',
        text: 'El permiso "' . $this->nombre. '" ha sido actualizado correctamente.',
        color: 'success'
    );

    // Cerrar el modal
    $this->dispatch('modal',
        modal: '#'.$this->nombre_modal,
        action: 'hide'
    );

    // Limpiar los campos
    $this->reset_modal();
}
// Metodo para cambiar el estado de permiso
public function cambiar_estado_permiso(): void
    {
        // Buscar permiso
        $permiso = Permiso::query()
            ->findOrFail($this->id_permiso);

        // Cambiar el estado de permiso
        $permiso->activo_per = !$permiso->activo_per;
        $permiso->save();

        // Mostrar mensaje de éxito
        $this->dispatch(
            'toast',
            text: 'La oficina "' . $permiso->nombre_per . '" ha sido ' . ($permiso->activo_per ? 'activado' : 'desactivado') . ' correctamente.',
            color: 'success'
        );

        // Cerrar el modal
        $this->dispatch('modal',
            modal: '#alerta',
            action: 'hide'
        );

        // Limpiar los campos
        $this->reset_modal();
    }
    // Metodo que renderiza la vista
    public function with(): array
    {
        $permisos = Permiso::query()
            ->where('nombre_per', 'like', "%{$this->search}%")
            ->orderBy('id_per', 'asc')
            ->paginate($this->registros);
        return [
            'permisos' => $permisos
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
                        Listado de Permisos
                    </h5>
                    <small>
                        Listado de permisos registrados en el sistema.
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
                            <!-- Tabla Permiso-->
                            <div class="datatable-container">
                                <table class="table table-hover datatable-table mb-0" id="pc-dt-simple">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th class="col-md-3">NOMBRE</th>
                                            <th class="text-center">ESTADO</th>
                                            <th class="text-center">ACCIONES</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($permisos as $item)
                                        <tr wire:key="{{ $item->id_per }}">
                                            <td>
                                                {{ $item->id_per }}
                                            </td>
                                            <td>
                                                {{ $item->nombre_per }}
                                            </td>
                                            <td class="text-center">
                                                @if ($item->activo_per)
                                                <span class="badge bg-light-success rounded f-12"
                                                    wire:click="cargar('status', {{ $item->id_per }})"
                                                    style="cursor: pointer;">
                                                    <i class="ti ti-circle-check me-1"></i>
                                                    Activo
                                                </span>
                                                @else
                                                <span class="badge bg-light-danger rounded f-12"
                                                    wire:click="cargar('status', {{ $item->id_per }})"
                                                    style="cursor: pointer;">
                                                    <i class="ti ti-circle-x me-1"></i>
                                                    Inactivo
                                                </span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <ul class="list-inline me-auto mb-0">
                                                    <li class="list-inline-item align-bottom" data-bs-toggle="tooltip"
                                                        aria-label="Editar" data-bs-original-title="Editar"
                                                        wire:click="cargar('editar', {{ $item->id_per }})">
                                                        <a href="#"
                                                            class="avtar avtar-xs btn-link-secondary btn-pc-default">
                                                            <i class="ti ti-edit-circle f-18"></i>
                                                        </a>
                                                    </li>
                                                    <li class="list-inline-item align-bottom" data-bs-toggle="tooltip"
                                                        aria-label="Eliminar" data-bs-original-title="Eliminar"
                                                        wire:click="cargar('eliminar', {{ $item->id_per }})">
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
                                            <td colspan="3" class="text-center text-muted py-5">
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
                            @if ($permisos->hasPages())
                            <div class="d-flex justify-content-between">
                                <div class="d-flex align-items-center text-secondary">
                                    Mostrando {{ $permisos->firstItem() }} -
                                    {{ $permisos->lastItem() }}
                                    de {{ $permisos->total() }} registros
                                </div>
                                <div class="">
                                    {{ $permisos->links() }}
                                </div>
                            </div>
                            @else
                            <div class="d-flex justify-content-between">
                                <div class="d-flex align-items-center text-secondary">
                                    Mostrando {{ $permisos->firstItem() }} -
                                    {{ $permisos->lastItem() }}
                                    de {{ $permisos->total() }} registros
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
                                Agregar nombre del Permiso <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                class="form-control @if ($errors->has('nombre')) is-invalid @elseif($nombre) is-valid @endif"
                                wire:model.live="nombre" id="nombre" placeholder="Ingrese el nombre del Permiso">
                            <small class="form-text text-muted">
                                Ingrese el nombre del Permiso.
                            </small>
                            @error('nombre')
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
    <!-- Alerta -->
    <div wire:ignore.self id="alerta" class="modal fade" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
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