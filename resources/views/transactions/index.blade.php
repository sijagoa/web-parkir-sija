@extends('layouts.app')

@section('title', 'SIJA Parking - Transaction')
@section('breadcrumb', 'Transaction')
@section('page-title', 'Transaction')

@section('topbar-actions')
    <div style="display: flex; gap: 8px; margin-right: 16px;">
        @foreach($vehicleTypes as $vt)
            <button class="btn vehicle-btn" data-id="{{ $vt->id }}" data-jenis="{{ $vt->jenis }}"
                onclick="selectVehicleType(this, {{ $vt->id }}, '{{ $vt->jenis }}')"
                style="background: #1e293b; color: white; padding: 10px 16px; font-size: 10px; border-radius: 8px; border: 2px solid transparent; text-transform: uppercase; box-shadow: 0 4px 10px rgba(30,41,59,0.2); transition: all 0.3s;">
                {{ $vt->jenis }}
            </button>
        @endforeach
    </div>
    <button id="btnEnterVehicle" onclick="enterVehicle()" class="btn" style="background: linear-gradient(135deg, #e8598d, #8e24aa); color: white; box-shadow: 0 4px 12px rgba(216, 27, 96, 0.3); padding: 10px 20px; font-size: 11px; text-transform: uppercase; border: none; cursor: pointer;">
        <i class="fas fa-plus"></i> ENTER VEHICLE
    </button>
@endsection

