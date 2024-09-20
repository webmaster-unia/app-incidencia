<?php

use Livewire\Volt\Component;
use Livewire\Attributes\{Layout, Title, Url, Validate};
use App\Models\{Rol,Usuario,Permiso};
use Illuminate\Support\Str;
use Livewire\WithPagination;

new 
#[Layout('components.layouts.app')]
#[Title('Roles | SIGEIN OTI')]
class extends Component {
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
    public string $titulo_modal = 'Nuevo Rol';
    public string $nombre_modal = 'modal-Rol';
    public string $alerta = '';
    public string $mensaje = '';
    public string $action = '';
    public array $acciones = [];

    // Variables para el formulario
    public string $modo_modal = 'crear';
    public $id_rol = null;
    public string $action_form = 'crear_rol';

    // Variables para el formulario
    #[Validate('required|string|max:255')]
    public string $nombre = '';
    #[Validate('required|string|max:255')]
    public string $descripcion = '';

    #[Validate('required|array|min:1')]
    public  array $accionesSelecionadas=[];

    // Metodo que se inicia con el componente
    public function mount(): void
    {
        $this->titulo_componente = 'ROLES DE USUARIO';
        $this->breadcrumbs = [
            ['url' => route('inicio.index'), 'title' => 'Inicio'],
            ['url' => '', 'title' => 'Seguridad'],
            ['url' => '', 'title' => 'Roles']
        ];
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

    // Metodo para resetear el modal
    public function reset_modal(): void
    {
        $this->reset(
            'nombre',
            'descripcion',
            'modo_modal',
            'id_rol',
            'action_form',
            'titulo_modal',
            'alerta',
            'mensaje',
            'action'
        );
        $this->resetErrorBag();
        $this->resetValidation();
    }

    // Metodo para cargar los datos del rol
    public function cargar(string $modo, ?int $id): void
    {
        $this->reset_modal();
        $this->modo_modal = $modo;
        $this->id_rol = $id;

        if ($modo === 'crear') {
            // Asignar los valores a las variables
            $this->titulo_modal = 'Nuevo Rol';
            $this->action_form = 'crear_rol';

            // Abrir el modal
            $this->dispatch('modal',
                modal: '#'.$this->nombre_modal,
                action: 'show'
            );
        } elseif ($modo === 'editar') {
            // Buscar el rol
            $data = Rol::query()
            ->findOrFail($id);

            // Asignar los valores a las variables
            $this->titulo_modal = 'Editar Rol';
            $this->action_form = 'editar_rol';
            $this->nombre = $data->nombre_rol;
            $this->descripcion = $data->descripcion_rol;

            // Abrir el modal
            $this->dispatch('modal',
            modal: '#'.$this->nombre_modal,
            action: 'show'
            );

        } elseif ($modo === 'eliminar') {
            // Buscar el rol
            $data = Rol::query()
                ->findOrFail($id);

            // Verificar si es el rol de administrador
            if ($data->nombre_rol === 'Administrador') {
                // Mostrar mensaje de error
                $this->dispatch(
                    'toast',
                    text: 'No se puede eliminar el rol de Administrador.',
                    color: 'danger'
                );
                return;
            }

            $this->titulo_modal = '';
            $this->alerta = '¡Atención!';
            $this->mensaje = '¿Está seguro de eliminar el rol "' . $data->nombre_rol . '"?';
            $this->action = 'eliminar_rol';

            // Abrir el modal
            $this->dispatch('modal',
                modal: '#alerta',
                action: 'show'
            );
        } elseif ($modo === 'status') {
            // Buscar el rol
            $data = Rol::query()
                ->findOrFail($id);

            // Verificar si es el rol de administrador
            if ($data->nombre_rol === 'Administrador') {
                // Mostrar mensaje de error
                $this->dispatch(
                    'toast',
                    text: 'No se puede cambiar el estado del rol de Administrador.',
                    color: 'danger'
                );
                return;
            }

            $this->titulo_modal = '';
            $this->alerta = '¡Atención!';
            $this->mensaje = $data->activo_rol
                ? '¿Está seguro de desactivar el rol "' . $data->nombre_rol . '"?'
                : '¿Está seguro de activar el rol "' . $data->nombre_rol . '"?';
            $this->action = 'cambiar_estado_rol';

            // Abrir el modal
            $this->dispatch('modal',
                modal: '#alerta',
                action: 'show'
            );
        } elseif ($modo === 'asignar') {
            // Buscar el rol
            $data = Rol::query()
                ->findOrFail($id);

            // Asignar los valores a las variables
            $this->titulo_modal = 'Asignar Permisos';
            $this->nombre = $data->nombre_rol;
            $this->descripcion = $data->descripcion_rol;
            $this->acciones = $data->acciones->pluck('id_acc')->toArray();

            // Abrir el modal
            $this->dispatch('modal',
                modal: '#modal-asignar-permiso',
                action: 'show'
            );
        }
    }

    // Metodo para asignar permisos a un rol
    public function asignar(): void
    {
        // Validar que el rol exista
        $rol = Rol::query()->findOrFail($this->id_rol);

        // Asignar los permisos al rol
        $rol->acciones()->sync($this->accionesSelecionadas);

        // Mostrar mensaje de éxito
        $this->dispatch(
            'toast',
            text: 'Los permisos han sido asignados correctamente al rol "' . $rol->nombre_rol . '".',
            color: 'success'
        );

        // Cerrar el modal
        $this->dispatch('modal',
            modal: '#modal-asignar-permiso',
            action: 'hide'
        );

        // Limpiar los campos
        $this->reset_modal();
    }

    // Metodo para editar un rol
    public function editar_rol(): void
    {
        // Validar los campos
        $this->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string|max:255'
        ]);

