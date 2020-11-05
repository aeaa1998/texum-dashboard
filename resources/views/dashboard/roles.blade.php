@extends('layouts.dashboard')

@section('nav-content')
<roles :roles="{{ $roles }}" />
@endsection