<?php

use Livewire\Volt\Component;
use Livewire\Attributes\{Layout, Title, Url, Validate};
use App\Models\{Usuario,Rol,Trabajador};
use Livewire\WithPagination;
use Livewire\WithFileUploads;

new 
#[Layout('components.layouts.app')]
#[Title('Usuarios | SIGEIN OTI')]
class extends Component {
    // Sirve para usar la paginación
    use WithPagination;
    // sirve para soportar la carga de archivos
    use WithFileUploads;

    // Define la variables para el Page Header
    public string $titulo_componente = 'Usuarios';
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
    public $id_usuario = null;
    public $foto_usu = null;
    public $correo_usu= null;
    public $contrasena_usu= null;
    public $rol= null;
    public $trabajador= null;
    public string $action_form = 'crear_usuario';

    //Metodo que se inicia con el componente
    public function mount(): void
    {
        $this->titulo_componente = 'Usuarios';
        $this->breadcrumbs = [
            ['url' => route('inicio.index'), 'title' => 'Inicio'],
            ['url' => '', 'title' => 'Seguridad'],
            ['url' => '', 'title' => 'Usuarios']
        ];
    }

    public function reset_modal(): void
    {
        $this->reset(
            'modo_modal',
            'id_usuario',
            'action_form',
            'titulo_modal',
            'alerta',
            'mensaje',
            'action',
            'rol',
            'trabajador',
            'correo_usu',
            'contrasena_usu',
            'foto_usu',
        );
        $this->resetErrorBag();
        $this->resetValidation();
    }

    // Metodo que carga el modal
    public function cargar(string $modo, ?int $id): void
    {
        $this->reset_modal();
        $this->modo_modal = $modo;
        $this->id_usuario = $id;
        if ($modo == 'crear') {
            // Asignar los valores a las variables
            $this->titulo_modal = 'Nuevo Registro';
            $this->action_form = 'crear_usuario';
            // Abrir el modal
            $this->dispatch('modal', modal: '#'.$this->nombre_modal, action: 'show');
        } elseif ($modo == 'editar') {
            // Buscar usuario
            $data = Usuario::query()
                ->findOrFail($id);
           
            // Asignar los valores a las variables
            $this->titulo_modal = 'Editar Registro';
            $this->action_form = 'editar_usuario';
            $this->correo_usu = $data->correo_usu;
            $this->nombre_usu = $data->nombre_usu;
            $this->rol = $data->id_rol;
            $this->trabajador = $data->id_tra;
            $this->estado_usu = $data->estado_usu;
            // Abrir el modal
            $this->dispatch('modal', modal: '#'.$this->nombre_modal, action: 'show');
        } elseif ($modo == 'eliminar') {
            // Buscar usuario
            $data = Usuario::query()
                ->findOrFail($id);

            $this->titulo_modal = '';
            $this->alerta = '¡Atención!';
            $this->mensaje = '¿Está seguro de eliminar el usuario "' . $data->correo_usu. '"?';
            $this->action = 'eliminar_usuario';

            // Abrir el modal
            $this->dispatch('modal', modal: '#alerta', action: 'show');
        } elseif ($modo == 'status') {
            // Buscar el usuario
            $data = Usuario::query()
                ->findOrFail($id);

            $this->titulo_modal = '';
            $this->alerta = '¡Atención!';
            $this->mensaje = $data->estado_usu
                ? '¿Está seguro de desactivar el usuario "' . $data->correo_usu. '"?'
                : '¿Está seguro de activar el usuario "' . $data->correo_usu. '"?';
            $this->action = 'cambiar_estado_usuario';

            // Abrir el modal
            $this->dispatch('modal', modal: '#alerta', action: 'show');
        }
    }

    public function crear_usuario(): void
    {
        // Validar los campos
        $this->validate([
            'correo_usu' => 'required|email|unique:tbl_usuario,correo_usu',
            'contrasena_usu' => 'required|min:6',
            'rol' => 'required|exists:tbl_rol,id_rol',
            'trabajador' => 'required|exists:tbl_trabajador,id_tra',
            'foto_usu' => 'nullable|image|max:1024' // Opcional, pero debe ser una imagen
        ]);

        // Crear el usuario
        $usuario = new Usuario();
        $usuario->correo_usu = $this->correo_usu;
        $usuario->contrasena_usu = Hash::make($this->contrasena_usu);
        $usuario->id_rol = $this->rol;
        $usuario->id_tra = $this->trabajador;
        $usuario->foto_usu = $this->foto_usu ? $this->foto_usu->storeAs('usuarios', $this->foto_usu->getClientOriginalName(), 'public'): null;
        $usuario->save();

        // Resetear el modal
        $this->reset_modal();

        // Cerrar el modal
        $this->dispatch('modal', modal: '#'.$this->nombre_modal, action: 'hide');
    }