        // Buscar el rol
        $rol = Rol::query()
            ->findOrFail($this->id_rol);

        // Verificar si es el rol de administrador
        if ($rol->nombre_rol === 'Administrador' && $rol->nombre_rol !== $this->nombre) {
            // Mostrar mensaje de advertencia
            $this->dispatch(
                'toast',
                text: 'No se puede cambiar el nombre del rol "Administrador".',
                color: 'danger'
            );
            return;
        }

        // Actualizar los campos
        $rol->nombre_rol = $this->nombre;
        $rol->descripcion_rol = $this->descripcion;
        $rol->slug_rol = Str::slug($this->nombre);
        $rol->save();

        // Mostrar mensaje de éxito
        $this->dispatch(
            'toast',
            text: 'El rol "' . $rol->nombre_rol . '" ha sido actualizado correctamente.',
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

    // Metodo para crear un nuevo rol
    public function crear_rol(): void
    {
        // Validar los campos
        $this->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string|max:255'
        ]);

        // Creamos el rol
        $rol = new Rol();
        $rol->nombre_rol = $this->nombre;
        $rol->descripcion_rol = $this->descripcion;
        $rol->slug_rol = Str::slug($this->nombre);
        $rol->activo_rol = true;
        $rol->save();

        // Mostrar mensaje de éxito
        $this->dispatch(
            'toast',
            text: 'El rol "' . $this->nombre . '" ha sido creado correctamente.',
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

    // Metodo para cambiar el estado de la complejidad
    public function cambiar_estado_rol(): void
    {
        // Buscar la complejidad
        $rol = Rol::query()
            ->findOrFail($this->id_rol);

        // Cambiar el estado de la complejidad
        $rol->activo_rol= !$rol->activo_rol;
        $rol->save();

        // Mostrar mensaje de éxito
        $this->dispatch(
            'toast',
            text: 'La complejidad "' . $rol->nombre_rol . '" ha sido ' . ($rol->activo_rol ? 'activada' : 'desactivada') . ' correctamente.',
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

    // Metodo para eliminar un rol
    public function eliminar_rol(): void
    {
        // Buscar el rol
        $rol = Rol::query()
            ->with('usuarios')
            ->findOrFail($this->id_rol);

        //
        if ($rol->usuarios()->count() > 0) {
            // Mostrar mensaje de error
            $this->dispatch(
                'toast',
                text: 'No se puede eliminar el rol "' . $rol->nombre_rol . '" porque está asociado a un usuario."',
                color: 'danger'
            );
            return;    
        }

        // Eliminar el rol
        $rol->delete();

        // Mostrar mensaje de éxito
        $this->dispatch(
            'toast',
            text: 'El rol "' . $rol->nombre_rol . '" ha sido eliminado correctamente.',
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
        $roles = Rol::query()
            ->search($this->search)
            ->paginate($this->registros);
        
        $permisos = Permiso::query()
            ->where('activo_per', true)
            ->with('acciones')
            ->get();

        return [
            'roles' => $roles,
            'permisos' => $permisos
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
                        Roles de Usuario
                    </h5>
                    <small>
                        Listado de roles de usuario registrados en el sistema.
                    </small>
                    <div class="card-header-right mt-3 me-3">
                        <button
                            class="btn btn-primary"
                            wire:click="cargar('crear', null)"
                        >
                            Nuevo Rol
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
                                            <th>DESCRIPCIÓN</th>
                                            <th class="text-center">ESTADO</th>
                                            <th class="text-center">ACCIONES</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($roles as $item)
                                            <tr wire:key="{{ $item->id_rol }}">
                                                <td>
                                                    {{ $item->id_rol }}
                                                </td>
                                                <td>
                                                    {{ $item->nombre_rol }}
                                                </td>
                                                <td>
                                                    {{ $item->descripcion_rol }}
                                                    
                                                </td>
                                                <td class="text-center">
                                                    @if ($item->activo_rol)
                                                        <span
                                                            class="badge bg-light-success rounded f-12"
                                                            wire:click="cargar('status', {{ $item->id_rol }})"
                                                            style="cursor: pointer;"
                                                        >
                                                            <i class="ti ti-circle-check me-1"></i>
                                                            Activo
                                                        </span>
                                                    @else
                                                        <span
                                                            class="badge bg-light-danger rounded f-12"
                                                            wire:click="cargar('status', {{ $item->id_rol }})"
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
                                                            aria-label="Asignar Permisos"
                                                            data-bs-original-title="Asignar Permisos"
                                                            wire:click="cargar('asignar', {{ $item->id_rol }})"
                                                        >
                                                            <a
                                                                href="#"
                                                                class="avtar avtar-xs btn-link-primary btn-pc-default"
                                                            >
                                                                <i class="ti ti-key f-18"></i>
                                                            </a>
                                                        </li>

                                                        <li
                                                            class="list-inline-item align-bottom"
                                                            data-bs-toggle="tooltip"
                                                            aria-label="Editar"
                                                            data-bs-original-title="Editar"
                                                            wire:click="cargar('editar', {{ $item->id_rol }})"
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
                                                            wire:click="cargar('eliminar', {{ $item->id_rol }})"
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
                            @if ($roles->hasPages())
                                <div class="d-flex justify-content-between">
                                    <div class="d-flex align-items-center text-secondary">
                                        Mostrando {{ $roles->firstItem() }} -
                                        {{ $roles->lastItem() }}
                                        de {{ $roles->total() }} registros
                                    </div>
                                    <div class="">
                                        {{ $roles->links() }}
                                    </div>
                                </div>
                            @else
                                <div class="d-flex justify-content-between">
                                    <div class="d-flex align-items-center text-secondary">
                                        Mostrando {{ $roles->firstItem() }} -
                                        {{ $roles->lastItem() }}
                                        de {{ $roles->total() }} registros
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Crear -->
    <div wire:ignore.self id="{{ $nombre_modal }}" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
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
                        <!--nombre-->
                        <div class="col-md-12">
                            <label class="form-label" for="nombre">
                                Nombre del rol <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                class="form-control @if ($errors->has('nombre')) is-invalid @elseif($nombre) is-valid @endif"
                                wire:model.live="nombre" id="nombre" placeholder="Ingrese el nombre del rol">
                            <small class="form-text text-muted">
                                Ingrese el nombre del rol.
                            </small>
                            @error('nombre')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <!--descripcion-->
                        <div class="col-md-12">
                            <label class="form-label" for="descripcion">
                                Descripcion del rol <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                class="form-control @if ($errors->has('descripcion')) is-invalid @elseif($descripcion) is-valid @endif"
                                wire:model.live="descripcion" id="descripcion" placeholder="Ingrese el descripcion del rol">
                            <small class="form-text text-muted">
                                Ingrese la descripcion del rol.
                            </small>
                            @error('descripcion')
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
    <!-- Modal Asignar Permiso -->
    <div wire:ignore.self id="modal-asignar-permiso" class="modal fade" tabindex="-1" role="dialog"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <form class="modal-content" wire:submit="asignar">
                <div class="modal-header animate_animated animatefadeIn animate_faster">
                    <h5 class="modal-title">
                        {{ $titulo_modal }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        wire:click="reset_modal"></button>
                </div>
                <div class="modal-body animate_animated animatefadeIn animate_faster">
                    <ul class="list-group">
                        <li class="list-group-item">
                            Rol: <strong>{{ $nombre }}</strong>
                        </li>
                        <li class="list-group-item">
                            Descripción: <strong>{{ $descripcion }}</strong>
                        </li>
                    </ul>
                    <div class="mt-3">
                        <span class="fs-5 text-muted">
                            <strong>Asignar permisos</strong>
                        </span>
                        <ul class="list-group list-group-flush">
                            @foreach ($permisos as $permiso)
                                <li class="list-group-item">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <span>
                                                <strong>{{ $permiso->nombre_per }}</strong>
                                            </span>
                                        </div>
                                        <div class="col-md-8">
                                            @foreach ($permiso->acciones as $item)
                                                <div class="form-check form-check-inline"
                                                    wire:key="{{ $item->id_acc }}">
                                                    <input class="form-check-input input-info" type="checkbox"
                                                        id="{{ $item->id_acc }}" wire:model.live="accionesSelecionadas"
                                                        value="{{ $item->id_acc }}">
                                                    <label class="form-check-label" for="{{ $item->id_acc }}">
                                                        {{ $item->nombre_acc }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="modal-footer animate_animated animatefadeIn animate_faster">
                    <button type="button" class="btn btn-light-danger" data-bs-dismiss="modal"
                        wire:click="reset_modal">
                        Cerrar
                    </button>
                    <button type="submit" class="btn btn-primary" style="width: 100px;"
                        wire:loading.attr="disabled" wire:target="asignar">
                        <span wire:loading.remove wire:target="asignar">
                            Asignar
                        </span>
                        <div class="spinner-border spinner-border-sm" role="status" wire:loading
                            wire:target="asignar">
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
                                        <button type="button" class="btn btn-primary w-100" wire:click="{{ $action }}">
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
