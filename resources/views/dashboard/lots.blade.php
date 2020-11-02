@extends('layouts.dashboard')

@section('nav-content')
<lots :lots="{{ $lots }}" />
@endsection