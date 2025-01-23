<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TabContent;

class TabController extends Controller
{
    public function getContent($slug)
    {
        sleep(2);
        $tabsContent = TabContent::select('description', 'items')
            ->where('slug', $slug)
            ->first();

        if ($tabsContent) {
            return response()->json($tabsContent);
        } else {
            return response()->json(['error' => 'Tab not found.'], 404);
        }
    }
}
