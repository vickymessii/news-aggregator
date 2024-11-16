<?php

namespace App\Http\Controllers;

use App\Models\Preference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreferenceController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'sources' => 'nullable|array',
            'categories' => 'nullable|array',
            'authors' => 'nullable|array',
        ]);

        $user = Auth::user();

        $preference = Preference::updateOrCreate(
            ['user_id' => $user->id],
            [
                'sources' => $request->input('sources'),
                'categories' => $request->input('categories'),
                'authors' => $request->input('authors'),
            ]
        );

        return response()->json($preference, 201);
    }

    public function show()
    {
        $user = Auth::user();
        $preference = Preference::where('user_id', $user->id)->firstOrFail();
        return response()->json($preference);
    }
}
