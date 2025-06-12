<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpportunityController extends Controller
{
    private $torreJobSearchUrl = 'https://search.torre.co/opportunities/_search/';
    private $defaultPageSize = 50;


    public function index()
    {
        return view('opportunities.search');
    }


    public function show($id)
    {
        try {
            $searchPayload = [
                'query' => [
                    'id' => $id
                ],
                'size' => 1,
                'offset' => 0
            ];

            $response = Http::timeout(30)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => '*/*'
                ])
                ->post('https://search.torre.co/opportunities/_search', $searchPayload);

            if ($response->successful()) {
                $data = $response->json();
                $results = $data['results'] ?? [];

                if (!empty($results)) {
                    $opportunity = $results[0];
                    return view('opportunities.show', compact('opportunity', 'id'));
                }
            }

            Log::warning('Torre API opportunity not found', [
                'id' => $id,
                'status' => $response->status(),
                'response' => $response->body()
            ]);

            return redirect()->route('opportunities.search')->with('error', 'Opportunity not found');
        } catch (\Exception $e) {
            Log::error('Torre Opportunity API error: ' . $e->getMessage());
            return redirect()->route('opportunities.search')->with('error', 'Opportunity service unavailable');
        }
    }

    public function search(Request $request)
    {
        $payload = [
            "currency" => "USD",
            "page" => 0,
            "periodicity" => "hourly",
            "aggregate" => true,
        ];

        $payload['size'] = $request->input('size', $this->defaultPageSize);

        if ($request->filled('query')) {
            $payload['query'] = $request->input('query');
        }

        if ($request->has('location.term') && !empty($request->input('location.term'))) {
            $payload['location'] = [
                'term' => $request->input('location.term')
            ];
        }

        if ($request->filled('skills')) {
            $skills = array_filter(array_map('trim', explode(',', $request->input('skills'))));
            if (!empty($skills)) {
                $payload['skill/role'] = [
                    'text' => implode(' ', $skills),
                    'experience' => 'potential-to-develop'
                ];
            }
        }

        if ($request->has('remote')) {
            $payload['remote'] = ['term' => (bool)$request->input('remote')];
        }

        try {
            $response = Http::timeout(30)->post($this->torreJobSearchUrl, $payload);
            $data = $response->json();

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'results' => $data['results'] ?? [],
                    'total' => $data['total'] ?? 0
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $data['message'] ?? 'Failed to fetch opportunities from Torre API.'
                ], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An internal server error occurred while searching opportunities.'
            ], 500);
        }
    }

    public function featuredOpportunities(Request $request)
    {
        $payload = [
            "remote" => ["term" => true],
            "page" => 0,
            "aggregate" => true,
            "sort" => [
                "field" => "startDate",
                "direction" => "desc"
            ]
        ];

        $payloa21d['size'] = $request->input('size', $this->defaultPageSize);
        $payload['limit'] = $payload['size'];

        try {
            $response = Http::timeout(30)->post($this->torreJobSearchUrl, $payload);
            $data = $response->json();

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'results' => $data['results'] ?? [],
                    'total' => $data['total'] ?? 0
                ]);
            } else {
                Log::error('Torre API featured error', ['status' => $response->status(), 'response' => $data]);
                return response()->json([
                    'success' => false,
                    'message' => $data['message'] ?? 'Failed to fetch featured opportunities from Torre API.'
                ], $response->status());
            }
        } catch (\Exception $e) {
            Log::error('Exception during Torre API featured fetch', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'An internal server error occurred while fetching featured opportunities.'
            ], 500);
        }
    }
}
