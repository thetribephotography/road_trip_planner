@extends('layouts.app')

@section('content')

<div class="float-right">
        @can('create', new App\Models\Destination)
        {{-- dd({{$trip->id}}); --}}
            <a href="{{ route('destinations.create', ['trip_id' => $trip->id] ) }}" class="btn btn-success">{{ __('destination.create') }}</a>
        @endcan
    </div>

    
    <div class="container">
        <div class="card">
            <div class="card-body" id="mapid"></div>
        </div>
    </div>


@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css">
    <style>
        #mapid { height: 500px; }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.min.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>

    <script>
        // Initialize the map
        var map = L.map('mapid').setView([{{ config('destination.map_center_latitude') }}, {{ config('destination.map_center_longitude') }}], {{ config('destination.zoom_level') }});
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        var destinations = @json($destinations);

        // Add all destinations to the map as markers and create a routing line between them
        var routingControl = L.Routing.control({
            waypoints: destinations.map(function(destination) {
                return L.latLng(destination.latitude, destination.longitude);
            }),
            routeWhileDragging: false,
            addWaypoints: false,
            createMarker: function(i, waypoint, n) {
                return L.marker(waypoint.latLng).bindPopup(destinations[i].name);
            },
            lineOptions: {
                styles: [{ color: 'blue', opacity: 0.6, weight: 4 }]
            }
        }).addTo(map);

        routingControl.on('routesfound', function(e) {
            var routes = e.routes;
            var summary = routes[0].summary;
            alert('Total distance: ' + (summary.totalDistance / 1000).toFixed(2) + ' km, Total time: ' + Math.round(summary.totalTime / 60) + ' minutes');
        });
    </script>
@endpush
