@extends('layouts.dashboard')

@section('nav-content')
<clients :clients="{{ $clients }}" />
@endsection