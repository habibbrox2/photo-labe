@extends('errors.layout')

@section('title', 'Internal Server Error')
@section('icon')
<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.008M12 2.25a9.75 9.75 0 100 19.5 9.75 9.75 0 000-19.5z" />
</svg>

@section('code', '500')
@section('heading', 'Something went wrong')
@section('message', 'An unexpected error occurred. Our team has been notified — please try again in a moment.')

@section('content')
<div class="error-details">
    <div class="error-details-head">
        <h3 class="error-details-title">Error Details</h3>
        <button id="copyMarkdownBtn" class="copy-button" type="button">
            <svg id="copyIcon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m2 4v6a2 2 0 01-2 2h-6a2 2 0 01-2-2v-6a2 2 0 012-2h6a2 2 0 012 2z" />
            </svg>
            <span id="copyLabel">Copy as Markdown</span>
        </button>
    </div>

    <div id="errorMarkdown" class="error-markdown">
@php
$details = $errorDetails ?? null;
if ($details):
?>
## Error Report

**Exception:** `{{ $details['exception'] }}`  
**Message:** {{ $details['message'] }}  
**Code:** {{ $details['code'] }}  
**File:** `{{ $details['file'] }}` (line {{ $details['line'] }})  
**URL:** {{ $details['url'] }}  
**Method:** {{ $details['method'] }}  
**IP:** {{ $details['ip'] }}  
**Time:** {{ $details['timestamp'] }}  
**PHP:** {{ $details['php_version'] }}  
**Platform:** {{ $details['laravel_version'] }}

### Stack Trace

@php
foreach ($details['trace'] as $frame):
?>
{{ $frame['number'] }}. `{{ $frame['file'] }}` (line {{ $frame['line'] }})
   @if($frame['function'])→ {{ $frame['function'] }}({{ $frame['args'] }})@endif

@php
endforeach;
@endphp
@else
An unexpected error occurred. Please try again later.
@endphp
    </div>
</div>
@endif

@section('primary_label', 'Back to Home')
@section('primary_url', url('/'))

@push('scripts')
<script>
(function() {
    const btn = document.getElementById('copyMarkdownBtn');
    const label = document.getElementById('copyLabel');
    const icon = document.getElementById('copyIcon');
    const code = document.getElementById('errorMarkdown');

    btn.addEventListener('click', async function() {
        const text = code.innerText;

        try {
            await navigator.clipboard.writeText(text);
            label.textContent = 'Copied!';
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />';
            setTimeout(() => {
                label.textContent = 'Copy as Markdown';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m2 4v6a2 2 0 01-2 2h-6a2 2 0 01-2-2v-6a2 2 0 012-2h6a2 2 0 012 2z" />';
            }, 2000);
        } catch (err) {
            // Fallback for older browsers
            const ta = document.createElement('textarea');
            ta.value = text;
            ta.style.position = 'fixed';
            ta.style.opacity = '0';
            document.body.appendChild(ta);
            ta.select();
            try {
                document.execCommand('copy');
                label.textContent = 'Copied!';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />';
                setTimeout(() => {
                    label.textContent = 'Copy as Markdown';
                    icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m2 4v6a2 2 0 01-2 2h-6a2 2 0 01-2-2v-6a2 2 0 012-2h6a2 2 0 012 2z" />';
                }, 2000);
            } catch (e) {
                label.textContent = 'Failed';
            }
            document.body.removeChild(ta);
        }
    });
})();
</script>
@endpush