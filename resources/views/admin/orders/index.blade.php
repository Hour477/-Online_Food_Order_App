@extends('admin.layouts.app')

@section('title', 'All Orders')

@section('content')
    @include('admin.orders.partials.listing', ['pageTitle' => $pageTitle ?? 'All Orders'])
@endsection
