@extends('admin.layouts.app')

@section('title', 'Completed Orders')

@section('content')
    @include('admin.orders.partials.listing', ['pageTitle' => $pageTitle ?? 'Completed'])
@endsection
