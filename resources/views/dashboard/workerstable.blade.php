@extends('layouts.dashboard')

@section('nav-content')
<workers-table :workerstable="{{ $workerstable }}"/>
@endsection