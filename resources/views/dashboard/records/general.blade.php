@extends('layouts.dashboard')

@section('nav-content')
<records-general :payload="{{ $payload }}" :statuses="{{ $statuses }}" :lots="{{ $lots }}" :letters="{{ $letters }}" :rows="{{ $rows }}" :columns="{{ $columns }}" />
@endsection