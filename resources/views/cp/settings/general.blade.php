@extends('statamic::layout')

@section('content')
    <publish-form
        title="ConvertKit Settings"
        action="{{ cp_route('convertkit.store') }}"
        :blueprint='@json($blueprint)'
        :meta='@json($meta)'
        :values='@json($values)'
    ></publish-form>
@stop
