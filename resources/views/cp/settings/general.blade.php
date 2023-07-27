@extends('statamic::layout')

@section('content')
    <publish-form
        action="{{ cp_route('convertkit.store') }}"
        :blueprint='@json($blueprint)'
        :meta='@json($meta)'
        :values='@json($values)'
        title="ConvertKit"
    ></publish-form>
@endsection
