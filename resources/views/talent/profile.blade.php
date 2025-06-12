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
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-900">Skills & Strengths</h2>
                @if(count($profile['strengths']) > 5)
                    <button id="toggleSkills" class="text-blue-600 hover:text-blue-800 text-sm">Show More</button>
                @endif
            </div>
            <div class="space-y-2 max-h-80 overflow-y-auto">
                @php
                    $maxWeight = collect($profile['strengths'])->max('weight') ?? 1;
                    $maxWeight = max($maxWeight, 1); // Ensure we don't divide by zero
                @endphp
                @foreach($profile['strengths'] as $index => $strength)
                    <div class="p-2 bg-gray-50 rounded-lg {{ $index >= 5 ? 'hidden skill-item' : '' }}">
                        <div class="flex items-center justify-between">
                            <div class="flex-1 min-w-0">
                                <h3 class="font-medium text-gray-900 text-sm truncate">{{ $strength['name'] ?? 'Unknown' }}</h3>
                                @if(isset($strength['proficiency']))
                                    <p class="text-xs text-gray-600">{{ $strength['proficiency'] }}</p>
                                @endif
                            </div>
                            @if(isset($strength['weight']))
                                @php
                                    // Normalize the weight to 0-100 scale
                                    $normalizedWeight = ($strength['weight'] / $maxWeight) * 100;
                                    $displayWeight = round($normalizedWeight);
                                @endphp
                                <div class="flex items-center space-x-2 ml-2">
                                    <span class="text-xs font-medium text-blue-600 whitespace-nowrap">{{ $displayWeight }}%</span>
                                    <div class="w-12 bg-gray-200 rounded-full h-1.5">
                                        <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ min($displayWeight, 100) }}%"></div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
                @if(count($profile['strengths']) > 5)
                    <p class="text-xs text-gray-500 text-center pt-2">Showing 5 of {{ count($profile['strengths']) }} skills</p>
                @endif
            </div>
        </div>
        @endif

        <!-- Languages -->
        @if(isset($profile['languages']) && count($profile['languages']) > 0)
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Languages</h2>
            <div class="space-y-2">
                @foreach($profile['languages'] as $language)
                    <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg">
                        <span class="font-medium text-gray-900 text-sm">{{ $language['language'] ?? 'Unknown' }}</span>
                        @if(isset($language['fluency']))
                            <span class="text-xs text-gray-600 bg-blue-100 px-2 py-1 rounded whitespace-nowrap">
                                {{ $language['fluency'] }}
                            </span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Interests - Full Width Section -->
    @if(isset($profile['interests']) && count($profile['interests']) > 0)
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Interests</h2>
        <div class="flex flex-wrap gap-2">
            @foreach(array_slice($profile['interests'], 0, 20) as $interest)
                <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm">
                    {{ $interest['name'] ?? 'Unknown' }}
                </span>
            @endforeach
            @if(count($profile['interests']) > 20)
                <span class="text-sm text-gray-500 px-3 py-1">+{{ count($profile['interests']) - 20 }} more</span>
            @endif
        </div>
    </div>
    @endif

    <!-- Experience - Compact Version -->
    @if(isset($profile['experiences']) && count($profile['experiences']) > 0)
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-gray-900">Experience</h2>
            <button id="toggleExperience" class="text-blue-600 hover:text-blue-800 text-sm">Show All</button>
        </div>
        
        <div id="experienceContent" class="space-y-4 max-h-96 overflow-y-auto">
            @foreach($profile['experiences'] as $index => $experience)
                <div class="border-l-4 border-blue-500 pl-4 pb-4 {{ $index > 2 ? 'hidden experience-item' : '' }}">
                    @if(isset($experience['name']))
                        <h3 class="font-semibold text-gray-900 text-sm">{{ $experience['name'] }}</h3>
                    @endif
                    
                    @if(isset($experience['organizations']) && count($experience['organizations']) > 0)
                        <div class="mt-1">
                            @foreach(array_slice($experience['organizations'], 0, 2) as $org)
                                <p class="text-gray-700 text-sm">
                                    <span class="font-medium">{{ $org['name'] ?? 'Unknown Organization' }}</span>
                                    @if(isset($org['fromMonth']) && isset($org['fromYear']))
                                        <span class="text-gray-500 text-xs ml-2">
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
                        <ul class="mt-1 text-xs text-gray-600 list-disc list-inside">
                            @foreach(array_slice($experience['responsibilities'], 0, 2) as $responsibility)
                                <li>{{ Str::limit($responsibility, 100) }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endforeach
            
            @if(count($profile['experiences']) > 3)
                <p class="text-xs text-gray-500 text-center pt-2">Showing 3 of {{ count($profile['experiences']) }} experiences</p>
            @endif
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
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle skills visibility
    const toggleSkillsBtn = document.getElementById('toggleSkills');
    const skillItems = document.querySelectorAll('.skill-item');
    let showingAllSkills = false;
    
    if (toggleSkillsBtn) {
        toggleSkillsBtn.addEventListener('click', function() {
            showingAllSkills = !showingAllSkills;
            skillItems.forEach(item => {
                if (showingAllSkills) {
                    item.classList.remove('hidden');
                } else {
                    item.classList.add('hidden');
                }
            });
            toggleSkillsBtn.textContent = showingAllSkills ? 'Show Less' : 'Show More';
        });
    }
    
    // Toggle experience visibility
    const toggleBtn = document.getElementById('toggleExperience');
    const experienceItems = document.querySelectorAll('.experience-item');
    let showingAll = false;
    
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            showingAll = !showingAll;
            experienceItems.forEach(item => {
                if (showingAll) {
                    item.classList.remove('hidden');
                } else {
                    item.classList.add('hidden');
                }
            });
            toggleBtn.textContent = showingAll ? 'Show Less' : 'Show All';
        });
    }
    
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
</script>
@endsection