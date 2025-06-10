@extends('layouts.app')

@section('title', 'Search Talent - Torre Explorer')

@section('content')
<div class="space-y-8">
    <!-- Hero Section -->
    <div class="text-center">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">
            Discover Amazing Talent
        </h1>
        <p class="text-xl text-gray-600 max-w-2xl mx-auto">
            Search and analyze profiles from Torre.ai's talent network. Find skills, compare profiles, and discover insights.
        </p>
    </div>

    <!-- Search Section -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="max-w-2xl mx-auto">
            <div class="relative">
                <input 
                    type="text" 
                    id="searchInput"
                    placeholder="Search for people or organizations..."
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-lg"
                >
                <button 
                    id="searchBtn"
                    class="absolute right-2 top-2 bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition-colors"
                >
                    Search
                </button>
            </div>
            <div id="searchLoading" class="hidden mt-4 text-center">
                <div class="inline-flex items-center">
                    <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-600 mr-2"></div>
                    Searching...
                </div>
            </div>
        </div>
    </div>

    <!-- Results Section -->
    <div id="searchResults" class="hidden">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-gray-900">Search Results</h2>
            <div class="flex space-x-2">
                <button id="analyzeBtn" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition-colors disabled:opacity-50" disabled>
                    Analyze Selected
                </button>
                <button id="compareBtn" class="bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700 transition-colors disabled:opacity-50" disabled>
                    Compare (2)
                </button>
                <button id="exportBtn" class="bg-orange-600 text-white px-4 py-2 rounded-md hover:bg-orange-700 transition-colors disabled:opacity-50" disabled>
                    Export CSV
                </button>
                <button id="connectivityBtn" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition-colors disabled:opacity-50" disabled>
                    Analyze Connections
                </button>
            </div>
        </div>
        <div id="resultsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Results will be populated here -->
        </div>
    </div>

    <!-- Analytics Section -->
    <div id="analyticsSection" class="hidden bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Skills Analysis</h3>
        <div id="analyticsContent">
            <!-- Analytics will be populated here -->
        </div>
    </div>

    <!-- Comparison Section -->
    <div id="comparisonSection" class="hidden bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Profile Comparison</h3>
        <div id="comparisonContent">
            <!-- Comparison will be populated here -->
        </div>
    </div>

    <!-- Connectivity Section -->
    <div id="connectivitySection" class="hidden bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Network Connectivity Analysis</h3>
        <div id="connectivityContent">
            <!-- Connectivity will be populated here -->
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchBtn = document.getElementById('searchBtn');
    const searchLoading = document.getElementById('searchLoading');
    const searchResults = document.getElementById('searchResults');
    const resultsGrid = document.getElementById('resultsGrid');
    const analyzeBtn = document.getElementById('analyzeBtn');
    const compareBtn = document.getElementById('compareBtn');
    const exportBtn = document.getElementById('exportBtn');
    const connectivityBtn = document.getElementById('connectivityBtn');
    const analyticsSection = document.getElementById('analyticsSection');
    const comparisonSection = document.getElementById('comparisonSection');
    const connectivitySection = document.getElementById('connectivitySection');
    
    let selectedProfiles = new Set();

    // Auto-load profiles on page load
    loadInitialProfiles();

    // Event listeners
    searchBtn.addEventListener('click', performSearch);
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            performSearch();
        }
    });
    
    // Add event listeners for action buttons
    analyzeBtn.addEventListener('click', analyzeProfiles);
    compareBtn.addEventListener('click', compareProfiles);
    exportBtn.addEventListener('click', exportProfiles);
    connectivityBtn.addEventListener('click', analyzeConnectivity);

    function loadInitialProfiles() {
        searchLoading.classList.remove('hidden');
        
        // Load popular profiles or recent searches
        axios.post('/api/search/featured')
            .then(response => {
                if (response.data.success) {
                    displayResults(response.data.results);
                } else {
                    // Fallback to a basic search if featured endpoint doesn't exist
                    performSearch('developer');
                }
            })
            .catch(error => {
                console.log('Featured profiles not available, loading sample profiles');
                // Fallback to a basic search
                performSearch('developer');
            })
            .finally(() => {
                searchLoading.classList.add('hidden');
            });
    }
    
    function performSearch(defaultQuery = null) {
        const query = defaultQuery || searchInput.value.trim();
        console.log('Search triggered with query:', query);
        
        if (!query && !defaultQuery) {
            alert('Please enter a search term');
            return;
        }

        searchLoading.classList.remove('hidden');
        searchResults.classList.add('hidden');
        analyticsSection.classList.add('hidden');
        comparisonSection.classList.add('hidden');
        connectivitySection.classList.add('hidden');

        console.log('Making API request to /api/search');
        
        axios.post('/api/search', { query })
            .then(response => {
                console.log('Search response:', response);
                if (response.data.success) {
                    displayResults(response.data.results);
                } else {
                    alert('Search failed: ' + response.data.message);
                }
            })
            .catch(error => {
                console.error('Search error:', error);
                if (error.response) {
                    console.error('Error response:', error.response.data);
                    alert('Search failed: ' + (error.response.data.message || 'Server error'));
                } else {
                    alert('Search failed. Please check your connection and try again.');
                }
            })
            .finally(() => {
                searchLoading.classList.add('hidden');
            });
    }

    function displayResults(results) {
        resultsGrid.innerHTML = '';
        selectedProfiles.clear();
        updateButtons();

        if (results.length === 0) {
            resultsGrid.innerHTML = '<div class="col-span-full text-center text-gray-500 py-8">No results found</div>';
        } else {
            results.forEach(result => {
                const card = createResultCard(result);
                resultsGrid.appendChild(card);
            });
        }

        searchResults.classList.remove('hidden');
    }

    function createResultCard(result) {
        const card = document.createElement('div');
        card.className = 'bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow cursor-pointer';
        
        const username = result.username || result.ggId || result.name?.toLowerCase().replace(/\s+/g, '') || 'unknown';
        const isSelectable = result.username || result.ggId;
        
        card.innerHTML = `
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-900">${result.name || 'Unknown'}</h3>
                    ${result.professionalHeadline ? `<p class="text-sm text-gray-600 mt-1">${result.professionalHeadline}</p>` : ''}
                    ${result.location ? `<p class="text-xs text-gray-500 mt-2">📍 ${result.location}</p>` : ''}
                    ${result.username ? `<p class="text-xs text-blue-600 mt-1">@${result.username}</p>` : ''}
                </div>
                ${isSelectable ? `
                    <input type="checkbox" class="profile-checkbox ml-2" data-username="${username}" data-name="${result.name || 'Unknown'}">
                ` : ''}
            </div>
            ${result.username ? `
                <div class="mt-3 pt-3 border-t border-gray-100">
                    <a href="/profile/${result.username}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        View Profile →
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

    function updateButtons() {
        const count = selectedProfiles.size;
        analyzeBtn.disabled = count === 0;
        compareBtn.disabled = count !== 2;
        exportBtn.disabled = count === 0;
        connectivityBtn.disabled = count < 2;
        compareBtn.textContent = `Compare (${count}/2)`;
        connectivityBtn.textContent = `Analyze Connections (${count})`;
    }

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
    }

    function displayComparison(comparison) {
        const content = document.getElementById('comparisonContent');
        const profiles = Object.values(comparison.profiles);
        const usernames = Object.keys(comparison.profiles);
        
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
    }

    function displayConnectivity(connectivity) {
        const content = document.getElementById('connectivityContent');
        
        content.innerHTML = `
            <div class="space-y-6">
                <!-- Connectivity Score -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-6 border border-blue-200">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-xl font-semibold text-blue-900">Network Connectivity Score</h4>
                        <div class="text-3xl font-bold text-blue-600">${connectivity.score?.score || 'N/A'}%</div>
                    </div>
                    <p class="text-blue-700">${connectivity.score?.description || 'Analyzing connections...'}</p>
                    <div class="mt-3 bg-blue-200 rounded-full h-3">
                        <div class="bg-blue-600 h-3 rounded-full transition-all duration-500" style="width: ${connectivity.score?.score || 0}%"></div>
                    </div>
                </div>

                <!-- Connection Insights -->
                ${connectivity.insights ? `
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">🔍 Key Insights</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            ${connectivity.insights.map(insight => `
                                <div class="border border-gray-200 rounded-lg p-4 ${
                                    insight.strength === 'high' ? 'bg-green-50 border-green-200' : 
                                    insight.strength === 'medium' ? 'bg-yellow-50 border-yellow-200' : 
                                    'bg-gray-50'
                                }">
                                    <h5 class="font-medium text-gray-900 mb-2">${insight.title}</h5>
                                    <p class="text-sm text-gray-600">${insight.description}</p>
                                    <span class="inline-block mt-2 px-2 py-1 text-xs rounded ${
                                        insight.strength === 'high' ? 'bg-green-100 text-green-800' :
                                        insight.strength === 'medium' ? 'bg-yellow-100 text-yellow-800' :
                                        'bg-gray-100 text-gray-800'
                                    }">${insight.strength} relevance</span>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                ` : ''}

                <!-- Detailed Connections -->
                ${connectivity.connections ? `
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">📊 Connection Details</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            ${connectivity.connections.shared_skills?.length > 0 ? `
                                <div class="bg-white border border-gray-200 rounded-lg p-4">
                                    <h5 class="font-medium text-gray-900 mb-3 flex items-center">
                                        🛠️ Shared Skills (${connectivity.connections.shared_skills.length})
                                    </h5>
                                    <div class="space-y-1">
                                        ${connectivity.connections.shared_skills.slice(0, 8).map(skill => `
                                            <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded mr-1 mb-1">${skill}</span>
                                        `).join('')}
                                        ${connectivity.connections.shared_skills.length > 8 ? `<span class="text-xs text-gray-500">+${connectivity.connections.shared_skills.length - 8} more</span>` : ''}
                                    </div>
                                </div>
                            ` : ''}
                            
                            ${connectivity.connections.shared_companies?.length > 0 ? `
                                <div class="bg-white border border-gray-200 rounded-lg p-4">
                                    <h5 class="font-medium text-gray-900 mb-3 flex items-center">
                                        🏢 Shared Companies (${connectivity.connections.shared_companies.length})
                                    </h5>
                                    <div class="space-y-1">
                                        ${connectivity.connections.shared_companies.map(company => `
                                            <div class="text-sm text-gray-700">${company}</div>
                                        `).join('')}
                                    </div>
                                </div>
                            ` : ''}
                            
                            ${connectivity.connections.shared_locations?.length > 0 ? `
                                <div class="bg-white border border-gray-200 rounded-lg p-4">
                                    <h5 class="font-medium text-gray-900 mb-3 flex items-center">
                                        📍 Shared Locations (${connectivity.connections.shared_locations.length})
                                    </h5>
                                    <div class="space-y-1">
                                        ${connectivity.connections.shared_locations.map(location => `
                                            <div class="text-sm text-gray-700">${location}</div>
                                        `).join('')}
                                    </div>
                                </div>
                            ` : ''}
                        </div>
                    </div>
                ` : ''}

                <!-- Summary -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-600 text-center">
                        <strong>Analysis Summary:</strong> Found ${connectivity.connections?.total_connections || 0} connection points between the selected profiles.
                        ${connectivity.connections?.total_connections > 5 ? 'Strong networking potential!' : 
                          connectivity.connections?.total_connections > 2 ? 'Some common ground exists.' : 
                          'Diverse backgrounds could bring complementary skills.'}
                    </p>
                </div>
            </div>
        `;
        
        connectivitySection.classList.remove('hidden');
        analyticsSection.classList.add('hidden');
        comparisonSection.classList.add('hidden');
    }
});
</script>
@endsection