@section('content')
<style>
    .clock-widget {
        background-image: url('{{ asset('assets/da4050a958e1ce27a1f99b3b3ac090a6.jpg') }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;   
        position: relative;
        border-radius: 20px;
        padding: 32px 24px;
        color: white;
        text-align: center;
        width: 220px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        overflow: hidden;
    }
    .clock-widget::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(135deg, rgba(17,27,61,0.8) 0%, rgba(32,58,67,0.8) 50%, rgba(15,32,39,0.8) 100%);
        z-index: 1;
    }
    .clock-content {
        position: relative;
        z-index: 2;
    }
    .clock-icon {
        width: 60px;
        height: 60px;
        margin: 0 auto 16px;
        background: rgba(255,255,255,0.1);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(4px);
    }
    .clock-icon i {
        font-size: 32px;
        background: linear-gradient(135deg, #982978, #626366);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .input-wrapper {
        position: relative;
        border-radius: 8px;
        padding: 8px 16px;
        display: flex;
        flex-direction: column;
        transition: all 0.3s;
    }
    .input-wrapper.active {
        border: 2px solid #ec407a;
    }
    .input-wrapper.inactive {
        border: 1px solid #e2e8f0;
    }
    .input-wrapper label {
        font-size: 10px;
        color: #a0aec0;
        margin-bottom: 2px;
    }
    .input-wrapper input {
        border: none;
        outline: none;
        font-size: 14px;
        font-family: 'Inter', sans-serif;
        background: transparent;
        color: var(--text-dark);
        width: 100%;
    }
    .location-card.active {
        border: 2px solid #ec407a !important;
        background: #fdf2f8 !important;
    }
    .location-card:hover {
        transform: translateY(-4px);
    }

    /* Ticket list styles */
    .ticket-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 16px;
        border-bottom: 1px solid #f0f2f5;
        cursor: pointer;
        transition: all 0.2s;
        border-radius: 8px;
        margin-bottom: 4px;
    }
    .ticket-item:hover {
        background: #fdf2f8;
    }
    .ticket-item .ticket-info {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    .ticket-item .ticket-date {
        font-size: 11px;
        color: var(--text-muted);
    }
    .ticket-item .ticket-number {
        font-size: 12px;
        font-weight: 600;
        color: var(--text-dark);
    }
    .ticket-item .ticket-price {
        font-size: 12px;
        font-weight: 700;
        color: var(--text-dark);
        text-align: right;
    }

    /* Modal overlay */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.5);
        z-index: 999;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(4px);
    }
    .modal-overlay.show {
        display: flex;
    }
    .modal-content {
        background: #fff;
        border-radius: 20px;
        padding: 32px;
        width: 90%;
        max-width: 1100px;
        max-height: 80vh;
        overflow-y: auto;
        box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        position: relative;
    }
    .modal-content h2 {
        font-size: 20px;
        font-weight: 800;
        color: var(--text-dark);
        margin-bottom: 24px;
    }
    .modal-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
    }
    .modal-table thead th {
        padding: 12px 10px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--accent-pink);
        border-bottom: 2px solid #f0f2f5;
        text-align: left;
        white-space: nowrap;
    }
    .modal-table tbody td {
        padding: 10px;
        color: var(--text-dark);
        border-bottom: 1px solid #f0f2f5;
        vertical-align: middle;
    }
    .btn-close-modal {
        background: linear-gradient(135deg, #1e293b, #334155);
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 10px 28px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        text-transform: uppercase;
        letter-spacing: 1px;
        box-shadow: 0 4px 12px rgba(30,41,59,0.3);
        float: right;
        margin-top: 16px;
    }
    .btn-pdf {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: linear-gradient(135deg, #ec407a, #e8598d);
        color: white;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 10px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.2s;
    }
    .btn-pdf:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 10px rgba(236,64,122,0.3);
    }

    /* Scrollbar styling for tickets */
    .tickets-list {
        max-height: 400px;
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: #ec407a #f0f2f5;
    }
    .tickets-list::-webkit-scrollbar {
        width: 4px;
    }
    .tickets-list::-webkit-scrollbar-track {
        background: #f0f2f5;
        border-radius: 4px;
    }
    .tickets-list::-webkit-scrollbar-thumb {
        background: #ec407a;
        border-radius: 4px;
    }
</style>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">

    <div style="display: flex; flex-direction: column; gap: 24px;">

        <div style="display: flex; gap: 16px; overflow-x: auto; padding-bottom: 8px; scrollbar-width: none;">
            <div class="clock-widget" style="flex-shrink: 0;">
                <div class="clock-content">
                    <img src="{{ asset('assets/parkir.png') }}" style="width: 50px; height: 50px; object-fit: contain;">
                    <h3 id="clock-day" style="font-size: 22px; font-weight: 700; margin-bottom: 4px; letter-spacing: 0.5px;"></h3>
                    <p id="clock-date" style="font-size: 11px; color: #cbd5e0; margin-bottom: 32px; font-weight: 500;"></p>
                    <div id="clock-time" style="font-size: 32px; font-weight: 700; letter-spacing: 3px; text-shadow: 0 2px 10px rgba(0,0,0,0.3);"></div>
                </div>
            </div>

            @foreach($locations as $location)
            <div class="location-card" id="location-card-{{ $location->id }}"
                data-id="{{ $location->id }}"
                style="flex-shrink: 0; background: #fff; border-radius: 20px; padding: 24px 16px; text-align: center; width: 140px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); cursor: pointer; transition: all 0.3s; border: 2px solid transparent; display: flex; flex-direction: column; align-items: center; justify-content: center;"
                onclick="selectLocation(this, {{ $location->id }})">
                <div style="width: 50px; height: 50px; margin: 0 auto 16px; background: linear-gradient(135deg, #e8598d, #8e24aa); border-radius: 14px; display: flex; align-items: center; justify-content: center; color: white; font-size: 22px; box-shadow: 0 6px 14px rgba(216, 27, 96, 0.3);">
                    <i class="fas fa-building"></i>
                </div>
                <h4 style="font-size: 14px; font-weight: 700; color: var(--text-dark); margin-bottom: 8px;">{{ $location->location_name }}</h4>
                <div style="display: flex; justify-content: center; gap: 8px; font-size: 10px; color: var(--text-muted); font-weight: 600; margin-bottom: 12px;">
                    <span><i class="fas fa-motorcycle"></i> {{ $location->max_motorcycle }}</span>
                    <span><i class="fas fa-car"></i> {{ $location->max_car }}</span>
                    <span><i class="fas fa-truck"></i> {{ $location->max_other }}</span>
                </div>
                <div style="display: flex; justify-content: center; gap: 8px; font-size: 12px; font-weight: 700;">
                    <span id="avail-motorcycle-{{ $location->id }}" style="color: {{ $location->available_motorcycle > 0 ? '#4ade80' : '#f87171' }};">
                        <i class="fas fa-motorcycle"></i> {{ $location->available_motorcycle }}
                    </span>
                    <span id="avail-car-{{ $location->id }}" style="color: {{ $location->available_car > 0 ? '#4ade80' : '#f87171' }};">
                        <i class="fas fa-car"></i> {{ $location->available_car }}
                    </span>
                    <span id="avail-other-{{ $location->id }}" style="color: {{ $location->available_other > 0 ? '#4ade80' : '#f87171' }};">
                        <i class="fas fa-truck"></i> {{ $location->available_other }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>

        <div class="card">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; padding: 24px;">
                <h2 class="card-header-title">Transaction <span style="font-weight: 400; color: var(--text-muted); font-size: 14px;">Input Form</span></h2>
                <button id="btnExitVehicle" onclick="exitVehicle()" class="btn" style="background: #1e293b; color: white; border-radius: 8px; padding: 10px 16px; font-size: 10px; box-shadow: 0 4px 10px rgba(30,41,59,0.3); border: none; cursor: pointer;">
                    <i class="fas fa-plus"></i> EXIT VEHICLE
                </button>
            </div>
            <div class="card-body" style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; padding: 0 24px 32px;">
                <div class="input-wrapper active">
                    <label>Ticket Number</label>
                    <input type="text" id="inputTicketNumber" placeholder="" autofocus>
                </div>
                <div class="input-wrapper inactive">
                    <label>Police Number</label>
                    <input type="text" id="inputPoliceNumber" placeholder="">
                </div>
            </div>
        </div>

    </div>

    <div class="card" style="height: 100%; display: flex; flex-direction: column;">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; padding: 24px;">
            <h3 style="font-size: 16px; font-weight: 700; color: var(--text-dark);">Tickets</h3>
            <button onclick="viewAllTransactions()" class="btn" style="background: transparent; color: #e8598d; border: 1px solid #e8598d; border-radius: 8px; padding: 8px 16px; font-size: 10px; font-weight: 700; cursor: pointer;">
                VIEW ALL
            </button>
        </div>
        <div class="card-body tickets-list" id="ticketsList">
            @forelse($activeTickets as $ticket)
            <div class="ticket-item" onclick="fillTicketNumber('{{ $ticket->no_tiket }}')">
                <div class="ticket-info">
                    <span class="ticket-date">{{ $ticket->masuk->format('Y-m-d H:i:s') }}</span>
                    <span class="ticket-number">#{{ $ticket->no_tiket }}</span>
                </div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    @if($ticket->total_bayar > 0)
                        <span class="ticket-price">Rp {{ number_format($ticket->total_bayar, 0, ',', '.') }}</span>
                    @endif
                    <a href="{{ route('transactions.pdf', $ticket->no_tiket) }}" target="_blank" class="btn-pdf" onclick="event.stopPropagation();">
                        <i class="fas fa-file-pdf"></i> PDF
                    </a>
                </div>
            </div>
            @empty
            <div style="text-align: center; padding: 40px 20px; color: var(--text-muted);">
                <i class="fas fa-ticket-alt" style="font-size: 32px; margin-bottom: 12px; opacity: 0.3;"></i>
                <p style="font-size: 12px;">Belum ada tiket aktif</p>
            </div>
            @endforelse
        </div>
    </div>

</div>

<div class="modal-overlay" id="modalViewAll">
    <div class="modal-content">
        <h2>All Transactions</h2>
        <div class="table-responsive">
            <table class="modal-table">
                <thead>
                    <tr>
                        <th>NO.</th>
                        <th>TICKET NUMBER</th>
                        <th>POLICE NUMBER</th>
                        <th>LOCATION NAME</th>
                        <th>VEHICLE TYPE</th>
                        <th>TIME IN</th>
                        <th>TIME OUT</th>
                        <th>FIRST HOUR CHARGES</th>
                        <th>NEXT HOURLY CHARGES</th>
                        <th>MAX COST PER DAY</th>
                        <th>TOTAL HOURS</th>
                        <th>TOTAL DAYS</th>
                        <th>TOTAL PAYS</th>
                    </tr>
                </thead>
                <tbody id="allTransactionsBody">
                    <tr>
                        <td colspan="13" style="text-align: center; padding: 40px; color: var(--text-muted);">Loading...</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <button class="btn-close-modal" onclick="closeModal()">CLOSE</button>
        <div style="clear:both;"></div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('assets/js/sweetalert.js') }}"></script>
