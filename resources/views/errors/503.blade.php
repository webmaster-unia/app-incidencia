@extends('errors::minimal')

@section('image')
    <img class="img-fluid" src="../assets/images/pages/img-error-500.svg" alt="img" />
@endsection
@section('title', 'Servicio no disponible')
@section('code', '503')
@section('message', '¡Vaya!. El servicio se encuentra en mantenimiento y no está disponible en este momento. Por favor, intenta nuevamente en unos momentos.')
