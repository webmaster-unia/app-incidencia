<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use Livewire\Attributes\{Layout, Title, Url, Validate};
use App\Models\{Incidencia, Usuario, TrabajadorActivo, Complejidad};
use Illuminate\Support\Str;

new #[Layout('components.layouts.app')] #[Title('Gestion Incidencia | SIGEIN OTI')] class extends Component {
    // Sirve para usar la paginación
    use WithPagination;

    // Define la variables para el Page Header
    public string $titulo_componente = 'Gestión de Incidencia';
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
        $this->titulo_componente = 'Gestión de Incidencia';
        $this->breadcrumbs = [['url' => route('inicio.index'), 'title' => 'Inicio'], ['url' => '', 'title' => 'Configuración'], ['url' => '', 'title' => 'Gestion de Incidencia']];
    }

    public function reset_modal(): void
    {
        $this->reset(
            'modo_modal',
            'id_incidencia',
            'action_form',
            'titulo_modal',
            'alerta',
            'mensaje',
            'action',
            'incidencia_inc',
            'trabajador_activo',
            'solucion_inc',
            'estado_inc',
        );
        $this->resetErrorBag();
        $this->resetValidation();
    }

    // Metodo que carga el modal
    public function cargar(string $modo, ?int $id): void
    {
        $this->reset_modal();
        $this->modo_modal = $modo;
        $this->id_incidencia = $id;
        if ($modo == 'crear') {
            // Asignar los valores a las variables
            $this->titulo_modal = 'Nueva Incidencia';
            $this->action_form = 'crear_incidencia';
            // Abrir el modal
            $this->dispatch('modal', modal: '#'.$this->nombre_modal, action: 'show');
        } elseif ($modo == 'editar') {
            // Buscar incidencia
            $data = Incidencia::query()
                ->findOrFail($id);

            // Asignar los valores a las variables
            $this->titulo_modal = 'Editar Incidencia';
            $this->action_form = 'editar_incidencia';
            $this->incidencia_inc = $data->incidencia_inc;
            $this->solucion_inc = $data->solucion_inc;
            $this->trabajador_activo = $data->trabajador_activo;
            $this->estado_inc = $data->estado_inc;
            // Abrir el modal
            $this->dispatch('modal', modal: '#'.$this->nombre_modal, action: 'show');
        } elseif ($modo == 'eliminar') {
            // Buscar incidencia
            $data = Incidencia::query()
                ->findOrFail($id);

            $this->titulo_modal = '';
            $this->alerta = '¡Atención!';
            $this->mensaje = '¿Está seguro de eliminar la incidencia "' . $data->incidencia_inc. '"?';
            $this->action = 'eliminar_incidencia';

            // Abrir el modal
            $this->dispatch('modal', modal: '#alerta', action: 'show');
        } elseif ($modo == 'status') {
            // Buscar la incidencia
            $data = Incidencia::query()
                ->findOrFail($id);

            $this->titulo_modal = '';
            $this->alerta = '¡Atención!';
            $this->mensaje = $data->estado_inc
                ? '¿Está seguro de desactivar la incidencia "' . $data->incidencia_inc. '"?'
                : '¿Está seguro de activar la incidencia "' . $data->incidencia_inc. '"?';
            $this->action = 'cambiar_estado_incidencia';

            // Abrir el modal
            $this->dispatch('modal', modal: '#alerta', action: 'show');
        }
    }


    public function with(): array
    {
        $incidencias = Incidencia::query()
            ->search($this->search)
            ->paginate($this->registros);


        return [
            'incidencias' => $incidencias,
            
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
                                            <th class="col-md-3">INCIDENCIA</th>
                                            <th class="text-center">SOLUCIÓN</th>
                                            <th class="text-center">TRABAJADOR</th>
                                            <th class="text-center">ESTADO</th>
                                            <th class="text-center">ACCIONES</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($incidencias as $incidencia)
                                            <tr wire:key="{{ $incidencia->id_inc }}">
                                                <td>
                                                    {{ $incidencia->id_inc }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $incidencia->incidencia_inc }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $incidencia->solucion_inc }}
                                                </td>
                                                <td class="text-center">
                                                    {{--  {{ $incidencia->trabajador_activo->trabajador->nombre_tra }}  --}}

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
                                                    <ul class="list-inline me-auto mb-0">
                                                        <li class="list-inline-item align-bottom"
                                                            data-bs-toggle="tooltip" aria-label="Editar"
                                                            data-bs-original-title="Editar"
                                                            wire:click="cargar('editar', {{ $incidencia->id_inc }})">
                                                            <a href="#"
                                                                class="avtar avtar-xs btn-link-secondary btn-pc-default">
                                                                <i class="ti ti-edit-circle f-18"></i>
                                                            </a>
                                                        </li>
                                                        <li class="list-inline-item align-bottom"
                                                            data-bs-toggle="tooltip" aria-label="Eliminar"
                                                            data-bs-original-title="Eliminar"
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
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer pb-4">
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
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Usuario -->
    {{--  <div wire:ignore.self id="{{ $nombre_modal }}" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
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
                            <label class="form-label" for="incidencia_inc">
                                Incidencia <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                class="form-control @if ($errors->has('incidencia_inc')) is-invalid @elseif($incidencia_inc) is-valid @endif"
                                wire:model.live="incidencia_inc" id="incidencia_inc" placeholder="Ingrese la incidencia">
                            <small class="form-text text-muted">
                                Ingrese la descripción de la incidencia.
                            </small>
                            @error('incidencia_inc')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="solucion_inc" class="form-label">Solución<span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control @error('solucion_inc') is-invalid @enderror"
                            id="solucion_inc" wire:model.live="solucion_inc" placeholder="Ingrese la solución">
                        <small class="form-text text-muted">
                            Ingrese la solución de la incidencia.
                        </small>
                        @error('solucion_inc')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div>
                        <label for="trabajador_activo" class="form-label">Trabajador<span
                                class="text-danger">*</span>
                        </label>
                        <select
                            class="form-select @if($errors->has('trabajador_activo')) is-invalid @elseif($trabajador_activo) is-valid-lite @endif"
                            id="trabajador_activo" wire:model.live="trabajador_activo">
                            <option value="">
                                Seleccione un trabajador
                            </option>
                            @foreach($trabajadores as $trabajador)
                            <option value="{{ $trabajador->id_tra }}">
                                {{ $trabajador->nombres_tra }}
                            </option>
                            @endforeach
                        </select>
                        @error('trabajador_activo')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="col-lg-12">
                        <label for="estado_inc" class="form-label required">
                            Estado
                        </label>
                        <select
                            class="form-select @if($errors->has('estado_inc')) is-invalid @elseif($estado_inc) is-valid-lite @endif"
                            id="estado_inc" wire:model.live="estado_inc">
                            <option value="">
                                Seleccione un estado
                            </option>
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                        @error('estado_inc')
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
    </div>  --}}
</div>
