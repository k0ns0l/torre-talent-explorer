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
        </div>
    </div>

    <!-- Comparison Section -->
    <div id="comparisonSection" class="hidden card p-8 animate-slide-in-right">
        <h3 class="text-2xl font-bold gradient-text mb-6">Profile Comparison</h3>
        <div id="comparisonContent">
        </div>
    </div>

    <!-- Connectivity Section -->
    <div id="connectivitySection" class="hidden card p-8 animate-slide-in-right">
        <h3 class="text-2xl font-bold gradient-text mb-6">Network Connectivity Analysis</h3>
        <div id="connectivityContent">
        </div>
    </div>
</div>
@endsection
