<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TabContent;

class TabController extends Controller
{
    public function getContent($slug)
    {
        $tabsContent = TabContent::select('description', 'items')
            ->where('slug', $slug)
            ->first();

        if ($tabsContent) {
            return response()->json($tabsContent);
        } else {
            return response()->json(['error' => 'Tab not found.'], 404);
        }
    }

    public function getAll()
    {
        $tabsContent = TabContent::all();

        if ($tabsContent) {
            return response()->json($tabsContent);
        } else {
            return response()->json(['error' => 'Error fetching all tabs content'], 404);
        }
    }
}
