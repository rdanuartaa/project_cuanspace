<?php
namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;

class TeamsController extends Controller
{
    // Show all teams for Admin
    public function index()
    {
        $teams = Team::all();
        return view('admin.teams.index', compact('teams'));
    }

    // Show teams for User (about or teams page)
    public function showUserTeams()
    {
        // Retrieve only the necessary team data for the user (limited fields)
        $teams = Team::select('id', 'name', 'role', 'image')->get();
    
        // Return a view that is intended for the user (teams page)
        return view('teams.show', compact('teams'));
    }

    // Show form to create a new team member (Admin)
    public function create()
    {
        return view('admin.teams.create');
    }

    // Store a new team member (Admin)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('teams', 'public');
        }

        Team::create([
            'name' => $request->name,
            'role' => $request->role,
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.teams.index')->with('success', 'Team created successfully.');
    }

    // Show form to edit a team member (Admin)
    public function edit(Team $team)
    {
        return view('admin.teams.edit', compact('team'));
    }

    // Update the team member (Admin)
    public function update(Request $request, Team $team)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = $team->image;
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($imagePath) {
                Storage::delete('public/' . $imagePath);
            }
            // Store the new image
            $imagePath = $request->file('image')->store('teams', 'public');
        }

        $team->update([
            'name' => $request->name,
            'role' => $request->role,
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.teams.index')->with('success', 'Team updated successfully.');
    }

    // Delete a team member (Admin)
    public function destroy(Team $team)
    {
        // Delete the image if it exists
        if ($team->image) {
            Storage::delete('public/' . $team->image);
        }

        $team->delete();

        return redirect()->route('admin.teams.index')->with('success', 'Team deleted successfully.');
    }
}
