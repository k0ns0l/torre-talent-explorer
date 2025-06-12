@extends('layouts.app')

@section('title', ($opportunity['objective'] ?? 'Opportunity') . ' - Torre Explorer')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container mx-auto p-4 md:p-6 lg:p-8 font-inter">
    <div class="mb-6">
        <a href="{{ route('opportunities.search') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors duration-200 ease-in-out">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Search
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6 mb-8 border border-gray-100">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
            <div class="flex-1 mb-4 md:mb-0">
                <h1 class="text-3xl lg:text-4xl font-extrabold text-gray-900 leading-tight">
                    {{ $opportunity['objective'] ?? 'Job Opportunity' }}
                </h1>
                @if(isset($opportunity['tagline']))
                <p class="text-lg text-gray-700 mt-2 italic">{{ $opportunity['tagline'] }}</p>
                @endif

                @if(isset($opportunity['organizations'][0]['name']))
                <p class="text-xl text-blue-700 mt-2 hover:text-blue-900 transition-colors duration-200">
                    <a href="https://torre.ai/teams/{{ $opportunity['organizations'][0]['publicId'] ?? '#' }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center">
                        @if(isset($opportunity['organizations'][0]['picture']))
                        <img src="{{ $opportunity['organizations'][0]['picture'] }}" alt="{{ $opportunity['organizations'][0]['name'] }}" class="w-8 h-8 rounded-full mr-2 object-cover">
                        @endif
                        {{ $opportunity['organizations'][0]['name'] }}
                    </a>
                </p>
                @endif

                <div class="flex flex-wrap gap-3 mt-4 text-sm text-gray-600">
                    @if(isset($opportunity['locations']) && count($opportunity['locations']) > 0)
                    <span class="flex items-center bg-gray-100 px-3 py-1 rounded-full">
                        <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.828 0L6.343 16.657m11.314-11.314L12 3.686l-5.657 5.657m11.314 0L21 12l-5.657 5.657M4 12l5.657-5.657m0 0a5 5 0 117.071 7.071L12 21.071l-5.657-5.657a5 5 0 117.071-7.071z"></path>
                        </svg>
                        {{ implode(', ', $opportunity['locations']) }}
                    </span>
                    @endif

                    @if(isset($opportunity['remote']) && $opportunity['remote'])
                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1l-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Remote
                    </span>
                    @endif

                    @if(isset($opportunity['place']['anywhere']) && $opportunity['place']['anywhere'])
                    <span class="bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2h7a2 2 0 002-2v-1a2 2 0 012-2h2.945M22 7H2m18 10H4M3 4l6.41 11.066C10.45 16.63 11.96 17 12 17s1.55-.37 2.59-1.934L21 4"></path>
                        </svg>
                        Work from Anywhere
                    </span>
                    @endif

                    @if(isset($opportunity['type']))
                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full flex items-center capitalize">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.55 23.55 0 0112 15c-1.637 0-3.245-.04-4.855-.115F2 13V3a2 2 0 012-2h16a2 2 0 012 2v10.255z"></path>
                        </svg>
                        {{ str_replace('-', ' ', $opportunity['type']) }}
                    </span>
                    @endif

                    @if(isset($opportunity['creationDate']))
                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full flex items-center capitalize">
                        <svg class="w-6 h-6 text-gray-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h.01M7 16h.01M17 16h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ \Carbon\Carbon::parse($opportunity['creationDate'])->diffForHumans() }}
                    </span>
                    @else
                    <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Posted Recently
                    </span>
                    @endif

                    @if(isset($opportunity['status']) && $opportunity['status'] == 'open')
                    <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Actively Hiring
                    </span>
                    @elseif(isset($opportunity['status']) && $opportunity['status'] == 'closed')
                    <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2A9 9 0 111 12a9 9 0 0118 0z"></path>
                        </svg>
                        Closed
                    </span>
                    @endif

                    @if(isset($opportunity['commitment']))
                    <span class="bg-gray-200 text-gray-800 px-3 py-1 rounded-full flex items-center capitalize">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        {{ str_replace('-', ' ', $opportunity['commitment']) }}
                    </span>
                    @endif
                </div>
            </div>

            @if(isset($opportunity['compensation']['data']))
            <div class="text-right flex-shrink-0 mt-4 md:mt-0">
                @php
                $compensationData = $opportunity['compensation']['data'];
                $minAmount = $compensationData['minAmount'] ?? 0;
                $maxAmount = $compensationData['maxAmount'] ?? 0;
                $currency = $compensationData['currency'] ?? '';
                $periodicity = $compensationData['periodicity'] ?? 'per year';
                $code = $compensationData['code'] ?? '';
                $negotiable = $compensationData['negotiable'] ?? false;
                @endphp

                @if($minAmount > 0 || $maxAmount > 0)
                <p class="text-3xl font-bold text-green-600">
                    {{-- Display currency symbol if available, otherwise default to '$' --}}
                    {{ $currency === 'USD' ? '$' : ($currency ?? '') }}
                    {{ number_format($minAmount) }}
                    @if($maxAmount > 0 && $maxAmount !== $minAmount) {{-- Only show range if max is different and positive --}}
                    - {{ $currency === 'USD' ? '$' : ($currency ?? '') }}
                    {{ number_format($maxAmount) }}
                    @elseif($minAmount > 0 && $maxAmount === 0) {{-- If only min is set, show it with a '+' --}}
                    +
                    @endif
                </p>
                {{-- Display periodicity, capitalize the first letter --}}
                <p class="text-sm text-gray-500 capitalize">{{ $periodicity }}</p>

                {{-- Display explicit currency code if not USD and it's set --}}
                @if($currency && $currency !== 'USD')
                <p class="text-xs text-gray-500">{{ $currency }}</p>
                @endif

                @elseif($code === 'to-be-agreed')
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V9m0 3v2m0 3.5V21M19 14H5a2 2 0 01-2-2V7a2 2 0 012-2h14a2 2 0 012 2v5a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-xl font-semibold text-gray-600">Compensation: To Be Agreed</p>
                </div>
                @else
                <p class="text-xl font-semibold text-gray-600">Compensation Details Not Specified</p>
                @endif

                @if($negotiable)
                <p class="text-sm text-gray-500 italic mt-1">Negotiable</p>
                @endif
            </div>
            @endif
        </div>
    </div>

    <hr class="my-8 border-gray-200">

    @if(isset($opportunity['summary']) || isset($opportunity['details']))
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8 border border-gray-100">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Job Description</h2>

        @if(isset($opportunity['summary']))
        <div class="mb-6">
            <h3 class="font-semibold text-gray-900 mb-2 text-lg">Summary</h3>
            <p class="text-gray-700 leading-relaxed">{{ $opportunity['summary'] }}</p>
        </div>
        @endif

        @if(isset($opportunity['details']))
        <div>
            <h3 class="font-semibold text-gray-900 mb-2 text-lg">Details</h3>
            <div class="prose max-w-none text-gray-700 leading-relaxed">
                {!! nl2br(e($opportunity['details'])) !!}
            </div>
        </div>
        @endif
    </div>
    @endif

    @if(isset($opportunity['additionalCompensation']) && count($opportunity['additionalCompensation']) > 0)
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8 border border-gray-100">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Additional Compensation</h2>
        <div class="space-y-2">
            @foreach($opportunity['additionalCompensation'] as $compType)
            <div class="flex items-center text-gray-700">
                <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span class="capitalize font-medium">{{ str_replace('-', ' ', $compType) }}</span>
                @if(isset($opportunity['additionalCompensationDetails'][$compType]))
                <span class="ml-2 text-gray-600">({{ $opportunity['additionalCompensationDetails'][$compType] }})</span>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif
    <div class="bg-white rounded-xl shadow-lg mb-8 border border-gray-100 overflow-hidden group">
        <!-- Enhanced Header with Better Visual -->
        <button
            class="w-full text-left p-6 flex justify-between items-center bg-gradient-to-r from-gray-50 to-gray-100 hover:from-blue-50 hover:to-blue-100 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 group-hover:shadow-sm"
            onclick="toggleVideoSection()"
            aria-expanded="false"
            aria-controls="videoSectionContent"
            id="videoToggleButton">
            <div class="flex items-center gap-3">
                <!-- Video Icon -->
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition-colors duration-300">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1.01M15 10h1.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11l-3-3-3 3"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 mb-1">Opportunity Video</h2>
                    <p class="text-sm text-gray-500">Click to watch the presentation</p>
                </div>
            </div>

            <!-- Enhanced Toggle Icon -->
            <div class="flex items-center gap-2">
                <span id="toggleText" class="text-sm font-medium text-gray-600 hidden sm:block">Show</span>
                <svg
                    id="videoToggleIcon"
                    class="w-5 h-5 text-gray-600 transform transition-all duration-300 ease-in-out"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </div>
        </button>

        <!-- Enhanced Content Area -->
        <div
            id="videoSectionContent"
            class="transition-all duration-500 ease-in-out overflow-hidden bg-gray-50"
            style="max-height: 0px; opacity: 0;">
            <div class="p-6">
                <!-- Video Container with loading State -->
                <div class="relative bg-black rounded-lg overflow-hidden shadow-lg" style="padding-bottom: 56.25%;">
                    <!-- Loading -->
                    <div id="videoLoader" class="absolute inset-0 flex items-center justify-center bg-gray-900">
                        <div class="text-center text-white">
                            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-white mx-auto mb-4"></div>
                            <p class="text-sm">Loading video...</p>
                        </div>
                    </div>

                    <!-- Enhanced Video Player -->
                    <video
                        id="opportunityVideo"
                        class="absolute top-0 left-0 w-full h-full object-cover"
                        controls
                        preload="metadata"
                        poster=""
                        onloadstart="showVideoLoader()"
                        oncanplay="hideVideoLoader()"
                        onerror="handleVideoError()">
                        <source src="{{ $opportunity['videoUrl'] }}" type="video/mp4">
                        <source src="{{ $opportunity['videoUrl'] }}" type="video/webm">
                        <source src="{{ $opportunity['videoUrl'] }}" type="video/ogg">
                        <p class="text-white p-4">
                            Your browser does not support the video tag!
                            <a href="{{ $opportunity['videoUrl'] }}" class="text-blue-300 underline" target="_blank">
                                Download the video instead
                            </a>
                        </p>
                    </video>
                </div>

                <!-- Video Controls & Info -->
                <div class="mt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Use fullscreen for the best viewing experience</span>
                    </div>

                    <div class="flex items-center gap-2">
                        <button
                            onclick="toggleFullscreen()"
                            class="px-3 py-1 text-sm bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 transition-colors duration-200 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                            </svg>
                            Fullscreen
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleVideoSection() {
            const content = document.getElementById('videoSectionContent');
            const icon = document.getElementById('videoToggleIcon');
            const button = document.getElementById('videoToggleButton');
            const toggleText = document.getElementById('toggleText');
            const isExpanded = button.getAttribute('aria-expanded') === 'true';

            if (isExpanded) {
                // Collapse
                content.style.maxHeight = '0px';
                content.style.opacity = '0';
                icon.style.transform = 'rotate(0deg)';
                button.setAttribute('aria-expanded', 'false');
                if (toggleText) toggleText.textContent = 'Show';

                // Pause video when collapsed
                const video = document.getElementById('opportunityVideo');
                if (video && !video.paused) {
                    video.pause();
                }
            } else {
                // Expand
                content.style.maxHeight = content.scrollHeight + 'px';
                content.style.opacity = '1';
                icon.style.transform = 'rotate(180deg)';
                button.setAttribute('aria-expanded', 'true');
                if (toggleText) toggleText.textContent = 'Hide';
            }
        }

        function showVideoLoader() {
            document.getElementById('videoLoader').style.display = 'flex';
        }

        function hideVideoLoader() {
            document.getElementById('videoLoader').style.display = 'none';
        }

        function handleVideoError() {
            const loader = document.getElementById('videoLoader');
            loader.innerHTML = `
        <div class="text-center text-white">
            <svg class="w-12 h-12 mx-auto mb-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-sm">Failed to load video</p>
            <a href="{{ $opportunity['videoUrl'] }}" class="text-blue-300 underline text-sm mt-2 inline-block" target="_blank">
                Try opening directly
            </a>
        </div>
    `;
        }

        function toggleFullscreen() {
            const video = document.getElementById('opportunityVideo');
            if (video.requestFullscreen) {
                video.requestFullscreen();
            } else if (video.webkitRequestFullscreen) {
                video.webkitRequestFullscreen();
            } else if (video.msRequestFullscreen) {
                video.msRequestFullscreen();
            }
        }

        // Auto-expand on page load if there's a hash
        if (window.location.hash === '#video') {
            setTimeout(() => {
                const button = document.getElementById('videoToggleButton');
                if (button && button.getAttribute('aria-expanded') === 'false') {
                    toggleVideoSection();
                }
            }, 100);
        }
    </script>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        @if(isset($opportunity['skills']) && count($opportunity['skills']) > 0)
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100 lg:col-span-2">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Required Skills</h2>
            <div class="space-y-4">
                @foreach($opportunity['skills'] as $skill)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <div>
                        <h3 class="font-medium text-gray-900 text-lg">{{ $skill['name'] ?? 'Unknown Skill' }}</h3>
                        @if(isset($skill['experience']))
                        <p class="text-sm text-gray-600 mt-1">Experience: <span class="font-semibold capitalize">{{ str_replace('-', ' ', $skill['experience']) }}</span></p>
                        @endif
                        @if(isset($skill['proficiency']))
                        <p class="text-sm text-gray-600 mt-1">Proficiency: <span class="font-semibold capitalize">{{ str_replace('-', ' ', $skill['proficiency']) }}</span></p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @if(isset($opportunity['organizations'][0]))
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Company Information</h2>
            <div class="space-y-4">
                @php $org = $opportunity['organizations'][0]; @endphp

                <div class="flex items-center mb-4">
                    @if(isset($org['picture']))
                    <img src="{{ $org['picture'] }}" alt="{{ $org['name'] }}" class="w-16 h-16 rounded-full mr-4 object-cover border border-gray-200">
                    @endif
                    <div>
                        <h3 class="font-bold text-gray-900 text-xl">{{ $org['name'] ?? 'Unknown Company' }}</h3>
                        @if(isset($org['website']))
                        <p class="text-blue-600 text-sm hover:underline mt-1">
                            <a href="{{ $org['website'] }}" target="_blank" rel="noopener noreferrer">Visit Website</a>
                        </p>
                        @endif
                    </div>
                </div>

                @if(isset($org['description']))
                <p class="text-gray-700 leading-relaxed">{{ $org['description'] }}</p>
                @endif

                @if(isset($org['size']))
                <div>
                    <span class="text-base font-medium text-gray-700">Company Size:</span>
                    <span class="text-base text-gray-600 ml-2">{{ $org['size'] }} employees</span>
                </div>
                @endif

                @if(isset($org['industry']))
                <div>
                    <span class="text-base font-medium text-gray-700">Industry:</span>
                    <span class="text-base text-gray-600 ml-2">{{ $org['industry'] }}</span>
                </div>
                @endif

                @if(isset($org['publicId']))
                <div>
                    <span class="text-base font-medium text-gray-700">Organization Profile:</span>
                    <a href="https://torre.ai/teams/{{ $org['publicId'] }}" target="_blank" rel="noopener noreferrer" class="text-blue-600 ml-2 hover:underline">View Profile</a>
                </div>
                @endif

            </div>
        </div>
        @endif
    </div>

    <hr class="my-8 border-gray-200">

    <div class="bg-white rounded-xl shadow-lg p-6 mb-8 border border-gray-100">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">More Details & Context</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-700">
            @if(isset($opportunity['id']))
            <div>
                <span class="font-semibold">Opportunity ID:</span> {{ $opportunity['id'] }}
            </div>
            @endif
            @if(isset($opportunity['commitment']))
            <div>
                <span class="font-semibold">Commitment:</span> <span class="capitalize">{{ str_replace('-', ' ', $opportunity['commitment']) }}</span>
            </div>
            @endif
            @if(isset($opportunity['status']))
            <div>
                <span class="font-semibold">Status:</span> <span class="capitalize">{{ $opportunity['status'] }}</span>
            </div>
            @endif
            @if(isset($opportunity['creators'][0]['name']))
            <div>
                <span class="font-semibold">Posted by:</span>
                @php
                $creator = $opportunity['creators'][0];
                @endphp
                <a href="https://torre.ai/p/{{ $creator['publicId'] ?? '#' }}" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:underline">
                    {{ $creator['name'] }}
                </a>
                @if(isset($creator['picture']))
                <img src="{{ $creator['picture'] }}" alt="{{ $creator['name'] }}" class="w-8 h-8 rounded-full inline-block ml-2 object-cover">
                @endif
            </div>
            @endif
            @if(isset($opportunity['languages']) && count($opportunity['languages']) > 0)
            <div>
                <span class="font-semibold">Languages:</span>
                <ul class="list-disc list-inside mt-1 ml-4">
                    @foreach($opportunity['languages'] as $lang)
                    <li>{{ $lang['language'] ?? 'N/A' }} ({{ $lang['fluency'] ?? 'N/A' }})</li>
                    @endforeach
                </ul>
            </div>
            @endif
            @if(isset($opportunity['timezones']) && count($opportunity['timezones']) > 0)
            <div>
                <span class="font-semibold">Timezones:</span>
                <ul class="list-disc list-inside mt-1 ml-4">
                    @foreach($opportunity['timezones'] as $timezone)
                    <li>{{ $timezone }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            @if(isset($opportunity['deadline']))
            <div>
                <span class="font-semibold">Application Deadline:</span> {{ date('F j, Y', strtotime($opportunity['deadline'])) }}
            </div>
            @endif
            @if(isset($opportunity['created']))
            <div>
                <span class="font-semibold">Posted On:</span> {{ date('F j, Y', strtotime($opportunity['created'])) }}
            </div>
            @endif
        </div>
    </div>

    @if(isset($opportunity['members']) && count($opportunity['members']) > 0)
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8 border border-gray-100">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Team Members</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($opportunity['members'] as $member)
            <div class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-200">
                @if(isset($member['picture']))
                <img src="{{ $member['picture'] }}" alt="{{ $member['name'] ?? 'Team Member' }}" class="w-16 h-16 rounded-full mr-4 object-cover border-2 border-blue-200">
                @else
                <div class="w-16 h-16 rounded-full mr-4 bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-xl">{{ mb_substr($member['name'] ?? '?', 0, 1) }}</div>
                @endif
                <div class="flex-1">
                    <h3 class="font-bold text-gray-900 text-lg">
                        <a href="{{ config('app.url') }}/profile/{{ $member['username'] ?? '#' }}" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:underline">
                            {{ $member['name'] ?? 'Unknown Member' }}
                        </a>
                    </h3>
                    @if(isset($member['professionalHeadline']))
                    <p class="text-sm text-gray-500 italic mt-1">{{ $member['professionalHeadline'] }}</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <hr class="my-8 border-gray-200">

    <div class="bg-white rounded-xl shadow-lg p-6 mb-8 border border-gray-100 flex flex-col md:flex-row justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Apply for this Position</h2>
            <p class="text-gray-600 mt-1">Ready to take the next step in your career? Apply now!</p>
        </div>

        <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4 mt-6 md:mt-0">
            <a href="https://torre.ai/_a/your-bio" target="_blank" rel="noopener noreferrer" class="w-full sm:w-auto bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition-colors duration-200 text-center font-semibold shadow-md">
                Apply Now
            </a>

            @auth
            <button id="favoriteBtn" onclick="toggleFavorite()" class="w-full sm:w-auto flex items-center justify-center space-x-2 px-8 py-3 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition-colors duration-200 font-semibold shadow-sm">
                <svg id="favoriteIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
                <span id="favoriteText">Add to Favorites</span>
            </button>
            @else
            <a href="{{ route('login') }}" class="w-full sm:w-auto flex items-center justify-center space-x-2 px-8 py-3 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition-colors duration-200 font-semibold shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
                <span>Login to Favorite</span>
            </a>
            @endauth
        </div>

        @if(isset($opportunity['createdAt']))
        <div class="mt-6 pt-4 border-t border-gray-100 text-center md:text-left w-full">
            <p class="text-sm text-gray-500">
                Posted on {{ date('F j, Y', strtotime($opportunity['createdAt'])) }}
                @if(isset($opportunity['deadline']))
                <span class="ml-4">Application Deadline: {{ date('F j, Y', strtotime($opportunity['deadline'])) }}</span>
                @endif
            </p>
        </div>
        @endif
    </div>
</div>

<script>
    @auth
    let isFavorite = false;

    document.addEventListener('DOMContentLoaded', function() {
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
            axios.delete('/api/favorites/{{ $id }}', {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => {
                    if (response.data.success) {
                        isFavorite = false;
                        updateFavoriteButton();
                        alert('Opportunity removed from favorites!');
                    }
                })
                .catch(error => {
                    console.error('Error removing favorite:', error);
                    alert('Error removing from favorites. Please try again.');
                })
                .finally(() => {
                    btn.disabled = false;
                });
        } else {
            const opportunityData = {
                type: 'opportunity',
                opportunity_id: '{{ $id }}',
                name: @json($opportunity["objective"] ?? "Job Opportunity"),
                summary: @json($opportunity["summary"] ?? ""),
                company_name: @json($opportunity["organizations"][0]["name"] ?? ""),
                location: @json($opportunity["locations"][0]["name"] ?? ""),
                min_salary: @json($opportunity['compensation']['data']['minAmount'] ?? null),
                max_salary: @json($opportunity['compensation']['data']['maxAmount'] ?? null)
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
                        alert('Opportunity added to favorites!');
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
            btn.classList.remove('border-gray-300', 'text-gray-700', 'hover:bg-gray-100');
            btn.classList.add('bg-red-500', 'text-white', 'hover:bg-red-600');
            icon.setAttribute('fill', 'currentColor');
            text.textContent = 'Remove from Favorites';
        } else {
            btn.classList.remove('bg-red-500', 'text-white', 'hover:bg-red-600');
            btn.classList.add('border-gray-300', 'text-gray-700', 'hover:bg-gray-100');
            icon.setAttribute('fill', 'none');
            text.textContent = 'Add to Favorites';
        }
    }
    @endauth
</script>
@endsection