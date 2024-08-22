@extends('errors::minimal')

@section('image')
    <img class="img-fluid" src="{{ asset('assets/images/pages/img-error-403.svg') }}" alt="img" />
@endsection
@section('title', 'Prohibido')
@section('code', '403')
@section('message', $exception->getMessage() ?: '¡Vaya! Error 403. No tienes permiso para acceder a esta página.')