<script>
    function updateClock() {
        const now = new Date();
        const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        document.getElementById('clock-day').textContent = days[now.getDay()];
        document.getElementById('clock-date').textContent = now.getDate() + ' ' + months[now.getMonth()] + ' ' + now.getFullYear();

        const h = String(now.getHours()).padStart(2, '0');
        const m = String(now.getMinutes()).padStart(2, '0');
        const s = String(now.getSeconds()).padStart(2, '0');
        document.getElementById('clock-time').textContent = h + ' : ' + m + ' : ' + s;
    }

    setInterval(updateClock, 1000);
    updateClock();

    let selectedLocationId = null;
    let selectedVehicleTypeId = null;
    let selectedVehicleTypeName = null;

    function selectLocation(element, locationId) {
        document.querySelectorAll('.location-card').forEach(card => {
            card.classList.remove('active');
        });
        element.classList.add('active');
        selectedLocationId = locationId;
    }

    function selectVehicleType(element, vehicleTypeId, vehicleType) {
        document.querySelectorAll('.vehicle-btn').forEach(btn => {
            btn.style.border = '2px solid transparent';
            btn.style.background = '#1e293b';
        });
        element.style.border = '2px solid #ec407a';
        element.style.background = 'linear-gradient(135deg, #e8598d, #8e24aa)';
        selectedVehicleTypeId = vehicleTypeId;
        selectedVehicleTypeName = vehicleType;
    }

    function enterVehicle() {
        if (!selectedLocationId) {
            Swal.fire({
                icon: 'warning',
                title: 'Pilih Lokasi',
                text: 'Silakan pilih lokasi parkir terlebih dahulu!',
                confirmButtonColor: '#e8598d'
            });
            return;
        }

        if (!selectedVehicleTypeId) {
            Swal.fire({
                icon: 'warning',
                title: 'Pilih Jenis Kendaraan',
                text: 'Silakan pilih jenis kendaraan terlebih dahulu!',
                confirmButtonColor: '#e8598d'
            });
            return;
        }

        fetch('{{ route("transactions.enter") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                id_lokasi: selectedLocationId,
                id_jenis: selectedVehicleTypeId
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                updateAvailability(data.id_lokasi, data.jenis_key, data.remaining);
                addTicketToSidebar(data.no_tiket, data.masuk);

                Swal.fire({
                    icon: 'success',
                    title: 'Kendaraan Masuk!',
                    html: `
                        <div style="text-align:left; font-size:14px; line-height:2;">
                            <strong>No Tiket:</strong> ${data.no_tiket}<br>
                            <strong>Lokasi:</strong> ${data.location}<br>
                            <strong>Jenis:</strong> ${data.jenis}<br>
                            <strong>Masuk:</strong> ${data.masuk}
                        </div>
                    `,
                    confirmButtonColor: '#e8598d',
                    confirmButtonText: 'OK'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: data.message,
                    confirmButtonColor: '#e8598d'
                });
            }
        })
        .catch(err => {
            err.response && err.response.json().then(data => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: data.message || 'Terjadi kesalahan.',
                    confirmButtonColor: '#e8598d'
                });
            });
            if (!err.response) {
                console.error(err);
            }
        });
    }

    function exitVehicle() {
        const noTiket = document.getElementById('inputTicketNumber').value.trim();
        const noPolisi = document.getElementById('inputPoliceNumber').value.trim();

        if (!noTiket) {
            Swal.fire({
                icon: 'warning',
                title: 'Input Ticket Number',
                text: 'Silakan masukkan nomor tiket!',
                confirmButtonColor: '#e8598d'
            });
            return;
        }

        if (!noPolisi) {
            Swal.fire({
                icon: 'warning',
                title: 'Input Police Number',
                text: 'Silakan masukkan nomor polisi!',
                confirmButtonColor: '#e8598d'
            });
            return;
        }

        fetch('{{ route("transactions.exit") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                no_tiket: noTiket,
                no_polisi: noPolisi
            })
        })
        .then(res => {
            if (!res.ok) {
                return res.json().then(data => { throw data; });
            }
            return res.json();
        })
        .then(data => {
            if (data.success) {
                updateAvailability(data.id_lokasi, data.jenis_key, data.remaining);
                updateTicketInSidebar(data.no_tiket, data.total_bayar);
                document.getElementById('inputTicketNumber').value = '';
                document.getElementById('inputPoliceNumber').value = '';
                Swal.fire({
                    html: `
                        <div style="text-align:center;">
                            <h2 style="font-size: 24px; font-weight: 800; color: #344767; margin-bottom: 8px;">Total Bayar : Rp ${formatNumber(data.total_bayar)}</h2>
                        </div>
                    `,
                    confirmButtonColor: '#e8598d',
                    confirmButtonText: 'OK',
                    customClass: {
                        popup: 'swal-wide'
                    }
                });
            }
        })
        .catch(data => {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: data.message || 'Terjadi kesalahan.',
                confirmButtonColor: '#e8598d'
            });
        });
    }

    function updateAvailability(locationId, jenisKey, remaining) {
        const type = jenisKey.replace('max_', '');
        const el = document.getElementById('avail-' + type + '-' + locationId);
        if (el) {
            const icon = el.querySelector('i').outerHTML;
            el.innerHTML = icon + ' ' + remaining;
            el.style.color = remaining > 0 ? '#4ade80' : '#f87171';
        }
    }

    function addTicketToSidebar(noTiket, masuk) {
        const list = document.getElementById('ticketsList');
        const emptyState = list.querySelector('.empty-state, div[style*="text-align: center"]');
        if (emptyState) {
            emptyState.remove();
        }

        const pdfUrl = '{{ url("transaction/pdf") }}/' + noTiket;

        const item = document.createElement('div');
        item.className = 'ticket-item';
        item.setAttribute('data-ticket', noTiket);
        item.onclick = function() { fillTicketNumber(noTiket); };
        item.innerHTML = `
            <div class="ticket-info">
                <span class="ticket-date">${masuk}</span>
                <span class="ticket-number">#${noTiket}</span>
            </div>
            <div style="display: flex; align-items: center; gap: 8px;">
                <span class="ticket-price" style="display:none;"></span>
                <a href="${pdfUrl}" target="_blank" class="btn-pdf" onclick="event.stopPropagation();">
                    <i class="fas fa-file-pdf"></i> PDF
                </a>
            </div>
        `;

        list.insertBefore(item, list.firstChild);
    }

    function updateTicketInSidebar(noTiket, totalBayar) {
        const item = document.querySelector(`.ticket-item[data-ticket="${noTiket}"]`);
        if (item) {
            const priceEl = item.querySelector('.ticket-price');
            if (priceEl) {
                priceEl.style.display = 'inline';
                priceEl.textContent = 'Rp ' + formatNumber(totalBayar);
            }
        }
    }

    function fillTicketNumber(noTiket) {
        document.getElementById('inputTicketNumber').value = noTiket;
        document.getElementById('inputTicketNumber').parentElement.classList.add('active');
        document.getElementById('inputTicketNumber').parentElement.classList.remove('inactive');

        const policeInput = document.getElementById('inputPoliceNumber');
        policeInput.parentElement.classList.add('active');
        policeInput.parentElement.classList.remove('inactive');
        policeInput.focus();
    }

    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function viewAllTransactions() {
        const modal = document.getElementById('modalViewAll');
        modal.classList.add('show');

        fetch('{{ route("transactions.all") }}')
        .then(res => res.json())
        .then(data => {
            const tbody = document.getElementById('allTransactionsBody');
            if (data.transactions.length === 0) {
                tbody.innerHTML = '<tr><td colspan="13" style="text-align:center; padding:40px; color:var(--text-muted);">Belum ada transaksi.</td></tr>';
                return;
            }

            tbody.innerHTML = '';
            data.transactions.forEach((t, i) => {
                const pdfUrl = '{{ url("transaction/pdf") }}/' + t.no_tiket;
                tbody.innerHTML += `
                    <tr>
                        <td>${i + 1}.</td>
                        <td>
                            <a href="${pdfUrl}" target="_blank" class="btn-pdf" style="margin-right:6px;"><i class="fas fa-file-pdf"></i> PDF</a>
                            ${t.no_tiket}
                        </td>
                        <td>${t.no_polisi || '-'}</td>
                        <td>${t.location_name}</td>
                        <td>${t.jenis}</td>
                        <td>${t.masuk}</td>
                        <td>${t.keluar}</td>
                        <td>Rp ${formatNumber(t.perjam_pertama)}</td>
                        <td>Rp ${formatNumber(t.perjam_berikutnya)}</td>
                        <td>Rp ${formatNumber(t.max_perhari)}</td>
                        <td>${t.total_jam}</td>
                        <td>${t.total_hari}</td>
                        <td>Rp ${formatNumber(t.total_bayar)}</td>
                    </tr>
                `;
            });
        });
    }

    function closeModal() {
        document.getElementById('modalViewAll').classList.remove('show');
    }

    document.getElementById('modalViewAll').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });

    const inputs = document.querySelectorAll('.input-wrapper input');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.remove('inactive');
            this.parentElement.classList.add('active');
        });
        input.addEventListener('blur', function() {
            if (!this.value) {
                this.parentElement.classList.remove('active');
                this.parentElement.classList.add('inactive');
            }
        });
    });
</script>
@endpush
@endsection
