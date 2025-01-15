<?php

namespace App\Http\Controllers;

use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function index()
    {
        // $statusesPerDay = Status::selectRaw('DATE(created_at) as date, count(id) as count')
        //     ->groupBy('date')
        //     ->orderBy('date', 'desc')
        //     ->take(7)
        //     ->get();

        // $usersPerDay = User::selectRaw('DATE(created_at) as date, count(id) as count')
        //     ->groupBy('date')
        //     ->orderBy('date', 'desc')
        //     ->take(7)
        //     ->get();



        // Dummy data for testing
        $statusesPerDay = [
            ['date' => '2023-10-01', 'count' => 10],
            ['date' => '2023-10-02', 'count' => 15],
            ['date' => '2023-10-03', 'count' => 8],
            ['date' => '2023-10-04', 'count' => 12],
            ['date' => '2023-10-05', 'count' => 20],
            ['date' => '2023-10-06', 'count' => 5],
            ['date' => '2023-10-07', 'count' => 18],
            ['date' => '2023-10-08', 'count' => 22],
            ['date' => '2023-10-09', 'count' => 17],
            ['date' => '2023-10-10', 'count' => 14],
            ['date' => '2023-10-11', 'count' => 19],
            ['date' => '2023-10-12', 'count' => 13],
            ['date' => '2023-10-13', 'count' => 16],
            ['date' => '2023-10-14', 'count' => 21],
            ['date' => '2023-10-15', 'count' => 9],
            ['date' => '2023-10-16', 'count' => 11],
            ['date' => '2023-10-17', 'count' => 7],
            ['date' => '2023-10-18', 'count' => 6],
            ['date' => '2023-10-19', 'count' => 4],
            ['date' => '2023-10-20', 'count' => 3],
        ];

        $usersPerDay = [
            ['date' => '2023-10-01', 'count' => 5],
            ['date' => '2023-10-02', 'count' => 7],
            ['date' => '2023-10-03', 'count' => 3],
            ['date' => '2023-10-04', 'count' => 6],
            ['date' => '2023-10-05', 'count' => 10],
            ['date' => '2023-10-06', 'count' => 2],
            ['date' => '2023-10-07', 'count' => 8],
            ['date' => '2023-10-08', 'count' => 12],
            ['date' => '2023-10-09', 'count' => 9],
            ['date' => '2023-10-10', 'count' => 11],
            ['date' => '2023-10-11', 'count' => 4],
            ['date' => '2023-10-12', 'count' => 5],
            ['date' => '2023-10-13', 'count' => 6],
            ['date' => '2023-10-14', 'count' => 7],
            ['date' => '2023-10-15', 'count' => 8],
            ['date' => '2023-10-16', 'count' => 9],
            ['date' => '2023-10-17', 'count' => 10],
            ['date' => '2023-10-18', 'count' => 11],
            ['date' => '2023-10-19', 'count' => 12],
            ['date' => '2023-10-20', 'count' => 13],
        ];

        return response()->json([
            'statusesPerDay' => $statusesPerDay,
            'usersPerDay' => $usersPerDay
        ]);
    }
}