    //Metodo de Eliminar Usuario
    public function eliminar_usuario(): void
    {
        // Buscar el usuario
        $usuario = Usuario::query()
        ->findOrFail($this->id_usuario);

        if ($usuario) {
            $usuario->delete();

            // Mostrar mensaje de éxito
            $this->dispatch(
            'toast',
            text: 'El usuario "' . $usuario->correo_usu . '" ha sido eliminado.',
            color: 'success'
            );
        } else {
            // Mostrar mensaje de error
            $this->dispatch(
            'toast',
            text: 'El usuario no fue encontrado.',
            color: 'danger'
            );
        }

        // Resetear el modal
        $this->dispatch('modal',
        modal: '#alerta',
        action: 'hide');

    }

    //Metodo para editar Usuario
    public function editar_usuario(): void
    {
        // Validar los campos
        $this->validate([
            'correo_usu' => 'required|email|unique:tbl_usuario,correo_usu,'.$this->id_usuario.',id_usu',
            'contrasena_usu' => 'nullable|max:50|min:6',
            'rol' => 'required|exists:tbl_rol,id_rol',
            'trabajador' => 'required|exists:tbl_trabajador,id_tra',
            'foto_usu' => 'nullable|image|max:1024' // Opcional, pero debe ser una imagen
        ]);

        //editamos el usuario
        $usuario = Usuario::query()
            ->findOrFail($this->id_usuario);
        $usuario->correo_usu = $this->correo_usu;
        $usuario->contrasena_usu = Hash::make($this->contrasena_usu);
        $usuario->id_rol = $this->rol;
        $usuario->id_tra = $this->trabajador;
        $usuario->foto_usu = $this->foto_usu ? $this->foto_usu->store('usuarios', 'public') : $usuario->foto_usu;
        $usuario->save();
        //Mostrar el mensaje de éxito
        $this->dispatch(
        'toast',
        text: 'El usuario "'.$usuario->correo_usu. '" ha sido actualizado.',
        color:'success'
        );

        // Cerrar el modal
        $this->dispatch('modal',
        modal: '#'.$this->nombre_modal,
        action: 'hide'
        );

        // Limpiar los campos
        $this->reset_modal();


    }

