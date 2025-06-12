<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ExportController extends Controller
{
    public function exportProfiles(Request $request)
    {
        $request->validate([
            'profiles' => 'required|array',
            'format' => 'required|in:csv,json'
        ]);

        $profilesData = [];
        
        foreach ($request->profiles as $username) {
            try {
                $response = Http::timeout(30)->get("https://torre.ai/api/genome/bios/{$username}");
                
                if ($response->successful()) {
                    $profile = $response->json();
                    $profilesData[] = [
                        'username' => $username,
                        'name' => $profile['person']['name'] ?? 'Unknown',
                        'headline' => $profile['person']['professionalHeadline'] ?? '',
                        'location' => $profile['person']['location']['name'] ?? '',
                        'skills_count' => count($profile['strengths'] ?? []),
                        'experience_count' => count($profile['experiences'] ?? []),
                        'languages' => implode(', ', collect($profile['languages'] ?? [])->pluck('language')->toArray()),
                        'top_skills' => implode(', ', collect($profile['strengths'] ?? [])->take(5)->pluck('name')->toArray())
                    ];
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        if ($request->format === 'csv') {
            return $this->exportToCsv($profilesData);
        }

        return response()->json($profilesData)
            ->header('Content-Disposition', 'attachment; filename="profiles.json"');
    }

    private function exportToCsv($data)
    {
        $filename = 'torre_profiles_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            if (!empty($data)) {
                fputcsv($file, array_keys($data[0]));
                foreach ($data as $row) {
                    fputcsv($file, $row);
                }
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
