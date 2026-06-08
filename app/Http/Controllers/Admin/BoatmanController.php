<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Boatman;
use Illuminate\Http\Request;

class BoatmanController extends Controller
{
    public function index()
    {
        $boatmen = Boatman::orderBy('name')->get();
        return view('admin.boatmen.index', compact('boatmen'), ['page_title' => 'Boatmen']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'phone'     => 'nullable|string|max:20',
            'alt_phone' => 'nullable|string|max:20',
            'ghat'      => 'nullable|string|max:255',
            'notes'     => 'nullable|string',
        ]);

        Boatman::create([
            'name'      => $request->name,
            'phone'     => $request->phone,
            'alt_phone' => $request->alt_phone,
            'ghat'      => $request->ghat,
            'notes'     => $request->notes,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('boatmen.index')->with('success', 'Boatman added successfully.');
    }

    public function update(Request $request, Boatman $boatman)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'phone'     => 'nullable|string|max:20',
            'alt_phone' => 'nullable|string|max:20',
            'ghat'      => 'nullable|string|max:255',
            'notes'     => 'nullable|string',
        ]);

        $boatman->update([
            'name'      => $request->name,
            'phone'     => $request->phone,
            'alt_phone' => $request->alt_phone,
            'ghat'      => $request->ghat,
            'notes'     => $request->notes,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('boatmen.index')->with('success', 'Boatman updated.');
    }

    public function destroy(Boatman $boatman)
    {
        $boatman->delete();
        return back()->with('success', 'Boatman deleted.');
    }

    public function toggleStatus(Boatman $boatman)
    {
        $boatman->update(['is_active' => !$boatman->is_active]);
        return back()->with('success', 'Status updated.');
    }
}
