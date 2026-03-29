@extends('admin.layouts.app')

@section('title', 'Delivered Orders')

@section('content')
    @include('admin.orders.partials.listing', ['pageTitle' => $pageTitle ?? 'Delivered'])
@endsection
