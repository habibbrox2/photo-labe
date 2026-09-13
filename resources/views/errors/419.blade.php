@extends('errors.layout')

@section('title', 'Session Expired')
@section('icon')
<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
</svg>
@endsection
@section('code', '419')
@section('heading', 'Session expired')
@section('message', 'Your session has expired for security reasons. Please go back, refresh the page, and try again.')
@section('primary_url', url()->previous() ?: url('/'))
@section('primary_label', 'Go Back')
@endsection
