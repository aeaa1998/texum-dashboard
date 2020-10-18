@extends('layouts.dashboard')

@section('nav-content')
<workers-profile :user="{{ $user }}"/>
@endsection