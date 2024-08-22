@extends('errors::minimal')

@section('image')
    <img class="img-fluid" src="{{ asset('assets/images/pages/img-error-404.svg') }}" alt="img" />
@endsection
@section('title', 'Página no encontrada')
@section('code', '404')
@section('message', '¡Vaya! Error 404. No se pudo encontrar la página que estás buscando.')
