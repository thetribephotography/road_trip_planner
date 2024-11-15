@extends('layouts.app')

@section('title', __('destination.detail'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">{{ __('destination.detail') }}</div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tbody>
                            <tr>
                                <td>{{ __('Trip Name') }}</td>
                                <td>{{ $trip->name }}</td>
                            </tr>
                            <tr>
                                <td>{{ __('destination.name') }}</td>
                                <td>{{ $destination->name }}</td>
                            </tr>
                            <tr>
                                <td>{{ __('destination.address') }}</td>
                                <td>{{ $destination->address }}</td>
                            </tr>
                            <tr>
                                <td>{{ __('destination.latitude') }}</td>
                                <td>{{ $destination->latitude }}</td>
                            </tr>
                            <tr>
                                <td>{{ __('destination.longitude') }}</td>
                                <td>{{ $destination->longitude }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    @can('update', $destination)
                        <a href="{{ route('destinations.edit', $destination) }}" id="edit-destination-{{ $destination->id }}"
                            class="btn btn-success">{{ __('destination.edit') }}</a>
                    @endcan
                    <a href="{{ route('destinations.create', ['trip_id' => $trip->id]) }}"
                        class="btn btn-success">{{ __('Add Destination') }}</a>
                    @if (auth()->check())
                        <a href="{{ route('trips.show', $trip) }}" id="show-destination-{{ $trip->id }}"
                            class="btn btn-primary">{{ __('Trip Summary') }}</a>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">{{ trans('destination.location') }}</div>
                @if ($userDestinations->count())
                    <div class="card-body" id="mapid"></div>
                @else
                    <div class="card-body">{{ __('destination.no_coordinate') }}</div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css" />
    <style>
        #mapid {
            height: 400px;
        }
    </style>
@endpush

@push('scripts')
    <!-- Make sure you put this AFTER Leaflet's CSS -->
    <script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js"></script>

    <script>
        // Store destination data in local storage
        localStorage.setItem('destinationData', JSON.stringify({
            name: '{{ $destination->name }}',
            address: '{{ $destination->address }}',
            latitude: '{{ $destination->latitude }}',
            longitude: '{{ $destination->longitude }}'
        }));

        var map = L.map('mapid').setView([{{ $destination->latitude }}, {{ $destination->longitude }}],
            {{ config('destination.detail_zoom_level') }});

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        @foreach ($userDestinations as $dest)
            L.marker([{{ $dest->latitude }}, {{ $dest->longitude }}]).addTo(map)
                .bindPopup("<b>Name: {{ $dest->name }}<br />Address: {{ $dest->address }}<br /><br />" +
                    "Latitude: {{ $dest->latitude }}<br />Longitude: {{ $dest->longitude }}<br /><br />" +
                    "Total Distance: {{ $summary['totalDistance'] ?? 'N/A' }} km<br />Total Time: {{ $summary['totalTime'] ?? 'N/A' }} minutes."
                    );
        @endforeach
    </script>
@endpush
