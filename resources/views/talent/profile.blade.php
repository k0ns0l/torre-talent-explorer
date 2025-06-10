@extends('layouts.app')

@section('title', ($profile['person']['name'] ?? 'Profile') . ' - Torre Explorer')

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div>
        <a href="{{ route('talent.search') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
            ← Back to Search
        </a>
    </div>

    <!-- Profile Header -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-start space-x-6">
            <div class="flex-shrink-0">
                @if(isset($profile['person']['picture']))
                    <img src="{{ $profile['person']['picture'] }}" alt="Profile Picture" class="w-24 h-24 rounded-full object-cover">
                @else
                    <div class="w-24 h-24 rounded-full bg-gray-300 flex items-center justify-center">
                        <span class="text-gray-600 text-2xl">{{ substr($profile['person']['name'] ?? 'U', 0, 1) }}</span>
                    </div>
                @endif
            </div>
            
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-gray-900">{{ $profile['person']['name'] ?? 'Unknown' }}</h1>
                @if(isset($profile['person']['professionalHeadline']))
                    <p class="text-xl text-gray-600 mt-2">{{ $profile['person']['professionalHeadline'] }}</p>
                @endif
                
                <div class="flex flex-wrap gap-4 mt-4 text-sm text-gray-600">
                    @if(isset($profile['person']['location']['name']))
                        <span class="flex items-center">
                            📍 {{ $profile['person']['location']['name'] }}
                        </span>
                    @endif
                    
                    @if(isset($profile['person']['links']))
                        @foreach($profile['person']['links'] as $link)
                            @if(isset($link['name']) && isset($link['address']))
                                <a href="{{ $link['address'] }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                    {{ $link['name'] }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow-sm p-4 text-center">
            <div class="text-2xl font-bold text-blue-600">{{ count($profile['strengths'] ?? []) }}</div>
            <div class="text-sm text-gray-600">Skills</div>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-4 text-center">
            <div class="text-2xl font-bold text-green-600">{{ count($profile['experiences'] ?? []) }}</div>
            <div class="text-sm text-gray-600">Experiences</div>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-4 text-center">
            <div class="text-2xl font-bold text-purple-600">{{ count($profile['languages'] ?? []) }}</div>
            <div class="text-sm text-gray-600">Languages</div>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-4 text-center">
            <div class="text-2xl font-bold text-orange-600">{{ count($profile['interests'] ?? []) }}</div>
            <div class="text-sm text-gray-600">Interests</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Skills/Strengths -->
        @if(isset($profile['strengths']) && count($profile['strengths']) > 0)
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Skills & Strengths</h2>
            <div class="space-y-3">
                @foreach($profile['strengths'] as $strength)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <h3 class="font-medium text-gray-900">{{ $strength['name'] ?? 'Unknown' }}</h3>
                            @if(isset($strength['proficiency']))
                                <p class="text-sm text-gray-600">Proficiency: {{ $strength['proficiency'] }}</p>
                            @endif
                        </div>
                        @if(isset($strength['weight']))
                            <div class="text-right">
                                <div class="text-sm font-medium text-blue-600">{{ round($strength['weight'] * 100) }}%</div>
                                <div class="w-16 bg-gray-200 rounded-full h-2 mt-1">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $strength['weight'] * 100 }}%"></div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Languages -->
        @if(isset($profile['languages']) && count($profile['languages']) > 0)
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Languages</h2>
            <div class="space-y-3">
                @foreach($profile['languages'] as $language)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="font-medium text-gray-900">{{ $language['language'] ?? 'Unknown' }}</span>
                        @if(isset($language['fluency']))
                            <span class="text-sm text-gray-600 bg-blue-100 px-2 py-1 rounded">
                                {{ $language['fluency'] }}
                            </span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Experience -->
    @if(isset($profile['experiences']) && count($profile['experiences']) > 0)
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Experience</h2>
        <div class="space-y-6">
            @foreach($profile['experiences'] as $experience)
                <div class="border-l-4 border-blue-500 pl-4">
                    @if(isset($experience['name']))
                        <h3 class="font-semibold text-gray-900">{{ $experience['name'] }}</h3>
                    @endif
                    
                    @if(isset($experience['organizations']) && count($experience['organizations']) > 0)
                        <div class="mt-2">
                            @foreach($experience['organizations'] as $org)
                                <p class="text-gray-700">
                                    <span class="font-medium">{{ $org['name'] ?? 'Unknown Organization' }}</span>
                                    @if(isset($org['fromMonth']) && isset($org['fromYear']))
                                        <span class="text-gray-500 text-sm ml-2">
                                            {{ $org['fromMonth'] }}/{{ $org['fromYear'] }}
                                            @if(isset($org['toMonth']) && isset($org['toYear']))
                                                - {{ $org['toMonth'] }}/{{ $org['toYear'] }}
                                            @else
                                                - Present
                                            @endif
                                        </span>
                                    @endif
                                </p>
                            @endforeach
                        </div>
                    @endif

                    @if(isset($experience['responsibilities']) && count($experience['responsibilities']) > 0)
                        <ul class="mt-2 text-sm text-gray-600 list-disc list-inside">
                            @foreach(array_slice($experience['responsibilities'], 0, 3) as $responsibility)
                                <li>{{ $responsibility }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Interests -->
    @if(isset($profile['interests']) && count($profile['interests']) > 0)
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Interests</h2>
        <div class="flex flex-wrap gap-2">
            @foreach($profile['interests'] as $interest)
                <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm">
                    {{ $interest['name'] ?? 'Unknown' }}
                </span>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Job Matches -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-900">Recommended Opportunities</h2>
            <div class="bg-gray-100 text-gray-600 px-4 py-2 rounded-md">
                Feature Coming Soon
            </div>
        </div>
        <div class="text-center py-8 text-gray-500">
            <p>Opportunity matching will be available when Torre.ai provides public access to their opportunities API.</p>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    const loadMatchesBtn = document.getElementById('loadMatches');
    const matchesContent = document.getElementById('matchesContent');
    const matchesLoading = document.getElementById('matchesLoading');
    const matchesResults = document.getElementById('matchesResults');
    
    if (loadMatchesBtn) {
        loadMatchesBtn.addEventListener('click', function() {
            matchesContent.classList.remove('hidden');
            matchesLoading.classList.remove('hidden');
            matchesResults.innerHTML = '';
            
            axios.get('/api/opportunities/matches/{{ $username }}')
                .then(response => {
                    if (response.data.success) {
                        displayMatches(response.data.matches);
                    } else {
                        matchesResults.innerHTML = '<p class="text-gray-500 text-center py-4">No matches found</p>';
                    }
                })
                .catch(error => {
                    console.error('Matches error:', error);
                    matchesResults.innerHTML = '<p class="text-red-500 text-center py-4">Error loading matches</p>';
                })
                .finally(() => {
                    matchesLoading.classList.add('hidden');
                });
        });
    }
    
    function displayMatches(matches) {
        if (!matches || matches.length === 0) {
            matchesResults.innerHTML = '<p class="text-gray-500 text-center py-4">No opportunities found</p>';
            return;
        }
        
        matchesResults.innerHTML = matches.slice(0, 5).map(match => `
            <div class="border border-gray-200 rounded-lg p-4 mb-4">
                <h3 class="font-semibold text-gray-900">${match.objective || 'Opportunity'}</h3>
                ${match.organizations?.[0]?.name ? `<p class="text-blue-600 text-sm">${match.organizations[0].name}</p>` : ''}
                ${match.locations?.[0]?.name ? `<p class="text-gray-600 text-sm">📍 ${match.locations[0].name}</p>` : ''}
                ${match.compensation?.minAmount ? `
                    <p class="text-green-600 text-sm font-medium">
                        ${match.compensation.minAmount.toLocaleString()}${match.compensation.maxAmount ? ` - ${match.compensation.maxAmount.toLocaleString()}` : '+'}
                    </p>
                ` : ''}
                <div class="mt-2 flex justify-between items-center">
                    <span class="text-xs text-gray-500">Match Score: ${Math.round((match.score || 0) * 100)}%</span>
                    ${match.id ? `<a href="/opportunities/${match.id}" class="text-blue-600 hover:text-blue-800 text-sm">View Details →</a>` : ''}
                </div>
            </div>
        `).join('');
    }
});
</script></div>
@endsection
