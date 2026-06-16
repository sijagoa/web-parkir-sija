@extends('layouts.app')

@section('title', 'SIJA Parking - Location Input')
@section('breadcrumb', 'Location')
@section('page-title', 'Location')

@section('content')
<style>
    .input-wrapper {
        position: relative;
        border-radius: 8px;
        padding: 8px 16px;
        display: flex;
        flex-direction: column;
        transition: all 0.3s;
    }
    .input-wrapper.active {
        border: 2px solid #d752ab;
    }
    .input-wrapper.inactive {
        border: 1px solid #e2e8f0;
    }
    .input-wrapper label {
        font-size: 10px;
        color: #a0aec0;
        margin-bottom: 2px;
        font-weight: 700;
    }
    .input-wrapper input {
        border: none;
        outline: none;
        font-size: 14px;
        font-family: 'Inter', sans-serif;
        background: transparent;
        color: var(--text-dark);
        width: 100%;
        padding-top: 4px;
    }
    .input-wrapper input::placeholder {
        color: #cbd5e0;
    }
</style>

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header" style="padding: 24px;">
        <h2 class="card-header-title" style="font-size: 20px;">Location <span style="font-weight: 400; color: var(--text-muted); font-size: 14px;">Input Form</span></h2>
    </div>

    <div class="card-body" style="padding: 0 24px 32px;">
        <form action="{{ route('locations.store') }}" method="POST" style="display: flex; flex-direction: column; gap: 16px;">
            @csrf

            <div class="input-wrapper inactive">
                <label>Location Name</label>
                <input type="text" name="location_name" placeholder="Gedung A-Z" value="{{ old('location_name') }}" required autofocus>
                @error('location_name')
                    <span style="color: #d752ab; font-size: 10px; margin-top: 4px;">{{ $message }}</span>
                @enderror
            </div>

            <div class="input-wrapper inactive">
                <label>Max Motorcycle</label>
                <input type="number" name="max_motorcycle" placeholder="0" value="{{ old('max_motorcycle', 0) }}" required min="0">
                @error('max_motorcycle')
                    <span style="color: #d752ab; font-size: 10px; margin-top: 4px;">{{ $message }}</span>
                @enderror
            </div>

            <div class="input-wrapper inactive">
                <label>Max Car</label>
                <input type="number" name="max_car" placeholder="0" value="{{ old('max_car', 0) }}" required min="0">
                @error('max_car')
                    <span style="color: #d752ab; font-size: 10px; margin-top: 4px;">{{ $message }}</span>
                @enderror
            </div>

            <div class="input-wrapper inactive">
                <label>Max Truck/Bus/Other</label>
                <input type="number" name="max_other" placeholder="0" value="{{ old('max_other', 0) }}" required min="0">
                @error('max_other')
                    <span style="color: #d752ab; font-size: 10px; margin-top: 4px;">{{ $message }}</span>
                @enderror
            </div>

            <div style="display: flex; gap: 16px; margin-top: 16px;">
                <a href="{{ route('locations.index') }}" class="btn" style="flex: 1; justify-content: center; background: #1e293b; color: white; border-radius: 8px; padding: 14px; font-weight: 700; text-decoration: none; box-shadow: 0 4px 10px rgba(30,41,59,0.3);">CANCEL</a>
                <button type="submit" class="btn" style="flex: 1; justify-content: center; background: linear-gradient(135deg, #d81b60, #8e24aa); color: white; border: none; border-radius: 8px; padding: 14px; font-weight: 700; box-shadow: 0 4px 12px rgba(216, 27, 96, 0.3); cursor: pointer; text-transform: uppercase;">SAVE LOCATION</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const inputs = document.querySelectorAll('.input-wrapper input');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.remove('inactive');
            this.parentElement.classList.add('active');
        });
        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('active');
            this.parentElement.classList.add('inactive');
        });
    });
</script>
@endpush
@endsection
