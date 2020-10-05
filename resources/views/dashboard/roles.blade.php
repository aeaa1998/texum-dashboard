@extends('layouts.dashboard')

@section('nav-content')
<roles :roles="{{ $role }}" />
@endsection