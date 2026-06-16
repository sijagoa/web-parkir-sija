@extends('layouts.app')

@section('title', 'SIJA Parking - Vehicle Type Input')
@section('breadcrumb', 'Vehicle Type')
@section('page-title', 'Vehicle Type')

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
        border: 2px solid #e1008c;
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
    .input-wrapper input, .input-wrapper select {
        border: none;
        outline: none;
        font-size: 14px;
        font-family: 'Inter', sans-serif;
        background: transparent;
        color: var(--text-dark);
        width: 100%;
        padding-top: 4px;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
    }
    .input-wrapper input::placeholder {
        color: #cbd5e0;
    }
    .select-wrapper {
        position: relative;
    }
    .select-wrapper::after {
        content: '\f107';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        pointer-events: none;
    }
</style>

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header" style="padding: 24px;">
        <h2 class="card-header-title" style="font-size: 20px;">Vehicle Type <span style="font-weight: 400; color: var(--text-muted); font-size: 14px;">Input Form</span></h2>
    </div>

    <div class="card-body" style="padding: 0 24px 32px;">
        <form action="{{ route('vehicle-types.store') }}" method="POST" style="display: flex; flex-direction: column; gap: 16px;">
            @csrf

            <div class="input-wrapper inactive select-wrapper">
                <label>Vehicle Type</label>
                <select name="jenis" required autofocus>
                    <option value="motorcycle" {{ old('jenis') == 'motorcycle' ? 'selected' : '' }}>Motorcycle</option>
                    <option value="car" {{ old('jenis') == 'car' ? 'selected' : '' }}>Car</option>
                    <option value="other" {{ old('jenis') == 'other' ? 'selected' : '' }}>Truck/Bus/Other</option>
                </select>
                @error('jenis')
                    <span style="color: #e1008c; font-size: 10px; margin-top: 4px;">{{ $message }}</span>
                @enderror
            </div>

            <div class="input-wrapper inactive">
                <label>First Hour Charges</label>
                <input type="number" name="perjam_pertama" placeholder="2000" value="{{ old('perjam_pertama') }}" required min="0">
                @error('perjam_pertama')
                    <span style="color: #e1008c; font-size: 10px; margin-top: 4px;">{{ $message }}</span>
                @enderror
            </div>

            <div class="input-wrapper inactive">
                <label>Next Hourly Charges</label>
                <input type="number" name="perjam_berikutnya" placeholder="1000" value="{{ old('perjam_berikutnya') }}" required min="0">
                @error('perjam_berikutnya')
                    <span style="color: #e1008c; font-size: 10px; margin-top: 4px;">{{ $message }}</span>
                @enderror
            </div>

            <div class="input-wrapper inactive">
                <label>Max Cost Per Day</label>
                <input type="number" name="max_perhari" placeholder="10000" value="{{ old('max_perhari') }}" required min="0">
                @error('max_perhari')
                    <span style="color: #e1008c; font-size: 10px; margin-top: 4px;">{{ $message }}</span>
                @enderror
            </div>

            <div style="display: flex; gap: 16px; margin-top: 16px;">
                <a href="{{ route('vehicle-types.index') }}" class="btn" style="flex: 1; justify-content: center; background: #1e293b; color: white; border-radius: 8px; padding: 14px; font-weight: 700; text-decoration: none; box-shadow: 0 4px 10px rgba(30,41,59,0.3);">CANCEL</a>
                <button type="submit" class="btn" style="flex: 1; justify-content: center; background: linear-gradient(135deg, #d81b60, #8e24aa); color: white; border: none; border-radius: 8px; padding: 14px; font-weight: 700; box-shadow: 0 4px 12px rgba(216, 27, 96, 0.3); cursor: pointer; text-transform: uppercase;">SAVE VEHICLE TYPE</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const inputs = document.querySelectorAll('.input-wrapper input, .input-wrapper select');
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
