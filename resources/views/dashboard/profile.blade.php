@extends('layouts.dashboard')

@section('nav-content')
<profile :profile="{{ $profile }}" />
@endsection