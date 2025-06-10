<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\SearchHistory;

class TalentController extends Controller
{
    public function index()
    {
        return view('talent.search');
    }

    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:2|max:100'
        ]);

        try {
            // Improved search with better keyword matching
            $searchQuery = $request->input('query');
            $response = Http::timeout(30)->post('https://search.torre.co/people/_search', [
                'query' => [
                    'and' => [
                        [
                            'or' => [
                                ['fuzzy' => ['name' => $searchQuery]],
                                ['fuzzy' => ['username' => $searchQuery]],
                                ['fuzzy' => ['professionalHeadline' => $searchQuery]],
                                ['match' => ['name' => $searchQuery]],
                                ['match' => ['professionalHeadline' => $searchQuery]],
                                ['wildcard' => ['name' => "*{$searchQuery}*"]],
                                ['wildcard' => ['professionalHeadline' => "*{$searchQuery}*"]]
                            ]
                        ]
                    ]
                ],
                'size' => 20,
                'offset' => 0
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $results = $data['results'] ?? [];
                
                // Track search history if user is authenticated
                if (Auth::check()) {
                    SearchHistory::create([
                        'user_id' => Auth::id(),
                        'query' => $searchQuery,
                        'results_count' => count($results),
                        'ip_address' => $request->ip()
                    ]);
                }
                
                return response()->json([
                    'success' => true,
                    'results' => $results,
                    'total' => $data['total'] ?? 0
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Search failed'
            ], 500);

        } catch (\Exception $e) {
            Log::error('Torre API search error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Search service unavailable'
            ], 500);
        }
    }

    public function profile($username)
    {
        try {
            $endpoints = [
                "https://torre.ai/api/bios/{$username}",
            ];

            $profile = null;
            foreach ($endpoints as $endpoint) {
                $response = Http::timeout(30)->get($endpoint);
                if ($response->successful()) {
                    $profile = $response->json();
                    break;
                }
            }

            if ($profile) {
                return view('talent.profile', compact('profile', 'username'));
            }
            
        } catch (\Exception $e) {
            Log::error('Torre API profile error: ' . $e->getMessage());
            return redirect()->route('talent.search')->with('error', 'Profile service unavailable');
        }
    }

    public function profileApi($username)
    {
        try {
            $response = Http::timeout(30)->get("https://torre.ai/api/bios/{$username}");

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'profile' => $response->json()
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Profile not found'
            ], 404);

        } catch (\Exception $e) {
            Log::error('Torre API profile error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Profile service unavailable'
            ], 500);
        }
    }

    public function featuredProfiles()
    {
        try {
            // Search for popular tech terms to get diverse profiles
            $searchTerms = ['developer', 'designer', 'manager', 'engineer', 'analyst'];
            $allResults = [];
            
            foreach ($searchTerms as $term) {
                $response = Http::timeout(30)->post('https://search.torre.co/people/_search', [
                    'query' => [
                        'and' => [
                            [
                                'or' => [
                                    ['fuzzy' => ['professionalHeadline' => $term]],
                                    ['match' => ['professionalHeadline' => $term]]
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
            $seenUsernames = [];
            
            foreach ($allResults as $result) {
                $username = $result['username'] ?? $result['ggId'] ?? null;
                if ($username && !in_array($username, $seenUsernames)) {
                    $seenUsernames[] = $username;
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
            Log::error('Torre API featured profiles error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Featured profiles unavailable'
            ], 500);
        }
    }
}
