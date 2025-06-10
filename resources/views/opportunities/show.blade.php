@extends('layouts.app')

@section('title', ($opportunity['objective'] ?? 'Opportunity') . ' - Torre Explorer')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="space-y-6">
    <!-- Back Button -->
    <div>
        <a href="{{ route('opportunities.search') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
            ← Back to Search
        </a>
    </div>

    <!-- Opportunity Header -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-start">
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-gray-900">{{ $opportunity['objective'] ?? 'Opportunity' }}</h1>

                @if(isset($opportunity['organizations'][0]['name']))
                <p class="text-xl text-blue-600 mt-2">{{ $opportunity['organizations'][0]['name'] }}</p>
                @endif

                <div class="flex flex-wrap gap-4 mt-4 text-sm text-gray-600">
                    @if(isset($opportunity['locations'][0]['name']))
                    <span class="flex items-center">
                        📍 {{ $opportunity['locations'][0]['name'] }}
                    </span>
                    @endif

                    @if(isset($opportunity['remote']) && $opportunity['remote'])
                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded">Remote</span>
                    @endif

                    @if(isset($opportunity['type']))
                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded">{{ $opportunity['type'] }}</span>
                    @endif
                </div>
            </div>

            @if(isset($opportunity['compensation']['minAmount']))
            <div class="text-right">
                <p class="text-2xl font-bold text-green-600">
                    ${{ number_format($opportunity['compensation']['minAmount']) }}
                    @if(isset($opportunity['compensation']['maxAmount']))
                    - ${{ number_format($opportunity['compensation']['maxAmount']) }}
                    @else
                    +
                    @endif
                </p>
                <p class="text-sm text-gray-500">{{ $opportunity['compensation']['periodicity'] ?? 'per year' }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Job Description -->
    @if(isset($opportunity['summary']) || isset($opportunity['details']))
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Job Description</h2>

        @if(isset($opportunity['summary']))
        <div class="mb-4">
            <h3 class="font-semibold text-gray-900 mb-2">Summary</h3>
            <p class="text-gray-700">{{ $opportunity['summary'] }}</p>
        </div>
        @endif

        @if(isset($opportunity['details']))
        <div>
            <h3 class="font-semibold text-gray-900 mb-2">Details</h3>
            <div class="prose max-w-none text-gray-700">
                {!! nl2br(e($opportunity['details'])) !!}
            </div>
        </div>
        @endif
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Required Skills -->
        @if(isset($opportunity['strengths']) && count($opportunity['strengths']) > 0)
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Required Skills</h2>
            <div class="space-y-3">
                @foreach($opportunity['strengths'] as $skill)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div>
                        <h3 class="font-medium text-gray-900">{{ $skill['name'] ?? 'Unknown' }}</h3>
                        @if(isset($skill['experience']))
                        <p class="text-sm text-gray-600">Experience: {{ $skill['experience'] }}</p>
                        @endif
                    </div>
                    @if(isset($skill['weight']))
                    <div class="text-right">
                        <div class="text-sm font-medium text-blue-600">{{ round($skill['weight'] * 100) }}%</div>
                        <div class="w-16 bg-gray-200 rounded-full h-2 mt-1">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $skill['weight'] * 100 }}%"></div>
                        </div>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Company Info -->
        @if(isset($opportunity['organizations'][0]))
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Company Information</h2>
            <div class="space-y-4">
                @php $org = $opportunity['organizations'][0]; @endphp

                <div>
                    <h3 class="font-semibold text-gray-900">{{ $org['name'] ?? 'Unknown Company' }}</h3>
                    @if(isset($org['description']))
                    <p class="text-gray-700 mt-2">{{ $org['description'] }}</p>
                    @endif
                </div>

                @if(isset($org['size']))
                <div>
                    <span class="text-sm font-medium text-gray-700">Company Size:</span>
                    <span class="text-sm text-gray-600 ml-2">{{ $org['size'] }}</span>
                </div>
                @endif

                @if(isset($org['industry']))
                <div>
                    <span class="text-sm font-medium text-gray-700">Industry:</span>
                    <span class="text-sm text-gray-600 ml-2">{{ $org['industry'] }}</span>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>

    <!-- Application Section -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Apply for this Position</h2>
                <p class="text-gray-600 mt-1">Ready to take the next step in your career?</p>
            </div>

            <div class="flex space-x-3">
                @if(isset($opportunity['externalUrl']))
                <a href="{{ $opportunity['externalUrl'] }}" target="_blank" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                    Apply Now
                </a>
                @endif

                @auth
                <button id="favoriteBtn" onclick="toggleFavorite()" class="btn-secondary flex items-center space-x-2">
                    <svg id="favoriteIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    <span id="favoriteText">Add to Favorites</span>
                </button>
                @endauth
            </div>
        </div>

        @if(isset($opportunity['createdAt']))
        <div class="mt-4 pt-4 border-t border-gray-100">
            <p class="text-sm text-gray-500">
                Posted on {{ date('F j, Y', strtotime($opportunity['createdAt'])) }}
            </p>
        </div>
        @endif
    </div>
