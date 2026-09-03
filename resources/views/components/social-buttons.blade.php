{{-- Social login buttons (Google / Facebook) — rendered only when providers are configured --}}
@php $providerCount = count($socialProviders ?? []); @endphp

@if($providerCount > 0)
    <div class="{{ $providerCount > 1 ? 'grid grid-cols-2 gap-3' : 'flex' }}">
        @foreach($socialProviders as $provider)
            <a href="{{ route('auth.social.redirect', $provider) }}"
                class="flex items-center justify-center gap-2.5 px-4 py-2.5 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 hover:border-gray-300 transition-colors {{ $providerCount === 1 ? 'flex-1' : '' }}">
                @if($provider === 'google')
                    {{-- Google "G" --}}
                    <svg class="w-5 h-5" viewBox="0 0 48 48" aria-hidden="true">
                        <path fill="#FFC107" d="M43.611 20.083H42V20H24v8h11.303c-1.649 4.657-6.08 8-11.303 8-6.627 0-12-5.373-12-12s5.373-12 12-12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4 12.955 4 4 12.955 4 24s8.955 20 20 20 20-8.955 20-20c0-1.341-.138-2.65-.389-3.917z"/>
                        <path fill="#FF3D00" d="M6.306 14.691l6.571 4.819C14.655 15.108 18.961 12 24 12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4 16.318 4 9.656 8.337 6.306 14.691z"/>
                        <path fill="#4CAF50" d="M24 44c5.166 0 9.86-1.977 13.409-5.192l-6.19-5.238A11.91 11.91 0 0 1 24 36c-5.202 0-9.619-3.317-11.283-7.946l-6.522 5.025C9.505 39.556 16.227 44 24 44z"/>
                        <path fill="#1976D2" d="M43.611 20.083H42V20H24v8h11.303a12.04 12.04 0 0 1-4.087 5.571l.003-.002 6.19 5.238C36.971 39.205 44 34 44 24c0-1.341-.138-2.65-.389-3.917z"/>
                    </svg>
                @elseif($provider === 'facebook')
                    {{-- Facebook "f" --}}
                    <svg class="w-5 h-5" viewBox="0 0 48 48" aria-hidden="true">
                        <path fill="#1877F2" d="M24 4C12.955 4 4 12.955 4 24c0 9.85 7.314 18.011 16.875 19.48V29.75h-5.08V24h5.08v-4.406c0-5.014 2.987-7.784 7.556-7.784 2.189 0 4.477.391 4.477.391v4.922h-2.522c-2.485 0-3.26 1.542-3.26 3.124V24h5.547l-.887 5.75h-4.66v13.73C36.686 42.011 44 33.85 44 24 44 12.955 35.045 4 24 4z"/>
                        <path fill="#fff" d="M31.113 29.75l.887-5.75h-5.547v-3.732c0-1.582.775-3.124 3.26-3.124h2.522v-4.922s-2.288-.391-4.477-.391c-4.569 0-7.556 2.77-7.556 7.784V24h-5.08v5.75h5.08v13.73a20.1 20.1 0 0 0 6.25 0V29.75h4.661z"/>
                    </svg>
                @endif
                <span class="text-sm font-semibold text-gray-700">{{ ucfirst($provider) }}</span>
            </a>
        @endforeach
    </div>

    <div class="flex items-center gap-4 my-6">
        <span class="h-px flex-1 bg-gray-200"></span>
        <span class="text-xs uppercase tracking-wider text-gray-400 font-medium">{{ $label ?? 'or continue with email' }}</span>
        <span class="h-px flex-1 bg-gray-200"></span>
    </div>
@endif
