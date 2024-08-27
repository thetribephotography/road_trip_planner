<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TripController extends Controller
{
    //

    public function index()
    {
        $user = Auth::user();
        $trips = Trip::where('user_id', $user->id)->orderBy('created_at', 'desc')->where('name', 'like', '%' . request('q') . '%')
            ->paginate(25);;

        return view('trips.index', compact('trips'));
    }

    public function show($id)
    {
        $trip = Trip::where('user_id', Auth::user()->id)->find($id);

        if($trip){
            $destinations = Destination::where('trips_id', $trip->id)->orderBy('created_at', 'desc')
                ->where('name', 'like', '%' . request('q') . '%')
                ->paginate(25);

            return view('destinations.index', compact('destinations', $trip));
        } else{
            return redirect()->route('trips.index');
        }
    }


    public function storeTrip(Request $request)
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

    public function createTrip()
    {
        return view('trips.create');
    }
}
