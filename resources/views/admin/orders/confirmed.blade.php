@extends('admin.layouts.app')

@section('title', 'Confirmed Orders')

@section('content')
    @include('admin.orders.partials.listing', ['pageTitle' => $pageTitle ?? 'Confirmed'])
@endsection
