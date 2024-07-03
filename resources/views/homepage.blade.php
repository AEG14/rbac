{{-- @extends('mainLayout')

@section('page-title','Main Landing Page')

@section('page-content')
<div class="container-fluid">
    <h1>Welcome to the Site</h1>
    <br>
    <a href="{{ route('acctg') }}"
        @unless(Auth::user()->hasRole('admin') || Auth::user()->hasRole('bookeeper') || Auth::user()->hasRole('auditor') || Auth::user()->hasRole('audasst'))
            class="link-dark not-allowed" style={!! '"pointer-events: none; cursor: not-allowed;"' !!}
        @endunless
    >Accounting</a>
    <a href="{{ route('prod') }}"
        @unless(Auth::user()->hasRole('admin') || Auth::user()->hasRole('assembler'))
            class="link-dark not-allowed" style={!! '"pointer-events: none; cursor: not-allowed;"' !!}
        @endunless
    >Production</a>
    @if(Auth::user()->hasRole('admin'))
       <a href="{{ route('dash') }}" >Dashboard</a>
    @endif
</div>
@endsection --}}

@extends('mainLayout')

@section('page-title','Main Landing Page')

@section('page-content')
<div class="container-fluid">
    <h1>Welcome to the Site</h1>
    <br>
    <a href="{{ route('acctg') }}"
        @unless(Auth::user()->hasPermissionTo('can_manage') || Auth::user()->hasPermissionTo('can_view_all'))
            class="link-dark not-allowed" style="pointer-events: none; cursor: not-allowed;"
        @endunless
    >Accounting</a>
    <a href="{{ route('prod') }}"
        @unless(Auth::user()->hasPermissionTo('can_manage') || Auth::user()->hasPermissionTo('can_view_all') && Auth::user()->hasPermissionTo('can_create') && Auth::user()->hasPermissionTo('can_update'))
            class="link-dark not-allowed" style="pointer-events: none; cursor: not-allowed;"
        @endunless
    >Production</a>
    @if(Auth::user()->hasPermissionTo('can_manage'))
       <a href="{{ route('dash') }}" >Dashboard</a>
    @endif
</div>
@endsection

