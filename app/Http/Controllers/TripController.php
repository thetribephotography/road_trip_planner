<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TripController extends Controller
{
    //make it that a trip bascially contains destinations which are stored in the destinations table and the trip table stores the trip name, destinations for a particular trip can be rearranged and the distance can there been calculated for that trip.

    public function index()
    {
        $user = Auth::user();
        $trips = Trip::where('user_id', $user->id)->orderBy('created_at', 'desc')->where('name', 'like', '%' . request('q') . '%')
            ->paginate(25);

        return view('trips.index', compact('trips', 'user'));
    }

    public function show($id)
    {
        $trip = Trip::where('user_id', Auth::user()->id)->find($id);

        if($trip){
            $destinations = Destination::where('trip_id', $trip->id)->orderBy('created_at', 'desc')
                // ->where('name', 'like', '%' . request('q') . '%')
                ->get();

            // return view('destinations.index', compact('destinations', 'trip'));
            return view('destinations.map', compact('destinations', 'trip'));
        } else{
            return redirect()->route('trips.index');
        }
    }


    public function store(Request $request)
    {
        try{
            $user = Auth::user();

            $validated = $request->validate([
                'name' => 'required',
            ]);

            $trip = Trip::create([
                'name' => $validated['name'],
                'user_id' => $user->id
            ]);

            //return all trips route when trip is created
            return redirect()->route('trips.index');

        }catch (\Exception $err){
            dd($err->getMessage());
        }
    }

    public function create()
    {
        return view('trips.create');
    }
}
