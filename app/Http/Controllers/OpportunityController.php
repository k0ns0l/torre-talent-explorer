<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpportunityController extends Controller
{
    public function index()
    {
        return view('opportunities.search');
    }

    public function search(Request $request)
    {
        $request->validate([
            'query' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:100',
            'remote' => 'nullable|boolean',
            'skills' => 'nullable|array',
            'skills.*' => 'string|max:50'
        ]);

        try {
            $searchPayload = [
                'size' => 20,
                'offset' => 0
            ];

            // Improved search with better keyword matching
            if ($request->filled('query')) {
                $searchQuery = $request->input('query');
                $searchPayload['query'] = [
                    'and' => [
                        [
                            'or' => [
                                ['fuzzy' => ['objective' => $searchQuery]],
                                ['match' => ['objective' => $searchQuery]],
                                ['wildcard' => ['objective' => "*{$searchQuery}*"]],
                                ['fuzzy' => ['organizations.name' => $searchQuery]],
                                ['match' => ['organizations.name' => $searchQuery]]
                            ]
                        ]
                    ]
                ];
            }

            // Add location filter
            if ($request->filled('location')) {
                $searchPayload['location'] = [
                    'name' => $request->location
                ];
            }

            // Add remote filter
            if ($request->has('remote')) {
                $searchPayload['remote'] = $request->boolean('remote');
            }

            // Add skills filter
            if ($request->filled('skills')) {
                $searchPayload['skill'] = array_map(function($skill) {
                    return ['name' => $skill];
                }, $request->skills);
            }

            $response = Http::timeout(30)->post('https://search.torre.co/opportunities/_search', $searchPayload);

            if ($response->successful()) {
                $data = $response->json();
                return response()->json([
                    'success' => true,
                    'results' => $data['results'] ?? [],
                    'total' => $data['total'] ?? 0
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Search failed'
            ], 500);

        } catch (\Exception $e) {
            Log::error('Torre Opportunities API error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Search service unavailable'
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            // Try multiple Torre API endpoints for opportunity details
            $endpoints = [
                "https://torre.ai/api/opportunities/{$id}",
                "https://torre.co/api/opportunities/{$id}",
                "https://api.torre.co/opportunities/{$id}"
            ];

            $opportunity = null;
            foreach ($endpoints as $endpoint) {
                $response = Http::timeout(30)->get($endpoint);
                if ($response->successful()) {
                    $opportunity = $response->json();
                    break;
                }
            }

            if ($opportunity) {
                return view('opportunities.show', compact('opportunity', 'id'));
            }

            // If API fails, create a mock opportunity for demonstration
            $mockCompanies = ['TechFlow Solutions', 'InnovateHub Inc', 'CodeCraft Studios', 'DataStream Systems', 'CloudBridge Technologies'];
            $randomCompany = $mockCompanies[array_rand($mockCompanies)];
            
            $opportunity = [
                'id' => $id,
                'objective' => 'Software Developer Position',
                'summary' => 'This opportunity is currently not available through the Torre.ai API. This is a demonstration of how the opportunity detail page would look.',
                'organizations' => [
                    [
                        'name' => $randomCompany,
                        'description' => 'A technology company focused on innovation and cutting-edge solutions.'
                    ]
                ],
                'locations' => [
                    ['name' => 'Remote']
                ],
                'remote' => true,
                'compensation' => [
                    'minAmount' => 50000,
                    'maxAmount' => 80000,
                    'periodicity' => 'per year'
                ],
                'strengths' => [
                    ['name' => 'JavaScript', 'weight' => 0.9],
                    ['name' => 'React', 'weight' => 0.8],
                    ['name' => 'Node.js', 'weight' => 0.7]
                ],
                'createdAt' => now()->toISOString(),
                'externalUrl' => 'https://torre.ai'
            ];

            return view('opportunities.show', compact('opportunity', 'id'));

        } catch (\Exception $e) {
            Log::error('Torre Opportunity API error: ' . $e->getMessage());
            return redirect()->route('opportunities.search')->with('error', 'Opportunity service unavailable');
        }
    }

    public function getMatches($username)
    {
        try {
            // Fix: Use the correct Torre API endpoint for matches
            $response = Http::timeout(30)->get("https://torre.ai/api/opportunities/_search", [
                'query' => [
                    'person' => ['ggId' => $username]
                ],
                'limit' => 10
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return response()->json([
                    'success' => true,
                    'matches' => $data['results'] ?? []
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No matches found'
            ], 404);

        } catch (\Exception $e) {
            Log::error('Torre Matches API error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Matches service unavailable'
            ], 500);
        }
    }

    public function featuredOpportunities()
    {
        try {
            // Search for popular job types to get diverse opportunities
            $jobTypes = ['developer', 'designer', 'manager', 'engineer', 'analyst', 'remote'];
            $allResults = [];
            
            foreach ($jobTypes as $type) {
                $response = Http::timeout(30)->post('https://search.torre.co/opportunities/_search', [
                    'query' => [
                        'and' => [
                            [
                                'or' => [
                                    ['fuzzy' => ['objective' => $type]],
                                    ['match' => ['objective' => $type]]
                                ]
                            ]
                        ]
                    ],
                    'size' => 4,
                    'offset' => 0
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $results = $data['results'] ?? [];
                    $allResults = array_merge($allResults, $results);
                }
            }

            // Remove duplicates and limit to 20
            $uniqueResults = [];
            $seenIds = [];
            
            foreach ($allResults as $result) {
                $id = $result['id'] ?? null;
                if ($id && !in_array($id, $seenIds)) {
                    $seenIds[] = $id;
                    $uniqueResults[] = $result;
                    if (count($uniqueResults) >= 20) break;
                }
            }

            return response()->json([
                'success' => true,
                'results' => $uniqueResults,
                'total' => count($uniqueResults)
            ]);

        } catch (\Exception $e) {
            Log::error('Torre API featured opportunities error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Featured opportunities unavailable'
            ], 500);
        }
    }
}