</div>

<script>
@auth
    let isFavorite = false;

    document.addEventListener('DOMContentLoaded', function() {
        // Check if opportunity is already favorited
        checkFavoriteStatus();
    });

    function checkFavoriteStatus() {
        axios.get('/api/favorites/check/{{ $id }}')
            .then(response => {
                isFavorite = response.data.is_favorite;
                updateFavoriteButton();
            })
            .catch(error => {
                console.error('Error checking favorite status:', error);
            });
    }

    function toggleFavorite() {
        const btn = document.getElementById('favoriteBtn');
        btn.disabled = true;

        if (isFavorite) {
            // Remove from favorites
            axios.delete('/api/favorites/{{ $id }}', {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => {
                    if (response.data.success) {
                        isFavorite = false;
                        updateFavoriteButton();
                    }
                })
                .catch(error => {
                    console.error('Error removing favorite:', error);
                    alert('Error removing from favorites');
                })
                .finally(() => {
                    btn.disabled = false;
                });
        } else {
            // Add to favorites
            const opportunityData = {
                type: 'opportunity',
                opportunity_id: '{{ $id }}',
                name: @json($opportunity["objective"] ?? "Job Opportunity"),
                summary: @json($opportunity["summary"] ?? ""),
                company_name: @json($opportunity["organizations"][0]["name"] ?? ""),
                location: @json($opportunity["locations"][0]["name"] ?? ""),
                min_salary: {{ $opportunity['compensation']['minAmount'] ?? 'null' }},
                max_salary: {{ $opportunity['compensation']['maxAmount'] ?? 'null' }}
            };

            axios.post('/api/favorites', opportunityData, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (response.data.success) {
                    isFavorite = true;
                    updateFavoriteButton();
                } else {
                    alert('Failed to add to favorites: ' + (response.data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error adding favorite:', error);
                let message = 'Error adding to favorites. Please try again.';
                if (error.response) {
                    console.error('Response Data:', error.response.data);
                    console.error('Response Status:', error.response.status);
                    if (error.response.data && error.response.data.message) {
                        message = `Error: ${error.response.data.message}`;
                    } else if (error.response.status === 422 && error.response.data && error.response.data.errors) {
                        const errors = Object.values(error.response.data.errors).flat().join(' ');
                        message = `Validation failed: ${errors}`;
                    } else {
                        message = `Error adding to favorites (Status: ${error.response.status}). Check console for details.`;
                    }
                } else if (error.request) {
                    message = 'Error adding to favorites: No response from server. Check network connection.';
                }
                alert(message);
            })
            .finally(() => {
                btn.disabled = false;
            });
        }
    }

    function updateFavoriteButton() {
        const btn = document.getElementById('favoriteBtn');
        const icon = document.getElementById('favoriteIcon');
        const text = document.getElementById('favoriteText');

        if (isFavorite) {
            btn.className = 'bg-red-500 text-white px-6 py-3 rounded-lg hover:bg-red-600 transition-colors flex items-center space-x-2';
            icon.setAttribute('fill', 'currentColor');
            text.textContent = 'Remove from Favorites';
        } else {
            btn.className = 'btn-secondary flex items-center space-x-2';
            icon.setAttribute('fill', 'none');
            text.textContent = 'Add to Favorites';
        }
    }
@endauth
</script>
@endsection