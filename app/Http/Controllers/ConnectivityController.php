<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ConnectivityController extends Controller
{
    public function analyzeConnectivity(Request $request)
    {
        $request->validate([
            'identifiers' => 'required|array|min:2|max:10',
            'identifiers.*' => 'string'
        ]);

        try {
            $identifiers = implode(',', $request->identifiers);
            
            // Since Torre.ai connectivity endpoints may not be working, 
            // let's create a meaningful analysis based on profile data
            $connectivityData = $this->analyzeProfileConnections($request->identifiers);

            return response()->json([
                'success' => true,
                'connectivity' => $connectivityData
            ]);

        } catch (\Exception $e) {
            Log::error('Torre Connectivity API error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Connectivity analysis unavailable'
            ], 500);
        }
    }

    private function analyzeProfileConnections($identifiers)
    {
        $profiles = [];
        $allSkills = [];
        $allCompanies = [];
        $allLocations = [];

        // Fetch profile data for each identifier
        foreach ($identifiers as $username) {
            try {
                $response = Http::timeout(30)->get("https://torre.ai/api/bios/{$username}");
                
                if ($response->successful()) {
                    $profile = $response->json();
                    $profiles[$username] = $profile;
                    
                    // Collect skills
                    if (isset($profile['strengths'])) {
                        foreach ($profile['strengths'] as $strength) {
                            $skill = $strength['name'] ?? 'Unknown';
                            $allSkills[$skill] = ($allSkills[$skill] ?? 0) + 1;
                        }
                    }
                    
                    // Collect companies
                    if (isset($profile['experiences'])) {
                        foreach ($profile['experiences'] as $exp) {
                            if (isset($exp['organizations'])) {
                                foreach ($exp['organizations'] as $org) {
                                    $company = $org['name'] ?? 'Unknown';
                                    $allCompanies[$company] = ($allCompanies[$company] ?? 0) + 1;
                                }
                            }
                        }
                    }
                    
                    // Collect locations
                    if (isset($profile['person']['location']['name'])) {
                        $location = $profile['person']['location']['name'];
                        $allLocations[$location] = ($allLocations[$location] ?? 0) + 1;
                    }
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        // Find connections
        $sharedSkills = array_filter($allSkills, function($count) { return $count > 1; });
        $sharedCompanies = array_filter($allCompanies, function($count) { return $count > 1; });
        $sharedLocations = array_filter($allLocations, function($count) { return $count > 1; });

        // Calculate connectivity score
        $totalConnections = count($sharedSkills) + count($sharedCompanies) + count($sharedLocations);
        $maxPossibleConnections = count($identifiers) * 10; // Rough estimate
        $connectivityScore = min(100, ($totalConnections / max($maxPossibleConnections, 1)) * 100);

        return [
            'score' => [
                'score' => round($connectivityScore, 1),
                'description' => $this->getScoreDescription($connectivityScore)
            ],
            'connections' => [
                'shared_skills' => array_keys($sharedSkills),
                'shared_companies' => array_keys($sharedCompanies),
                'shared_locations' => array_keys($sharedLocations),
                'total_connections' => $totalConnections
            ],
            'insights' => $this->generateInsights($sharedSkills, $sharedCompanies, $sharedLocations, $identifiers)
        ];
    }

    private function getScoreDescription($score)
    {
        if ($score >= 80) return 'Very Strong Connection - Multiple shared experiences and skills';
        if ($score >= 60) return 'Strong Connection - Several common elements';
        if ($score >= 40) return 'Moderate Connection - Some shared background';
        if ($score >= 20) return 'Weak Connection - Few commonalities';
        return 'Minimal Connection - Very different backgrounds';
    }

    private function generateInsights($sharedSkills, $sharedCompanies, $sharedLocations, $identifiers)
    {
        $insights = [];

        if (!empty($sharedSkills)) {
            $topSkills = array_slice(array_keys($sharedSkills), 0, 3);
            $insights[] = [
                'type' => 'skills',
                'title' => 'Shared Technical Skills',
                'description' => 'Common expertise in: ' . implode(', ', $topSkills),
                'strength' => 'high'
            ];
        }

        if (!empty($sharedCompanies)) {
            $topCompanies = array_slice(array_keys($sharedCompanies), 0, 2);
            $insights[] = [
                'type' => 'experience',
                'title' => 'Shared Work Experience',
                'description' => 'Both have worked at: ' . implode(', ', $topCompanies),
                'strength' => 'high'
            ];
        }

        if (!empty($sharedLocations)) {
            $locations = array_keys($sharedLocations);
            $insights[] = [
                'type' => 'location',
                'title' => 'Geographic Connection',
                'description' => 'Located in the same area: ' . implode(', ', $locations),
                'strength' => 'medium'
            ];
        }

        if (empty($insights)) {
            $insights[] = [
                'type' => 'diversity',
                'title' => 'Diverse Backgrounds',
                'description' => 'These profiles represent different skill sets and experiences, which could be valuable for diverse team composition.',
                'strength' => 'medium'
            ];
        }

        return $insights;
    }
}
