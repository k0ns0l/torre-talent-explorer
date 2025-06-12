@extends('layouts.app')

@section('title', 'Search Jobs - Torre Explorer')

@section('content')
<div class="space-y-8">
    <div class="text-center">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">
            Discover Amazing Opportunities
        </h1>
        <p class="text-xl text-gray-600 max-w-2xl mx-auto">
            Search and analyze job opportunities from Torre.ai's network. Find the perfect match for your skills.
        </p>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="max-w-4xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                <input
                    type="text"
                    id="queryInput"
                    placeholder="Job title or company..."
                    class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <input
                    type="text"
                    id="locationInput"
                    placeholder="Location..."
                    class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <input
                    type="text"
                    id="skillsInput"
                    placeholder="Skills (comma separated)..."
                    class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <div class="flex items-center space-x-4">
                    <label class="flex items-center">
                        <input type="checkbox" id="remoteCheck" class="rounded border-gray-300 text-blue-600">
                        <span class="ml-2 text-sm text-gray-700">Remote</span>
                    </label>
                    <button
                        id="searchBtn"
                        class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
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

    <div id="searchResults" class="hidden">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-gray-900"><span id="resultsTitle">Job Opportunities</span></h2>
            <div id="resultsCount" class="text-gray-600 underline underline-dotted decoration-1 decoration-blue-300"></div>
        </div>
        <div id="resultsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
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
        const searchResultsSection = document.getElementById('searchResults');
        const resultsGrid = document.getElementById('resultsGrid');
        const resultsCount = document.getElementById('resultsCount');
        const resultsTitle = document.getElementById('resultsTitle');

        async function performSearch(isInitialLoad = false, size = 50) {
            const query = queryInput.value.trim();
            const location = locationInput.value.trim();
            const skillsText = skillsInput.value.trim();
            const remote = remoteCheck.checked;

            const hasSearchCriteria = query || location || skillsText || remote;

            if (isInitialLoad && !hasSearchCriteria) {
                loadFeaturedOpportunities(size);
                window.initialLoadPerformed = true;
                return;
            }

            searchLoading.classList.remove('hidden');
            searchResultsSection.classList.add('hidden');

            const payload = {
                "size": size
            };
            if (query) payload.query = query;
            if (location) payload.location = {
                term: location
            };
            if (skillsText) {
                payload.skills = skillsText;
            }
            if (remote) payload.remote = true;
            
            try {
                const response = await axios.post('/api/opportunities/search', payload, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                if (response.data.success) {
                    resultsTitle.textContent = 'Job Opportunities';
                    displayResults(response.data.results, response.data.total);
                } else {
                    alert('Search failed: ' + response.data.message);
                    resultsGrid.innerHTML = `<div class="text-center text-red-600 py-8 col-span-full">Error: ${response.data.message || 'Search failed. Please try again.'}</div>`;
                    resultsCount.textContent = 'No results';
                    searchResultsSection.classList.remove('hidden');
                }
            } catch (error) {
                console.error('Search error:', error);
                alert('Search failed. Please try again.');
                resultsGrid.innerHTML = '<div class="text-center text-red-600 py-8 col-span-full">An error occurred while fetching opportunities. Please try again.</div>';
                resultsCount.textContent = '0 results';
                searchResultsSection.classList.remove('hidden');
            } finally {
                searchLoading.classList.add('hidden');
            }
        }

        async function loadFeaturedOpportunities(requestedSize = 50) {
            searchLoading.classList.remove('hidden');
            searchResultsSection.classList.add('hidden');

            try {
                const payload = {
                    "size": requestedSize
                };

                const response = await axios.post('/api/opportunities/featured', {}, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                if (response.data.success) {
                    resultsTitle.textContent = 'Featured Opportunities';
                    displayResults(response.data.results, response.data.total);
                } else {
                    console.log('Featured opportunities not available, performing general search fallback.');
                    performSearch(false, requestedSize);
                }
            } catch (error) {
                console.error('Error loading featured opportunities:', error);
                console.log('Featured opportunities not available, performing general search fallback.');
                performSearch(false, requestedSize);
            } finally {
                searchLoading.classList.add('hidden');
            }
        }

        function displayResults(results, total) {
            resultsGrid.innerHTML = '';
            resultsCount.textContent = `${total.toLocaleString()} opportunities found`;

            const query = queryInput.value.trim();
            const location = locationInput.value.trim();
            const skillsText = skillsInput.value.trim();
            const remote = remoteCheck.checked;

            let titleText = 'Job Opportunities';
            const searchTerms = [];

            if (query) {
                searchTerms.push(`"${query}"`);
            }
            if (location) {
                searchTerms.push(`in "${location}"`);
            }
            if (skillsText) {
                searchTerms.push(`with skills like "${skillsText}"`);
            }
            if (remote) {
                searchTerms.push(`(Remote)`);
            }

            if (searchTerms.length > 0) {
                titleText = `Opportunities for ${searchTerms.join(' ')}`;
            } else {
                if (!initialLoadPerformed && total > 0) {
                    titleText = 'All Opportunities';
                }
            }
            resultsTitle.textContent = titleText;

            if (results.length === 0) {
                resultsGrid.innerHTML = '<div class="text-center text-gray-500 py-8 col-span-full">No opportunities found for your criteria.</div>';
            } else {
                results.forEach(opportunity => {
                    const card = createOpportunityCard(opportunity);
                    resultsGrid.appendChild(card);
                });
            }
            searchResultsSection.classList.remove('hidden');
        }

        function createOpportunityCard(opportunity) {
            const card = document.createElement('div');
            card.className = 'bg-white rounded-lg shadow p-5 flex flex-col h-full';

            const getNested = (obj, path, defaultValue = '') => {
                return path.split('.').reduce((acc, part) => acc && acc[part], obj) || defaultValue;
            };

            const organizationName = getNested(opportunity, 'organizations.0.name');
            const organizationPicture = getNested(opportunity, 'organizations.0.picture');
            const objective = opportunity.objective || 'Job Opportunity';
            const locationName = getNested(opportunity, 'locations.0.name');
            const remote = opportunity.remote;
            const type = opportunity.type || '';
            const tagline = opportunity.tagline || '';
            const minAmount = getNested(opportunity, 'compensation.data.minAmount', 0);
            const maxAmount = getNested(opportunity, 'compensation.data.maxAmount', 0);
            const periodicity = getNested(opportunity, 'compensation.data.periodicity', '');
            const skills = opportunity.skills || [];
            const members = opportunity.members || [];
            const opportunityId = opportunity.id;

            card.innerHTML = `
            <div class="flex items-center space-x-3 mb-3">
                ${organizationPicture ? `
                <img src="${organizationPicture}" alt="${organizationName || 'Company'}" class="w-12 h-12 rounded-full object-cover border">
                ` : `
                <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center text-gray-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"></path>
                    </svg>
                </div>
                `}
                <div>
                    <div class="font-semibold text-gray-900 text-lg">${objective}</div>
                    <div class="text-blue-600 text-sm">${organizationName}</div>
                </div>
            </div>
            <div class="flex flex-wrap gap-2 mb-2">
                ${locationName ? `
                <span class="text-gray-600 text-xs flex items-center">📍 ${locationName}</span>
                ` : ''}
                ${remote ? `
                <span class="bg-green-100 text-green-800 text-xs px-2 py-0.5 rounded">Remote</span>
                ` : ''}
                ${type ? `
                <span class="bg-blue-100 text-blue-800 text-xs px-2 py-0.5 rounded">${type}</span>
                ` : ''}
            </div>
            ${tagline ? `
            <div class="text-gray-700 text-sm mb-2">${tagline}</div>
            ` : ''}
            ${minAmount > 0 ? `
            <div class="text-green-700 font-semibold text-sm mb-2">
                $${minAmount.toLocaleString()}
                ${maxAmount > 0 ? `- $${maxAmount.toLocaleString()}` : ''}
                <span class="text-gray-500 font-normal">${periodicity}</span>
            </div>
            ` : ''}
            ${skills.length > 0 ? `
            <div class="flex flex-wrap gap-2 mb-2">
                ${skills.slice(0, 3).map(skill => `
                <span class="bg-gray-100 text-gray-700 text-xs px-2 py-0.5 rounded">${skill.name}</span>
                `).join('')}
                ${skills.length > 3 ? `
                <span class="text-xs text-gray-400">+${skills.length - 3} more</span>
                ` : ''}
            </div>
            ` : ''}
            ${members.length > 0 ? `
            <div class="flex -space-x-2 mt-auto mb-2">
                ${members.slice(0, 3).map(member => `
                ${member.picture ? `
                <img src="${member.picture}" alt="${member.name}" class="w-7 h-7 rounded-full border-2 border-white shadow">
                ` : `
                <div class="w-7 h-7 rounded-full bg-gray-200 border-2 border-white flex items-center justify-center text-xs text-gray-500 shadow">
                    ${member.name ? member.name.substring(0, 1).toUpperCase() : ''}
                </div>
                `}
                `).join('')}
                ${members.length > 3 ? `
                <span class="w-7 h-7 rounded-full bg-gray-100 border-2 border-white flex items-center justify-center text-xs text-gray-500 shadow">+${members.length - 3}</span>
                ` : ''}
            </div>
            ` : ''}
            <div class="mt-3">
                ${opportunityId ? `
                <a href="/opportunities/${opportunityId}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition text-sm font-medium">
                    View Details
                </a>
                ` : ''}
            </div>
            `;
            return card;
        }

        searchBtn.addEventListener('click', () => performSearch(false));

        [queryInput, locationInput, skillsInput].forEach(input => {
            input.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    performSearch(false);
                }
            });
        });

        const urlParams = new URLSearchParams(window.location.search);
        const hasUrlParams = Array.from(urlParams.keys()).length > 0;

        if (hasUrlParams) {
            queryInput.value = urlParams.get('query') || '';
            locationInput.value = urlParams.get('location') || '';
            skillsInput.value = urlParams.get('skills') || '';
            remoteCheck.checked = urlParams.get('remote') === 'true' || urlParams.get('remote') === '1';
            performSearch(false);
        } else {
            performSearch(true);
        }
    });
</script>
@endsection