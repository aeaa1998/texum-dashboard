@extends('layouts.dashboard')

@section('nav-content')
<clients :payload="{{ $clients }}" />
@endsection