    //Método para cambiar el estado del usuario
    public function cambiar_estado_usuario():  void
    {
        //Buscamos el usuario
        $usuario = Usuario::query()
        ->findOrFail($this->id_usuario);

        //Cambiar el estado de Usuario
        $usuario->activo_usu = !$usuario->activo_usu;
        $usuario->save();

        //Mostrar el mensaje
        $this->dispatch(
        'toast',
        text: 'El usuario "'.$usuario->correo_usu.'" ha sido ' . ($usuario->activo_usu ? 'activado' : 'desactivado') . ' correctamente.',
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

    //Método para cargar la vista correspondiente
    public function with(): array
    {
        $usuarios = Usuario::query()
            ->where('correo_usu', 'like', "%{$this->search}%")
            ->orderBy('id_usu', 'asc')
            ->paginate($this->registros);

        $roles = Rol::query()
            ->orderBy('id_rol', 'asc')
            ->where('activo_rol', true)
            ->get();

        $trabajadores = Trabajador::query()
            ->orderBy('id_tra', 'asc')
            ->where('activo_tra', true)
            ->get();

        // dd($this->all());

        return [
            'usuarios' => $usuarios,
            'roles' => $roles,
            'trabajadores' => $trabajadores
        ];
    }
}; ?>

<div>
    <x-page.header :breadcrumbs="$breadcrumbs" :titulo="$titulo_componente" />
    <!--Tabla de Usuarios-->
    <div class="row">
        <div class="col-sm-12">
            <div class="card table-card">
                <div class="card-header">
                    <h5>
                        Listado de Usuarios
                    </h5>
                    <small>
                        Listado de usuarios registrados en el sistema.
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
                            <!-- Tabla Usuarios-->
                            <div class="datatable-container">
                                <table class="table table-hover datatable-table mb-0" id="pc-dt-simple">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th class="col-md-3">CORREO</th>
                                            <th class="text-center">ESTADO</th>
                                            <th class="text-center">CREADO EN</th>
                                            <th class="text-center">ACCIONES</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($usuarios as $usuario)
                                        <tr wire:key="{{ $usuario->id_usu }}">
                                            <td>
                                                {{ $usuario->id_usu }}
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center justify-content-start gap-3">
                                                    <img src="{{ e($usuario->foto_usu) }}" alt="Foto"
                                                        class="user-avtar wid-40 rounded-circle" width="50">
                                                    <div class="text-center">
                                                        {{ $usuario->correo_usu }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                @if ($usuario->activo_usu)
                                                <span class="badge bg-light-success rounded f-12"
                                                    wire:click="cargar('status', {{ $usuario->id_usu }})"
                                                    style="cursor: pointer;">
                                                    <i class="ti ti-circle-check me-1"></i>
                                                    Activo
                                                </span>
                                                @else
                                                <span class="badge bg-light-danger rounded f-12"
                                                    wire:click="cargar('status', {{ $usuario->id_usu }})"
                                                    style="cursor: pointer;">
                                                    <i class="ti ti-circle-x me-1"></i>
                                                    Inactivo
                                                </span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                {{ \Carbon\Carbon::parse($usuario->created_at)->format('d/m/Y') }}
                                            </td>
                                            <td class="text-center">
                                                <ul class="list-inline me-auto mb-0">
                                                    <li class="list-inline-item align-bottom" data-bs-toggle="tooltip"
                                                        aria-label="Editar" data-bs-original-title="Editar"
                                                        wire:click="cargar('editar', {{ $usuario->id_usu }})">
                                                        <a href="#"
                                                            class="avtar avtar-xs btn-link-secondary btn-pc-default">
                                                            <i class="ti ti-edit-circle f-18"></i>
                                                        </a>
                                                    </li>
                                                    <li class="list-inline-item align-bottom" data-bs-toggle="tooltip"
                                                        aria-label="Eliminar" data-bs-original-title="Eliminar"
                                                        wire:click="cargar('eliminar', {{ $usuario->id_usu }})">
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
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Usuario -->
    <div wire:ignore.self id="{{ $nombre_modal }}" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form class="modal-content" wire:submit.prevent="{{ $action_form }}">
                <div class="modal-header animate_animated animatefadeIn animate_faster">
                    <h5 class="modal-title">
                        {{ $titulo_modal }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        wire:click="reset_modal"></button>
                </div>
                <div class="modal-body animate_animated animatefadeIn animate_faster">
                    <div class="row-g3">
                        <div class="col-md-12">
                            <label class="form-label" for="foto_usu">
                                Agregar Foto <span class="text-danger">*</span>
                            </label>
                            <input type="file"
                                class="form-control @if ($errors->has('foto_usu')) is-invalid @elseif($foto_usu) is-valid @endif"
                                wire:model.live="foto_usu" id="foto_usu" placeholder="Seleccione una foto">
                            <small class="form-text text-muted">
                                Seleccione una foto para el usuario.
                            </small>
                            @error('foto_usu')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    
                    <div>
                        <label for="correo_usu" class="form-label">Correo del usuario<span class="text-danger">*</span>
                        </label>
                        <input type="email" class="form-control @error('correo_usu') is-invalid @enderror"
                            id="correo_usu" wire:model.live="correo_usu" placeholder="Ingrese Correo Nuevo">
                        <small class="form-text text-muted">
                            Ingrese Correo del Usuario
                        </small>
                        @error('correo_usu')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div>
                        <label for="contrasena_usu" class="form-label">Contraseña del Usuario<span
                                class="text-danger">*</span>
                        </label>
                        <div>
                            <input type="password" class="form-control @error('contrasena_usu') is-invalid @enderror"
                                id="contrasena_usu" wire:model.live="contrasena_usu"
                                placeholder="Ingrese Contraseña Nueva" autocomplete="new-password">
                            <small class="form-text text-muted">
                                Ingrese Contraseña del Usuario
                            </small>
                            @error('contrasena_usu')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <label for="rol" class="form-label required">
                            Rol
                        </label>
                        <select
                            class="form-select @if($errors->has('rol')) is-invalid @elseif($rol) is-valid-lite @endif"
                            id="rol" wire:model.live="rol">
                            <option value="">
                                Seleccione un rol
                            </option>
                            @foreach($roles as $rol)
                            <option value="{{ $rol->id_rol }}">
                                {{ $rol->nombre_rol }}
                            </option>
                            @endforeach
                        </select>
                        @error('rol')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="col-lg-12">
                        <label for="trabajador" class="form-label required">
                            Trabajador
                        </label>
                        <select
                            class="form-select @if($errors->has('trabajador')) is-invalid @elseif($trabajador) is-valid-lite @endif"
                            id="trabajador" wire:model.live="trabajador">
                            <option value="">
                                Seleccione un trabajador
                            </option>
                            @foreach($trabajadores as $trabajador)
                            <option value="{{ $trabajador->id_tra }}">
                                {{ $trabajador->nombres_tra }}
                            </option>
                            @endforeach
                        </select>
                        @error('trabajador')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="modal-footer animate_animated animatefadeIn animate_faster">
                        <button type="button" class="btn btn-light-danger" data-bs-dismiss="modal"
                            wire:click="reset_modal">
                            Cerrar
                        </button>
                        <button type="submit" class="btn btn-primary" style="width: 100px;" wire:loading.attr="disabled"
                            wire:target="guardar">
                            <span wire:loading.remove wire:target="guardar">
                                Guardar
                            </span>
                            <div class="spinner-border spinner-border-sm" role="status" wire:loading
                                wire:target="guardar">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </button>
                    </div>
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
                        <div class="col-md-12 animate_animated animatefadeIn animate_faster">
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