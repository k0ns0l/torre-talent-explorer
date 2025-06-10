<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function index()
    {
        $favorites = Auth::user()->favorites()->latest()->get();
        return view('favorites.index', compact('favorites'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:profile,opportunity',
            // Profile fields
            'username' => 'required_if:type,profile|string',
            'name' => 'required|string',
            'professional_headline' => 'nullable|string',
            'location' => 'nullable|string',
            'picture_url' => 'nullable|string',
            // Opportunity fields
            'opportunity_id' => 'required_if:type,opportunity|string',
            'summary' => 'nullable|string',
            'company_name' => 'nullable|string',
            'min_salary' => 'nullable|numeric',
            'max_salary' => 'nullable|numeric',
        ]);

        $data = $request->only([
            'username', 'name', 'professional_headline', 'location', 'picture_url',
            'opportunity_id', 'type', 'summary', 'company_name', 'min_salary', 'max_salary'
        ]);

        // Set default type if not provided
        if (!isset($data['type'])) {
            $data['type'] = $request->has('username') ? 'profile' : 'opportunity';
        }

        if ($data['type'] === 'profile') {
            $favorite = Auth::user()->favorites()->updateOrCreate(
                ['username' => $request->username, 'type' => 'profile'],
                $data
            );
        } else {
            $favorite = Auth::user()->favorites()->updateOrCreate(
                ['opportunity_id' => $request->opportunity_id, 'type' => 'opportunity'],
                $data
            );
        }

        return response()->json([
            'success' => true,
            'message' => ucfirst($data['type']) . ' added to favorites',
            'favorite' => $favorite
        ]);
    }

    public function destroy($identifier)
    {
        // Try to find by username first (profile), then by opportunity_id
        $deleted = Auth::user()->favorites()
            ->where(function($query) use ($identifier) {
                $query->where('username', $identifier)
                      ->orWhere('opportunity_id', $identifier);
            })
            ->delete();

        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Removed from favorites'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Favorite not found'
        ], 404);
    }

    public function check($identifier)
    {
        $isFavorite = Auth::user()->favorites()
            ->where(function($query) use ($identifier) {
                $query->where('username', $identifier)
                      ->orWhere('opportunity_id', $identifier);
            })
            ->exists();

        return response()->json([
            'is_favorite' => $isFavorite
        ]);
    }
}
