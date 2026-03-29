@extends('admin.layouts.app')

@section('title', 'Cancelled Orders')

@section('content')
    @include('admin.orders.partials.listing', ['pageTitle' => $pageTitle ?? 'Cancelled'])
@endsection
