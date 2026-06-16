@extends('layouts.app')

@section('title', 'SIJA Parking - Vehicle Type')
@section('breadcrumb', 'Vehicle Type')
@section('page-title', 'Vehicle Type')

@section('topbar-actions')
    <div class="search-box">
        <form action="{{ route('vehicle-types.index') }}" method="GET">
            <i class="fas fa-search search-icon"></i>
            <input type="text" name="search" placeholder="Type here..." value="{{ request('search') }}">
        </form>
    </div>
    <a href="{{ route('vehicle-types.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> ADD NEW VEHICLE TYPE
    </a>
@endsection

@section('content')
<div class="card vehicle-type-page">
    <div class="card-header">
        <h2 class="card-header-title" style="color: #e1008c;">Vehicle Type <span style="color: var(--text-muted);">Data Table</span></h2>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>NO.</th>
                        <th>VEHICLE TYPE</th>
                        <th>FIRST HOUR CHARGES</th>
                        <th>NEXT HOURLY CHARGES</th>
                        <th>MAX COST PER DAY</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vehicleTypes as $index => $vt)
                    <tr>
                        <td>{{ $vehicleTypes->firstItem() + $index }}</td>
                        <td><strong style="text-transform: capitalize;">{{ $vt->jenis }}</strong></td>
                        <td>{{ number_format($vt->perjam_pertama, 0, ',', '.') }}</td>
                        <td>{{ number_format($vt->perjam_berikutnya, 0, ',', '.') }}</td>
                        <td>{{ number_format($vt->max_perhari, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="empty-state">
                            Belum ada data vehicle type.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($vehicleTypes->hasPages())
        <div style="margin-top: 16px;">
            {{ $vehicleTypes->links('pagination::bootstrap-4') }}
        </div>
        @endif
    </div>
</div>

@push('styles')
<style>
    .vehicle-type-page .card-header-title {
        color: #e1008c;
    }
    .vehicle-type-page .card-header-title span {
        color: #b84bff;
    }
    .vehicle-type-page .data-table thead th {
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
