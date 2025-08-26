<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Address;

class AddressController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $addresses = $user->addresses()->orderBy('is_default', 'desc')->get();
        
        return view('user.addresses.index', compact('addresses'));
    }

    public function create()
    {
        return view('user.addresses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'locality' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'zip' => 'required|string|max:20',
            'landmark' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        
        // If this is the first address, make it default
        $isDefault = $user->addresses()->count() === 0;
        
        $address = $user->addresses()->create([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'locality' => $request->locality,
            'city' => $request->city,
            'state' => $request->state,
            'zip' => $request->zip,
            'landmark' => $request->landmark,
            'country' => 'United States',
            'is_default' => $isDefault,
        ]);

        return redirect()->route('user.addresses.index')
                        ->with('success', 'Address added successfully!');
    }

    public function edit($id)
    {
        $user = Auth::user();
        $address = $user->addresses()->findOrFail($id);
        
        return view('user.addresses.edit', compact('address'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'locality' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'zip' => 'required|string|max:20',
            'landmark' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $address = $user->addresses()->findOrFail($id);
        
        $address->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'locality' => $request->locality,
            'city' => $request->city,
            'state' => $request->state,
            'zip' => $request->zip,
            'landmark' => $request->landmark,
        ]);

        return redirect()->route('user.addresses.index')
                        ->with('success', 'Address updated successfully!');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $address = $user->addresses()->findOrFail($id);
        
        // Don't allow deletion of the last address
        if ($user->addresses()->count() === 1) {
            return back()->withErrors(['error' => 'Cannot delete the last address.']);
        }
        
        $address->delete();
        
        return redirect()->route('user.addresses.index')
                        ->with('success', 'Address deleted successfully!');
    }

    public function setDefault($id)
    {
        $user = Auth::user();
        $address = $user->addresses()->findOrFail($id);
        
        // Remove default from all addresses
        $user->addresses()->update(['is_default' => false]);
        
        // Set this address as default
        $address->update(['is_default' => true]);
        
        return redirect()->route('user.addresses.index')
                        ->with('success', 'Default address updated successfully!');
    }
}
