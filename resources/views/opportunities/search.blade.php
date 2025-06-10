@extends('layouts.app')

@section('title', 'Search Jobs - Torre Explorer')

@section('content')
<div class="space-y-8">
    <!-- Hero Section -->
    <div class="text-center">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">
            Discover Amazing Opportunities
        </h1>
        <p class="text-xl text-gray-600 max-w-2xl mx-auto">
            Search and analyze job opportunities from Torre.ai's network. Find the perfect match for your skills.
        </p>
    </div>

    <!-- Search Section -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="max-w-4xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                <input 
                    type="text" 
                    id="queryInput"
                    placeholder="Job title or company..."
                    class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                <input 
                    type="text" 
                    id="locationInput"
                    placeholder="Location..."
                    class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                <input 
                    type="text" 
                    id="skillsInput"
                    placeholder="Skills (comma separated)..."
                    class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                <div class="flex items-center space-x-4">
                    <label class="flex items-center">
                        <input type="checkbox" id="remoteCheck" class="rounded border-gray-300 text-blue-600">
                        <span class="ml-2 text-sm text-gray-700">Remote OK</span>
                    </label>
                    <button 
                        id="searchBtn"
                        class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors"
                    >
                        Search
                    </button>
                </div>
            </div>
            
            <div id="searchLoading" class="hidden mt-4 text-center">
                <div class="inline-flex items-center">
                    <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-600 mr-2"></div>
                    Searching opportunities...
                </div>
            </div>
        </div>
    </div>

    <!-- Results Section -->
    <div id="searchResults" class="hidden">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-gray-900">Job Opportunities</h2>
            <div id="resultsCount" class="text-gray-600"></div>
        </div>
        <div id="resultsGrid" class="space-y-4">
            <!-- Results will be populated here -->
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const queryInput = document.getElementById('queryInput');
    const locationInput = document.getElementById('locationInput');
    const skillsInput = document.getElementById('skillsInput');
    const remoteCheck = document.getElementById('remoteCheck');
    const searchBtn = document.getElementById('searchBtn');
    const searchLoading = document.getElementById('searchLoading');
    const searchResults = document.getElementById('searchResults');
    const resultsGrid = document.getElementById('resultsGrid');
    const resultsCount = document.getElementById('resultsCount');

    // Auto-load featured opportunities on page load
    loadFeaturedOpportunities();

    function loadFeaturedOpportunities() {
        searchLoading.classList.remove('hidden');
        searchResults.classList.add('hidden');

        axios.post('/api/opportunities/featured')
            .then(response => {
                if (response.data.success) {
                    displayResults(response.data.results, response.data.total);
                } else {
                    // Fallback to basic search
                    performSearch();
                }
            })
            .catch(error => {
                console.log('Featured opportunities not available, loading sample jobs');
                // Fallback to basic search
                performSearch();
            })
            .finally(() => {
                searchLoading.classList.add('hidden');
            });
    }

    function performSearch() {
        const query = queryInput.value.trim();
        const location = locationInput.value.trim();
        const skillsText = skillsInput.value.trim();
        const remote = remoteCheck.checked;
        
        const skills = skillsText ? skillsText.split(',').map(s => s.trim()).filter(s => s) : [];

        searchLoading.classList.remove('hidden');
        searchResults.classList.add('hidden');

        const payload = {};
        if (query) payload.query = query;
        if (location) payload.location = location;
        if (skills.length > 0) payload.skills = skills;
        if (remote) payload.remote = true;

        axios.post('/api/opportunities/search', payload)
            .then(response => {
                if (response.data.success) {
                    displayResults(response.data.results, response.data.total);
                } else {
                    alert('Search failed: ' + response.data.message);
                }
            })
            .catch(error => {
                console.error('Search error:', error);
                alert('Search failed. Please try again.');
            })
            .finally(() => {
                searchLoading.classList.add('hidden');
            });
    }

    function displayResults(results, total) {
        resultsGrid.innerHTML = '';
        resultsCount.textContent = `${total.toLocaleString()} opportunities found`;

        if (results.length === 0) {
            resultsGrid.innerHTML = '<div class="text-center text-gray-500 py-8">No opportunities found</div>';
        } else {
            results.forEach(result => {
                const card = createOpportunityCard(result);
                resultsGrid.appendChild(card);
            });
        }

        searchResults.classList.remove('hidden');
    }

    function createOpportunityCard(opportunity) {
        const card = document.createElement('div');
        card.className = 'bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow';
        
        const compensation = opportunity.compensation || {};
        const location = opportunity.locations?.[0] || {};
        
        card.innerHTML = `
            <div class="flex justify-between items-start mb-4">
                <div class="flex-1">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">${opportunity.objective || 'Untitled Position'}</h3>
                    ${opportunity.organizations?.[0]?.name ? `
                        <p class="text-lg text-blue-600 mb-2">${opportunity.organizations[0].name}</p>
                    ` : ''}
                    ${location.name ? `
                        <p class="text-sm text-gray-600 mb-2">📍 ${location.name}</p>
                    ` : ''}
                    ${opportunity.remote ? '<span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded mb-2">Remote</span>' : ''}
                </div>
                ${compensation.minAmount ? `
                    <div class="text-right">
                        <p class="text-lg font-semibold text-green-600">
                            $${compensation.minAmount.toLocaleString()}${compensation.maxAmount ? ` - $${compensation.maxAmount.toLocaleString()}` : '+'}
                        </p>
                        <p class="text-sm text-gray-500">${compensation.periodicity || 'per year'}</p>
                    </div>
                ` : ''}
            </div>
            
            ${opportunity.summary ? `
                <p class="text-gray-700 mb-4 line-clamp-3">${opportunity.summary}</p>
            ` : ''}
            
            ${opportunity.strengths?.length > 0 ? `
                <div class="mb-4">
                    <p class="text-sm font-medium text-gray-700 mb-2">Required Skills:</p>
                    <div class="flex flex-wrap gap-1">
                        ${opportunity.strengths.slice(0, 6).map(skill => `
                            <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">${skill.name}</span>
                        `).join('')}
                        ${opportunity.strengths.length > 6 ? `<span class="text-xs text-gray-500">+${opportunity.strengths.length - 6} more</span>` : ''}
                    </div>
                </div>
            ` : ''}
            
            <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                <div class="text-sm text-gray-500">
                    Posted ${opportunity.createdAt ? new Date(opportunity.createdAt).toLocaleDateString() : 'recently'}
                </div>
                <div class="flex space-x-2">
                    ${opportunity.id ? `
                        <a href="/opportunities/${opportunity.id}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            View Details →
                        </a>
                    ` : ''}
                    ${opportunity.externalUrl ? `
                        <a href="${opportunity.externalUrl}" target="_blank" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700 transition-colors">
                            Apply
                        </a>
                    ` : ''}
                </div>
            </div>
        `;

        return card;
    }

    // Event listeners
    searchBtn.addEventListener('click', performSearch);
    
    [queryInput, locationInput, skillsInput].forEach(input => {
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                performSearch();
            }
        });
    });
});
</script>
@endsection
