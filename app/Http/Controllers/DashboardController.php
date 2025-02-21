<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserDTO;
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

        $user = auth()->user();

        // Fetch all received and sent requests, regardless of status
        $receivedRequests = $user->receivedRequests()->with('sender')->get();
        $sentRequests = $user->sentRequests()->with('receiver')->get();

        return view('dashboard', compact('users', 'receivedRequests', 'sentRequests'));
    }
    public function show()
    {
        $user = User::find(5);
        $userDTO = UserDTO::fromModel($user);

        return response()->json($userDTO->toArray());
    }
}
