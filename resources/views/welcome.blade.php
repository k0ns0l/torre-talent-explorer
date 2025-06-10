@extends('layouts.app')

@section('title', 'Torre Talent Explorer - Discover Amazing Talent')

@section('content')
<div class="section">
    <!-- Hero Section -->
    <div class="hero-section animate-fade-in">
        <div class="absolute top-4 right-4 opacity-20">
            <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24">
                <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
        <h1 class="text-5xl md:text-6xl font-bold mb-6 text-shadow">
            Discover Amazing
            <span class="block bg-gradient-to-r from-yellow-300 to-orange-300 bg-clip-text text-transparent">
                Talent
            </span>
        </h1>
        <p class="text-xl md:text-2xl max-w-4xl mx-auto mb-10 text-blue-100 leading-relaxed">
            Explore Torre.ai's talent network with powerful analytics. Search profiles, analyze skills, compare professionals, and discover insights from the world's most innovative talent platform.
        </p>
        <div class="flex flex-col sm:flex-row justify-center gap-4 sm:gap-6">
            <a href="{{ route('talent.search') }}" class="btn-primary text-lg px-8 py-4">
                <span class="flex items-center justify-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span>Search Talent</span>
                </span>
            </a>
            <a href="{{ route('opportunities.search') }}" class="btn-secondary text-lg px-8 py-4 bg-white/20 text-white border-white/30 hover:bg-white/30">
                <span class="flex items-center justify-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                    </svg>
                    <span>Find Jobs</span>
                </span>
            </a>
        </div>
    </div>

    <!-- Featured Profiles Section -->
    <div class="animate-fade-in-up">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-8 gap-4">
            <div>
                <h2 class="text-3xl md:text-4xl font-bold gradient-text mb-2">Featured Talent</h2>
                <p class="text-gray-600 text-lg">Discover amazing professionals from around the world</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <button id="analyzeBtn" class="bg-emerald-500 text-white px-4 py-2 rounded-xl hover:bg-emerald-600 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed transform hover:scale-105 shadow-lg" disabled>
                    <span class="flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <span class="hidden sm:inline">Analyze</span>
                    </span>
                </button>
                <button id="compareBtn" class="bg-purple-500 text-white px-4 py-2 rounded-xl hover:bg-purple-600 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed transform hover:scale-105 shadow-lg" disabled>
                    <span class="flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        <span class="hidden sm:inline">Compare (0/2)</span>
                        <span class="sm:hidden">Compare</span>
                    </span>
                </button>
                <button id="exportBtn" class="bg-orange-500 text-white px-4 py-2 rounded-xl hover:bg-orange-600 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed transform hover:scale-105 shadow-lg" disabled>
                    <span class="flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="hidden sm:inline">Export</span>
                    </span>
                </button>
                <button id="connectivityBtn" class="bg-indigo-500 text-white px-4 py-2 rounded-xl hover:bg-indigo-600 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed transform hover:scale-105 shadow-lg" disabled>
                    <span class="flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                        </svg>
                        <span class="hidden sm:inline">Connections</span>
                    </span>
                </button>
            </div>
        </div>
        
        <div id="loadingProfiles" class="text-center py-16">
            <div class="inline-flex items-center space-x-3">
                <div class="loading-spinner w-8 h-8"></div>
                <span class="text-lg text-gray-600">Loading featured profiles...</span>
            </div>
        </div>
        
        <div id="profilesGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 hidden">
            <!-- Profiles will be loaded here -->
        </div>
    </div>

    <!-- Features Section -->
    <div class="card p-8 md:p-12 animate-fade-in-up">
        <h2 class="text-3xl md:text-4xl font-bold text-center gradient-text mb-4">Platform Features</h2>
        <p class="text-center text-gray-600 text-lg mb-12 max-w-2xl mx-auto">
            Powerful tools to help you discover, analyze, and connect with the best talent
        </p>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="feature-card group">
                <div class="feature-icon bg-gradient-to-br from-blue-500 to-blue-600 group-hover:scale-110 group-hover:rotate-3">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Smart Search</h3>
                <p class="text-gray-600 leading-relaxed">Find talent by name, skills, or location using Torre.ai's powerful search engine with fuzzy matching.</p>
            </div>
            
            <div class="feature-card group">
                <div class="feature-icon bg-gradient-to-br from-emerald-500 to-emerald-600 group-hover:scale-110 group-hover:rotate-3">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Skills Analysis</h3>
                <p class="text-gray-600 leading-relaxed">Analyze skill distributions, locations, and company experience across multiple profiles with detailed insights.</p>
            </div>
            
            <div class="feature-card group">
                <div class="feature-icon bg-gradient-to-br from-purple-500 to-purple-600 group-hover:scale-110 group-hover:rotate-3">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Profile Comparison</h3>
                <p class="text-gray-600 leading-relaxed">Compare two professionals side-by-side to find similarities, differences, and compatibility scores.</p>
            </div>
            
            <div class="feature-card group">
                <div class="feature-icon bg-gradient-to-br from-indigo-500 to-indigo-600 group-hover:scale-110 group-hover:rotate-3">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Network Analysis</h3>
                <p class="text-gray-600 leading-relaxed">Discover connections and relationships between professionals with advanced connectivity scoring.</p>
            </div>
        </div>
    </div>

    <!-- Analytics Section -->
    <div id="analyticsSection" class="hidden card p-8 animate-slide-in-right">
        <h3 class="text-2xl font-bold gradient-text mb-6">Skills Analysis</h3>
        <div id="analyticsContent">
            <!-- Analytics will be populated here -->
        </div>
    </div>

    <!-- Comparison Section -->
    <div id="comparisonSection" class="hidden card p-8 animate-slide-in-right">
        <h3 class="text-2xl font-bold gradient-text mb-6">Profile Comparison</h3>
        <div id="comparisonContent">
            <!-- Comparison will be populated here -->
        </div>
    </div>

    <!-- Connectivity Section -->
    <div id="connectivitySection" class="hidden card p-8 animate-slide-in-right">
        <h3 class="text-2xl font-bold gradient-text mb-6">Network Connectivity Analysis</h3>
        <div id="connectivityContent">
            <!-- Connectivity will be populated here -->
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const profilesGrid = document.getElementById('profilesGrid');
    const loadingProfiles = document.getElementById('loadingProfiles');
    const analyzeBtn = document.getElementById('analyzeBtn');
    const compareBtn = document.getElementById('compareBtn');
    const exportBtn = document.getElementById('exportBtn');
    const connectivityBtn = document.getElementById('connectivityBtn');
    const analyticsSection = document.getElementById('analyticsSection');
    const comparisonSection = document.getElementById('comparisonSection');
    const connectivitySection = document.getElementById('connectivitySection');
    
    let selectedProfiles = new Set();

    // Load featured profiles on page load
    loadFeaturedProfiles();

    function loadFeaturedProfiles() {
        // Search for some popular profiles to showcase
        const featuredQueries = ['developer', 'designer', 'manager', 'engineer'];
        const randomQuery = featuredQueries[Math.floor(Math.random() * featuredQueries.length)];
        
        axios.post('/api/search', { query: randomQuery })
            .then(response => {
                if (response.data.success && response.data.results.length > 0) {
                    displayProfiles(response.data.results.slice(0, 12)); // Show first 12 results
                } else {
                    showNoProfiles();
                }
            })
            .catch(error => {
                console.error('Error loading featured profiles:', error);
                showNoProfiles();
            })
            .finally(() => {
                loadingProfiles.classList.add('hidden');
                profilesGrid.classList.remove('hidden');
            });
    }

    function displayProfiles(profiles) {
        profilesGrid.innerHTML = '';
        
        profiles.forEach(profile => {
            const card = createProfileCard(profile);
            profilesGrid.appendChild(card);
        });
    }

    function createProfileCard(profile) {
        const card = document.createElement('div');
        card.className = 'bg-white border border-gray-200 rounded-lg p-6 hover:shadow-lg transition-shadow';
        
        const username = profile.username || profile.ggId || profile.name?.toLowerCase().replace(/\s+/g, '') || 'unknown';
        const isSelectable = profile.username || profile.ggId;
        
        card.innerHTML = `
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    ${profile.picture ? `
                        <img src="${profile.picture}" alt="${profile.name}" class="w-12 h-12 rounded-full mb-3">
                    ` : `
                        <div class="w-12 h-12 rounded-full bg-gray-300 flex items-center justify-center mb-3">
                            <span class="text-gray-600 font-semibold">${(profile.name || 'U')[0].toUpperCase()}</span>
                        </div>
                    `}
                    <h3 class="font-semibold text-gray-900 text-lg">${profile.name || 'Unknown'}</h3>
                    ${profile.professionalHeadline ? `<p class="text-sm text-gray-600 mt-1 line-clamp-2">${profile.professionalHeadline}</p>` : ''}
                </div>
                ${isSelectable ? `
                    <input type="checkbox" class="profile-checkbox ml-2 mt-2" data-username="${username}" data-name="${profile.name || 'Unknown'}">
                ` : ''}
            </div>
            
            <div class="space-y-2 text-sm text-gray-500">
                ${profile.location ? `<p class="flex items-center"><span class="mr-1">📍</span> ${profile.location}</p>` : ''}
                ${profile.username ? `<p class="flex items-center"><span class="mr-1">@</span> ${profile.username}</p>` : ''}
                ${profile.weight ? `<p class="flex items-center"><span class="mr-1">⭐</span> Score: ${Math.round(profile.weight * 100)}</p>` : ''}
            </div>
            
            ${profile.username ? `
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <a href="/profile/${profile.username}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        View Full Profile →
                    </a>
                </div>
            ` : ''}
        `;

        if (isSelectable) {
            const checkbox = card.querySelector('.profile-checkbox');
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    selectedProfiles.add(username);
                } else {
                    selectedProfiles.delete(username);
                }
                updateButtons();
            });
        }

        return card;
    }

    function showNoProfiles() {
        profilesGrid.innerHTML = `
            <div class="col-span-full text-center py-12">
                <div class="text-gray-400 mb-4">
                    <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No profiles available</h3>
                <p class="text-gray-500 mb-4">Try searching for specific talent using the search page.</p>
                <a href="{{ route('talent.search') }}" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition-colors">
                    Search Talent
                </a>
            </div>
        `;
    }

    function updateButtons() {
        const count = selectedProfiles.size;
        analyzeBtn.disabled = count === 0;
        compareBtn.disabled = count !== 2;
        exportBtn.disabled = count === 0;
        connectivityBtn.disabled = count < 2;
        compareBtn.textContent = `Compare (${count}/2)`;
        connectivityBtn.textContent = `Analyze Connections (${count})`;
    }

    // Event listeners for action buttons
    analyzeBtn.addEventListener('click', analyzeProfiles);
    compareBtn.addEventListener('click', compareProfiles);
    exportBtn.addEventListener('click', exportProfiles);
    connectivityBtn.addEventListener('click', analyzeConnectivity);

    function analyzeProfiles() {
        if (selectedProfiles.size === 0) return;

        const profiles = Array.from(selectedProfiles);
        
        axios.post('/api/analytics/skills', { profiles })
            .then(response => {
                if (response.data.success) {
                    displayAnalytics(response.data.analytics);
                } else {
                    alert('Analysis failed: ' + response.data.message);
                }
            })
            .catch(error => {
                console.error('Analytics error:', error);
                alert('Analysis failed. Please try again.');
            });
    }

    function compareProfiles() {
        if (selectedProfiles.size !== 2) return;

        const profiles = Array.from(selectedProfiles);
        
        axios.post('/api/analytics/compare', { profiles })
            .then(response => {
                if (response.data.success) {
                    displayComparison(response.data.comparison);
                } else {
                    alert('Comparison failed: ' + response.data.message);
                }
            })
            .catch(error => {
                console.error('Comparison error:', error);
                alert('Comparison failed. Please try again.');
            });
    }

    function exportProfiles() {
        if (selectedProfiles.size === 0) return;

        const profiles = Array.from(selectedProfiles);
        
        axios.post('/api/export/profiles', { 
            profiles: profiles,
            format: 'csv'
        }, {
            responseType: 'blob'
        })
        .then(response => {
            const url = window.URL.createObjectURL(new Blob([response.data]));
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', 'torre_profiles.csv');
            document.body.appendChild(link);
            link.click();
            link.remove();
        })
        .catch(error => {
            console.error('Export error:', error);
            alert('Export failed. Please try again.');
        });
    }

    function analyzeConnectivity() {
        if (selectedProfiles.size < 2) return;

        const profiles = Array.from(selectedProfiles);
        
        axios.post('/api/connectivity/analyze', { identifiers: profiles })
            .then(response => {
                if (response.data.success) {
                    displayConnectivity(response.data.connectivity);
                } else {
                    alert('Connectivity analysis failed: ' + response.data.message);
                }
            })
            .catch(error => {
                console.error('Connectivity error:', error);
                alert('Connectivity analysis failed. Please try again.');
            });
    }

    function displayAnalytics(analytics) {
        const content = document.getElementById('analyticsContent');
        
        content.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <h4 class="font-semibold text-gray-900 mb-3">Top Skills</h4>
                    <div class="space-y-2">
                        ${Object.entries(analytics.skills).slice(0, 10).map(([skill, count]) => `
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-700">${skill}</span>
                                <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">${count}</span>
                            </div>
                        `).join('')}
                    </div>
                </div>
                
                <div>
                    <h4 class="font-semibold text-gray-900 mb-3">Locations</h4>
                    <div class="space-y-2">
                        ${Object.entries(analytics.locations).slice(0, 8).map(([location, count]) => `
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-700">${location}</span>
                                <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">${count}</span>
                            </div>
                        `).join('')}
                    </div>
                </div>
                
                <div>
                    <h4 class="font-semibold text-gray-900 mb-3">Companies</h4>
                    <div class="space-y-2">
                        ${Object.entries(analytics.companies).slice(0, 8).map(([company, count]) => `
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-700">${company}</span>
                                <span class="bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded">${count}</span>
                            </div>
                        `).join('')}
                    </div>
                </div>
            </div>
            
            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-600">
                    Analysis based on <strong>${analytics.total_profiles}</strong> selected profiles
                </p>
            </div>
        `;
        
        analyticsSection.classList.remove('hidden');
        comparisonSection.classList.add('hidden');
        connectivitySection.classList.add('hidden');
    }

    function displayComparison(comparison) {
        const content = document.getElementById('comparisonContent');
        const profiles = Object.values(comparison.profiles);
        
        content.innerHTML = `
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                ${profiles.map((profile, index) => `
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-900 mb-2">${profile.name}</h4>
                        <div class="space-y-2 text-sm">
                            <p><strong>Skills:</strong> ${profile.skill_count}</p>
                            <p><strong>Experience:</strong> ${profile.experience_count} positions</p>
                            <p><strong>Location:</strong> ${profile.location}</p>
                            <p><strong>Languages:</strong> ${profile.languages.join(', ') || 'None listed'}</p>
                        </div>
                    </div>
                `).join('')}
            </div>
            
            <div class="bg-blue-50 rounded-lg p-4 mb-4">
                <h4 class="font-semibold text-blue-900 mb-2">
                    Similarity Score: ${comparison.similarities.similarity_score}%
                </h4>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-semibold text-green-700 mb-3">Similarities</h4>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm font-medium text-gray-700">Common Skills (${comparison.similarities.common_skills.length})</p>
                            <div class="flex flex-wrap gap-1 mt-1">
                                ${comparison.similarities.common_skills.slice(0, 10).map(skill => `
                                    <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">${skill}</span>
                                `).join('')}
                            </div>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-700">Common Languages</p>
                            <div class="flex flex-wrap gap-1 mt-1">
                                ${comparison.similarities.common_languages.map(lang => `
                                    <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">${lang}</span>
                                `).join('')}
                            </div>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h4 class="font-semibold text-red-700 mb-3">Differences</h4>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm font-medium text-gray-700">Unique to ${profiles[0].name}</p>
                            <div class="flex flex-wrap gap-1 mt-1">
                                ${comparison.differences.unique_skills_1.slice(0, 8).map(skill => `
                                    <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded">${skill}</span>
                                `).join('')}
                            </div>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-700">Unique to ${profiles[1].name}</p>
                            <div class="flex flex-wrap gap-1 mt-1">
                                ${comparison.differences.unique_skills_2.slice(0, 8).map(skill => `
                                    <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded">${skill}</span>
                                `).join('')}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        comparisonSection.classList.remove('hidden');
        analyticsSection.classList.add('hidden');
        connectivitySection.classList.add('hidden');
    }

    function displayConnectivity(connectivity) {
        const content = document.getElementById('connectivityContent');
        
        content.innerHTML = `
            <div class="space-y-6">
                ${connectivity.score ? `
                    <div class="bg-blue-50 rounded-lg p-4">
                        <h4 class="font-semibold text-blue-900 mb-2">Connectivity Score</h4>
                        <p class="text-2xl font-bold text-blue-600">${connectivity.score.score || 'N/A'}</p>
                        <p class="text-sm text-blue-700">${connectivity.score.description || 'Network connectivity strength'}</p>
                    </div>
                ` : ''}
                
                ${connectivity.highlights ? `
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-3">Connection Highlights</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            ${Array.isArray(connectivity.highlights) ? connectivity.highlights.map(highlight => `
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <h5 class="font-medium text-gray-900">${highlight.title || 'Connection'}</h5>
                                    <p class="text-sm text-gray-600 mt-1">${highlight.description || 'Shared connection found'}</p>
                                </div>
                            `).join('') : '<p class="text-gray-500">No specific highlights available</p>'}
                        </div>
                    </div>
                ` : ''}
                
                ${connectivity.information ? `
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-3">Detailed Information</h4>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <pre class="text-sm text-gray-700 whitespace-pre-wrap">${JSON.stringify(connectivity.information, null, 2)}</pre>
                        </div>
                    </div>
                ` : ''}
            </div>
        `;
        
        connectivitySection.classList.remove('hidden');
        analyticsSection.classList.add('hidden');
        comparisonSection.classList.add('hidden');
    }
});
</script>
@endsection
