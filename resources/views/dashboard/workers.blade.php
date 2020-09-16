@extends('layouts.dashboard')

@section('nav-content')
<workers :payload="{{ $payload }}" />
@endsection