@extends('layouts.app')

@section('title', __('Trips.list'))

@section('content')
<div class="mb-3">
    <h1 class="page-title">{{ __('Trips List') }} <small>{{ __('app.total') }} : {{ $trips->total() }}</small></h1>

</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                 <div class="float-right">
        {{-- @can('create', new App\Models\Trip) --}}
            <a href="{{ route('trips.create') }}" class="btn btn-success">{{ __('Create New Trip') }}</a>
            {{-- <p>jdj</p> --}}
        {{-- @endcan --}}
    </div>
                <form method="GET" action="" accept-charset="UTF-8" class="form-inline">
                    <div class="form-group">
                        <label for="q" class="control-label">{{ __('Trips Search') }}</label>
                        <input placeholder="{{ __('trips.search_text') }}" name="q" type="text" id="q" class="form-control mx-sm-2" value="{{ request('q') }}">
                    </div>
                    <input type="submit" value="{{ __('trips.search') }}" class="btn btn-secondary">
                    <a href="{{ route('trips.index') }}" class="btn btn-link">{{ __('app.reset') }}</a>
                </form>
            </div>
            <table class="table table-sm table-responsive-sm">
                <thead>
                    <tr>
                        <th class="text-center">{{ __('app.table_no') }}</th>
                        <th>{{ __('Trips') }}</th>
                        <th class="text-center">{{ __('app.action') }}</th>
                    </tr>
                </thead>
                <tbody id="destination-table-body">
                    @forelse($trips as $key => $trip)
                    <tr data-trip-id="{{ $trip->id }}">
                        <td class="text-center">{!! $trip->id !!}</td>
                        <td>{!! $trip->name !!}</td>
                        <td class="text-center">
                            <a href="{{ route('trips.show', $trip) }}" id="show-destination-{{ $trip->id }}">{{ __('app.show') }}</a>
                            {{-- <a class="nav-link text-white" href="{{ route('destination_map.index') }}">{{ __('menu.map') }}</a> --}}
                        </td>
                    </tr>
                    @empty
                    <p class="text-center p-5">No Trips Available.</p>
                    @endforelse
                </tbody>
            </table>
            {{-- <div class="card-body">{{ $destinations->appends(Request::except('page'))->render() }}</div> --}}

        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endpush

@push('scripts')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script>
    $(function() {
        $("#destination-table-body").sortable({
            items: "tr",
            cursor: "move",
            update: function(event, ui) {
                // Get the updated order of the rows
                var newOrder = $(this).sortable('toArray', { attribute: 'data-destination-id' });

                // Send the new order to the server using AJAX
                $.ajax({
                    url: "{{ route('destinations.updateOrder') }}",
                    type: "POST",
                    data: {
                        order: newOrder,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        // Handle success response
                        console.log(response);
                    },
                    error: function(error) {
                        // Handle error response
                        console.error(error);
                    }
                });
            }
        });
    });
</script>
@endpush
