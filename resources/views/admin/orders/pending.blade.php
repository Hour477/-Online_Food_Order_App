@extends('admin.layouts.app')

@section('title', 'Pending Orders')

@section('content')
    @include('admin.orders.partials.listing', ['pageTitle' => $pageTitle ?? 'Pending'])
@endsection
