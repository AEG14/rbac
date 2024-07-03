@extends('mainLayout')

@section('page-content')
<div class="container-fluid">
    Simplicity is the ultimate sophistication. - Leonardo da Vinci
    <p>
        @can('create')
           <a href="{{ url('acctg/new') }}">Add New Ledger Entry</a>
        @endcan
        @can('viewAll')
           <a href="{{ url('acctg/view/all') }}">View All Ledgers</a>
        @endcan
    </p>
    <p>
        @include('slugs.homeLink')
    </p>
</div>
@endsection
