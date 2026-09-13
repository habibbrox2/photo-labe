@extends('errors.layout')

@section('title', 'Access Denied')
@section('icon')
<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
</svg>
@endsection
@section('code', '403')
@section('heading', 'Access denied')
@section('message', "You don't have permission to view this page. If you believe this is a mistake, please contact support.")
@section('primary_url', url('/'))
@endsection
