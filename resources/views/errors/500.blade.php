@extends('errors::minimal')

@section('image')
    <img class="img-fluid" src="../assets/images/pages/img-error-500.svg" alt="img" />
@endsection
@section('title', 'Error del servidor')
@section('code', '500')
@section('message', '¡Vaya! Error 500 del servidor. Algo salió mal en el servidor, por favor, inténtelo de nuevo más tarde.')
