<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Get the search term from the request
        $search = $request->input('search');

        // Query users based on search term for 'name' or 'city'
        if ($search) {
            $users = User::where('name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%')
                ->orWhere('city', 'like', '%' . $search . '%')
                ->get();
        } else {
            // If no search term, fetch all users
            $users = User::all();
        }

        return view('dashboard', compact('users'));
    }
}
