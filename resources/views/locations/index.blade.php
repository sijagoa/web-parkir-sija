@extends('layouts.app')

@section('title', 'SIJA Parking - Location')
@section('breadcrumb', 'Location')
@section('page-title', 'Location')

@section('topbar-actions')
    <div class="search-box">
        <form action="{{ route('locations.index') }}" method="GET">
            <i class="fas fa-search search-icon"></i>
            <input type="text" name="search" placeholder="Type here..." value="{{ request('search') }}">
        </form>
    </div>
    <a href="{{ route('locations.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> ADD NEW LOCATION
    </a>
@endsection

@section('content')
<div class="card location-page">
    <div class="card-header">
        <h2 class="card-header-title" style="color: #e1008c;">Location <span style="color: #e1008c;">Data Table</span></h2>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>NO.</th>
                        <th>LOCATION NAME</th>
                        <th>MAX MOTORCYCLE</th>
                        <th>MAX CAR</th>
                        <th>MAX TRUCK/BUS/OTHER</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($locations as $index => $location)
                    <tr>
                        <td>{{ $locations->firstItem() + $index }}</td>
                        <td><strong>{{ $location->location_name }}</strong></td>
                        <td>{{ $location->max_motorcycle }}</td>
                        <td>{{ $location->max_car }}</td>
                        <td>{{ $location->max_other }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="empty-state">
                            Belum ada data location.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($locations->hasPages())
        <div style="margin-top: 16px;">
            {{ $locations->links('pagination::bootstrap-4') }}
        </div>
        @endif
    </div>
</div>

@push('styles')
<style>
    .location-page .data-table thead th {
        color: #e1008c;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Good Job',
            text: '{{ session('success') }}',
            confirmButtonText: 'OK',
            confirmButtonColor: '#8e24aa',
            customClass: {
                confirmButton: 'btn btn-primary'
            }
        });
    @endif
</script>
@endpush
@endsection
