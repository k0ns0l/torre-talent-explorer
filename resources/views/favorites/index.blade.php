@extends('layouts.app')

@section('title', 'My Favorites - Torre Explorer')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">My Favorites</h1>
        <div class="flex space-x-3">
            <a href="{{ route('talent.search') }}" class="btn-secondary">
                Search Profiles
            </a>
            <a href="{{ route('opportunities.search') }}" class="btn-primary">
                Search Jobs
            </a>
        </div>
    </div>

    @if($favorites->count() > 0)
    @php
    $profiles = $favorites->where('type', 'profile');
    $opportunities = $favorites->where('type', 'opportunity');
    @endphp

    @if($opportunities->count() > 0)
    <div>
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Saved Jobs ({{ $opportunities->count() }})</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($opportunities as $favorite)
            <div class="opportunity-card">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900 text-lg">{{ $favorite->name }}</h3>
                        @if($favorite->company_name)
                        <p class="text-blue-600 text-sm mt-1">{{ $favorite->company_name }}</p>
                        @endif
                        @if($favorite->location)
                        <p class="text-gray-600 text-sm mt-1">📍 {{ $favorite->location }}</p>
                        @endif
                    </div>
                    @if($favorite->min_salary)
                    <div class="text-right">
                        <p class="text-green-600 font-semibold">
                            ${{ number_format($favorite->min_salary) }}
                            @if($favorite->max_salary)
                            - ${{ number_format($favorite->max_salary) }}
                            @else
                            +
                            @endif
                        </p>
                    </div>
                    @endif
                </div>

                @if($favorite->summary)
                <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ $favorite->summary }}</p>
                @endif

                <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                    <a href="{{ route('opportunities.show', $favorite->opportunity_id) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        View Details →
                    </a>
                    <button onclick="removeFavorite('{{ $favorite->opportunity_id }}')" class="text-red-600 hover:text-red-800 text-sm">
                        Remove
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    @else
    <div class="text-center py-12">
        <div class="text-gray-400 text-6xl mb-4">⭐</div>
        <h3 class="text-xl font-medium text-gray-900 mb-2">No favorites yet</h3>
        <p class="text-gray-600 mb-6">Start searching for jobs to add them to your favorites!</p>
    </div>
    @endif
</div>

<script>
    function removeFavorite(identifier) {
        if (confirm('Remove this item from favorites?')) {
            axios.delete(`/api/favorites/${identifier}`)
                .then(response => {
                    if (response.data.success) {
                        location.reload();
                    }
                })
                .catch(error => {
                    alert('Error removing favorite');
                });
        }
    }
</script>
@endsection