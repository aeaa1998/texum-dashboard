@extends('layouts.dashboard')

@section('nav-content')
<roles :payload="{{ $roles }}" :menus="{{ $menus }}" />
@endsection