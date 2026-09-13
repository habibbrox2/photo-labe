@extends('errors.layout')

@section('title', 'Too Many Requests')
@section('icon')
<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
</svg>
@endsection
@section('code', '429')
@section('heading', 'Too many requests')
@section('message', "You've made too many requests in a short time. Please wait a moment and try again.")
@section('primary_url', url('/'))
@endsection
