@extends('admin.layouts.app')

@section('title', 'Refunded Orders')

@section('content')
    @include('admin.orders.partials.listing', ['pageTitle' => $pageTitle ?? 'Refunded'])
@endsection
