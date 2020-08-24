@extends('layouts.dashboard')

@section('nav-content')
<requests-general :payload="{{ $payload }}" :statuses="{{ $statuses }}" :lots="{{ $lots }}" :letters="{{ $letters }}" :rows="{{ $rows }}" :columns="{{ $columns }}" />
@endsection