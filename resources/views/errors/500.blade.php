@extends('errors.layout')

@section('title', 'Server Error')
@section('icon')
<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714a2.25 2.25 0 00.659 1.591L19 14.5M14.25 3.104c.251.023.501.05.75.082M19 14.5l-2.47 2.47a2.25 2.25 0 01-1.591.659H8.56a2.25 2.25 0 01-1.591-.659L4.5 14.5m14.5 0v3.25a2.25 2.25 0 01-2.25 2.25H6.75a2.25 2.25 0 01-2.25-2.25V14.5" />
</svg>
@endsection
@section('code', '500')
@section('heading', 'Something went wrong')
@section('message', 'An unexpected error occurred. Our team has been notified — please try again in a moment.')
@section('primary_url', url('/'))
@endsection
