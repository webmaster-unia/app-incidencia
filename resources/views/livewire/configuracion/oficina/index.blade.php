<?php

use Livewire\Volt\Component;
use Livewire\Attributes\{Layout, Title, Url, Validate};
use App\Models\{Oficina, Cargo, OficinaCargo};
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
    public string $action_form = 'crear_oficina';

    // Variables para el formulario
    #[Validate('required|string|max:255')]
    public string $nombre = '';
    #[Validate('required|string|max:255')]
    public string $cargo = '';
    public $cargos = [];
    #[Validate('required|array|min:1')]
    public $cargosSeleccionados = [];

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

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedRegistros(): void
    {
        $this->resetPage();
    }

    public function reset_modal(): void
    {
        $this->reset(
            'nombre',
            'cargo',
            'cargosSeleccionados',
            'modo_modal',
            'id_oficina',
            'action_form',
            'titulo_modal',
            'cargos',
            'alerta',
            'mensaje',
            'action'
        );
        $this->resetErrorBag();
        $this->resetValidation();
    }

    // Metodo para cargar los datos de oficina
    public function cargar(string $modo, ?int $id): void
    {
        $this->reset_modal();
        $this->modo_modal = $modo;
        $this->id_oficina = $id;

        if ($modo === 'crear') {
            // Asignar los valores a las variables
            $this->titulo_modal = 'Nuevo Registro';
            $this->action_form = 'crear_oficina';
            $this->cargos = Cargo::query()
                ->where('activo_car', true)
                ->get();

            // Abrir el modal
            $this->dispatch('modal',
                modal: '#'.$this->nombre_modal,
                action: 'show'
            );
        } elseif ($modo === 'editar') {
            // Buscar la oficina
            $data = Oficina::query()
                ->with('cargos')
                ->findOrFail($id);

            // Asignar los valores a las variables
            $this->titulo_modal = 'Editar Registro';
            $this->action_form = 'editar_oficina';
            $this->nombre = $data->nombre_ofi;
            $this->cargos = Cargo::query()
                ->where('activo_car', true)
                ->get();
            $this->cargosSeleccionados = $data->cargos->pluck('id_car')->toArray();

            // Abrir el modal
            $this->dispatch('modal',
                modal: '#'.$this->nombre_modal,
                action: 'show'
            );
        } elseif ($modo === 'eliminar') {
            // Buscar la oficina
            $data = Oficina::query()
                ->with('cargos')
                ->findOrFail($id);

            $this->titulo_modal = '';
            $this->alerta = '¡Atención!';
            $this->mensaje = '¿Está seguro de eliminar la oficina "' . $data->nombre_ofi . '"?';
            $this->action = 'eliminar_oficina';

            // Abrir el modal
            $this->dispatch('modal',
                modal: '#alerta',
                action: 'show'
            );
        } elseif ($modo === 'status') {
            // Buscar la oficina
            $data = Oficina::query()
                ->with('cargos')
                ->findOrFail($id);

            $this->titulo_modal = '';
            $this->alerta = '¡Atención!';
            $this->mensaje = $data->activo_ofi
                ? '¿Está seguro de desactivar la oficina "' . $data->nombre_ofi . '"?'
                : '¿Está seguro de activar la oficina "' . $data->nombre_ofi . '"?';
            $this->action = 'cambiar_estado_oficina';

            // Abrir el modal
            $this->dispatch('modal',
                modal: '#alerta',
                action: 'show'
            );
        }
    }

    // Metodo para agregar un nuevo registro de cargo
    public function agregar_cargo(): void
    {
        // Validar el campo cargo
        $this->validate([
            'cargo' => 'required|string|max:255'
        ]);

        // Crear el cargo
        $cargo = new Cargo();
        $cargo->nombre_car = $this->cargo;
        $cargo->activo_car = true;
        $cargo->save();

        // Actualizar la lista de cargos
        $this->cargos = Cargo::query()
            ->where('activo_car', true)
            ->get();

        // Limpiar el campo cargo
        $this->reset('cargo');
    }

    // Metodo para eliminar un registro de cargo
    public function eliminar_cargo(Cargo $cargo): void
    {
        $oficinas_count = $cargo->oficinas()->count();
        if ($oficinas_count > 0) {
            // Mostrar mensaje de error
            $this->dispatch(
                'toast',
                text: 'No se puede eliminar el cargo "' . $cargo->nombre_car . '" porque está asociado a una oficina.',
                color: 'danger'
            );
            return;
        } else {
            // Eliminar el cargo
            $cargo->delete();

            // Actualizar la lista de cargos
            $this->cargos = Cargo::query()
                ->where('activo_car', true)
                ->get();

            // Mostrar mensaje de éxito
            $this->dispatch(
                'toast',
                text: 'El cargo "' . $cargo->nombre_car . '" ha sido eliminado correctamente.',
                color: 'success'
            );
        }
    }

    // Metodo para crear una nueva oficina
    public function crear_oficina(): void
    {
        // Validar los campos
        $this->validate([
            'nombre' => 'required|string|max:255',
            'cargosSeleccionados' => 'required|array|min:1'
        ]);

        // Creamos la oficina
        $oficina = new Oficina();
        $oficina->nombre_ofi = $this->nombre;
        $oficina->activo_ofi = true;
        $oficina->save();

        // Asignar los cargos a la oficina creada
        foreach ($this->cargosSeleccionados as $id_car) {
            $oficina_cargo = new OficinaCargo();
            $oficina_cargo->id_ofi = $oficina->id_ofi;
            $oficina_cargo->id_car = $id_car;
            $oficina_cargo->save();
        }

        // Mostrar mensaje de éxito
        $this->dispatch(
            'toast',
            text: 'La oficina "' . $this->nombre . '" ha sido creada correctamente.',
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

    // Metodo para editar una oficina
    public function editar_oficina(): void
    {
        // Validar los campos
        $this->validate([
            'nombre' => 'required|string|max:255',
            'cargosSeleccionados' => 'required|array|min:1'
        ]);

        //  Editamos la oficina
        $oficina = Oficina::query()
            ->findOrFail($this->id_oficina);
        $oficina->nombre_ofi = $this->nombre;
        $oficina->save();

        // Reasignar los cargos a la oficina editada del modelo oficina_cargo
        $cargos = OficinaCargo::query()
            ->where('id_ofi', $this->id_oficina)
            ->get();
        // Comparar los cargos seleccionados con los cargos actuales
        $cargos = $cargos->pluck('id_car')->toArray();
        // Si hay mas cargos seleccionados que los actuales, se crean los nuevos
        $nuevos_cargos = array_diff($this->cargosSeleccionados, $cargos);
        if (count($nuevos_cargos) > 0) {
            foreach ($nuevos_cargos as $id_car) {
                $oficina_cargo = new OficinaCargo();
                $oficina_cargo->id_ofi = $this->id_oficina;
                $oficina_cargo->id_car = $id_car;
                $oficina_cargo->save();
            }
        }
        // Si hay menos cargos seleccionados que los actuales, se eliminan los actuales
        $cargos_eliminar = array_diff($cargos, $this->cargosSeleccionados);
        if (count($cargos_eliminar) > 0) {
            foreach ($cargos_eliminar as $id_car) {
                $oficina_cargo = OficinaCargo::query()
                    ->where('id_ofi', $this->id_oficina)
                    ->where('id_car', $id_car)
                    ->first();
                // Verificamos si el cargo está asociado a un trabajador
                $trabajadores_count = $oficina_cargo->trabajadores()->count();
                if ($trabajadores_count > 0) {
                    // Mostrar mensaje de error
                    $this->dispatch(
                        'toast',
                        text: 'No se puede eliminar el cargo "' . $oficina_cargo->cargo->nombre_car . '" porque está asociado a un trabajador.',
                        color: 'danger'
                    );
                    return;
                } else {
                    $oficina_cargo->delete();
                }
            }
        }

        // Mostrar mensaje de éxito
        $this->dispatch(
            'toast',
            text: 'La oficina "' . $this->nombre . '" ha sido actualizado correctamente.',
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

    // Metodo para cambiar el estado de la oficina
    public function cambiar_estado_oficina(): void
    {
        // Buscar la oficina
        $oficina = Oficina::query()
            ->findOrFail($this->id_oficina);

        // Cambiar el estado de la oficina
        $oficina->activo_ofi = !$oficina->activo_ofi;
        $oficina->save();

        // Mostrar mensaje de éxito
        $this->dispatch(
            'toast',
            text: 'La oficina "' . $oficina->nombre_ofi . '" ha sido ' . ($oficina->activo_ofi ? 'activado' : 'desactivado') . ' correctamente.',
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

    // Metodo para eliminar una oficina
    public function eliminar_oficina(): void
    {
        // Buscar la oficina
        $oficina = Oficina::query()
            ->findOrFail($this->id_oficina);

        // Verificar si la oficina tiene trabajadores
        $oficina_cargo = OficinaCargo::query()
            ->where('id_ofi', $this->id_oficina)
            ->get();
        foreach ($oficina_cargo as $item) {
            // Verificar si el cargo está asociado a un trabajador
            $trabajadores_count = $item->trabajadores()->count();
            if ($trabajadores_count > 0) {
                // Mostrar mensaje de error
                $this->dispatch(
                    'toast',
                    text: 'No se puede eliminar la oficina "' . $oficina->nombre_ofi . '" porque el cargo "' . $item->cargo->nombre_car . '" está asociado a un trabajador.',
                    color: 'danger'
                );
                return;
            } else {
                $item->delete();
            }
        }

        // Eliminar la oficina
        $oficina->delete();

        // Mostrar mensaje de éxito
        $this->dispatch(
            'toast',
            text: 'La oficina "' . $oficina->nombre_ofi . '" ha sido eliminado correctamente.',
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
                                                    @forelse ($item->cargos as $cargo)
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
                                                            wire:click="cargar('status', {{ $item->id_ofi }})"
                                                            style="cursor: pointer;"
                                                        >
                                                            <i class="ti ti-circle-check me-1"></i>
                                                            Activo
                                                        </span>
                                                    @else
                                                        <span
                                                            class="badge bg-light-danger rounded f-12"
                                                            wire:click="cargar('status', {{ $item->id_ofi }})"
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
                                                            wire:click="cargar('editar', {{ $item->id_ofi }})"
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
                                                            wire:click="cargar('eliminar', {{ $item->id_ofi }})"
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
                            {{-- <div class="list-group">
                                @foreach ($cargos as $cargo)
                                    <label
                                        class="list-group-item"
                                        wire:key="cargo-{{ $cargo->id_car }}"
                                    >
                                        <input
                                            class="form-check-input me-1"
                                            type="checkbox"
                                            wire:model.live="cargosSeleccionados"
                                            value="{{ $cargo->id_car }}"
                                        >
                                        {{ $cargo->nombre_car }}
                                    </label>
                                @endforeach
                            </div> --}}
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
