@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@push('styles')
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #f5f7fa;
}

/* NAVBAR */
.top-navbar {
    background: white;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    padding: 15px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 100;
}

.navbar-brand {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 24px;
    font-weight: bold;
    color: #1f2937;
    text-decoration: none;
}

.navbar-brand .logo {
    width: 35px;
    height: 35px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    color: white;
    font-weight: bold;
}

.navbar-menu {
    display: flex;
    gap: 30px;
    align-items: center;
}

.navbar-menu a {
    color: #6b7280;
    text-decoration: none;
    font-size: 15px;
    font-weight: 500;
    transition: color 0.3s;
}

.navbar-menu a:hover {
    color: #6366f1;
}

.navbar-menu a.active {
    color: #6366f1;
    font-weight: 600;
}

.navbar-right {
    display: flex;
    align-items: center;
    gap: 15px;
}

.navbar-user {
    display: flex;
    align-items: center;
    gap: 10px;
}

.navbar-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 16px;
}

.navbar-user span {
    font-size: 14px;
    color: #1f2937;
    font-weight: 500;
}

.btn-signout {
    padding: 10px 20px;
    background: #ef4444;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    transition: background 0.3s;
}

.btn-signout:hover {
    background: #dc2626;
}

.dashboard-container {
    display: flex;
    min-height: 100vh;
    margin-top: 70px;
}

/* SIDEBAR */
.sidebar {
    width: 260px;
    background: white;
    box-shadow: 2px 0 10px rgba(0,0,0,0.05);
    display: flex;
    flex-direction: column;
    position: fixed;
    height: 100vh;
    overflow-y: auto;
}

.sidebar-header {
    padding: 25px 20px;
    border-bottom: 1px solid #e5e7eb;
}

.sidebar-header h2 {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 18px;
    color: #1f2937;
}

.sidebar-header .crown-icon {
    color: #8b5cf6;
    font-size: 24px;
}

.sidebar-header p {
    font-size: 12px;
    color: #6b7280;
    margin-top: 5px;
}

.sidebar-menu {
    flex: 1;
    padding: 20px 0;
}

.menu-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 20px;
    color: #4b5563;
    text-decoration: none;
    transition: all 0.3s;
    font-size: 14px;
    cursor: pointer;
}

.menu-item:hover {
    background: #f3f4f6;
    color: #6366f1;
}

.menu-item.active {
    background: #eef2ff;
    color: #6366f1;
    border-right: 3px solid #6366f1;
}

.menu-item i {
    font-size: 18px;
    width: 20px;
}

.sidebar-footer {
    padding: 20px;
    border-top: 1px solid #e5e7eb;
}

.logout-btn {
    width: 100%;
    padding: 12px;
    background: #ef4444;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: background 0.3s;
}

.logout-btn:hover {
    background: #dc2626;
}

/* MAIN CONTENT */
.main-content {
    flex: 1;
    margin-left: 260px;
    padding: 30px;
}

.content-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.content-header h1 {
    font-size: 28px;
    color: #1f2937;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 15px;
}

.notification-btn {
    position: relative;
    padding: 10px;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s;
}

.notification-btn:hover {
    background: #f9fafb;
}

.notification-badge {
    position: absolute;
    top: 5px;
    right: 5px;
    width: 8px;
    height: 8px;
    background: #ef4444;
    border-radius: 50%;
}

.user-profile {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 15px;
    background: white;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.user-avatar {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
}

.user-profile span {
    font-size: 14px;
    color: #1f2937;
    font-weight: 500;
}

/* STATS CARDS */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 20px;
    transition: transform 0.3s, box-shadow 0.3s;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.stat-icon {
    font-size: 40px;
    opacity: 0.9;
}

.stat-content h3 {
    font-size: 14px;
    font-weight: 500;
    margin-bottom: 8px;
    opacity: 0.95;
}

.stat-content p {
    font-size: 28px;
    font-weight: bold;
}

/* SECTION TABS */
.section-tabs {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
    background: white;
    padding: 10px;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.tab-btn {
    padding: 12px 24px;
    background: transparent;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 14px;
    color: #6b7280;
    transition: all 0.3s;
    font-weight: 500;
}

.tab-btn.active {
    background: #6366f1;
    color: white;
}

.tab-btn:hover {
    background: #f3f4f6;
}

.tab-btn.active:hover {
    background: #5558e3;
}

/* CONTENT SECTIONS */
.content-section {
    display: none;
}

.content-section.active {
    display: block;
}

/* DASHBOARD CARD */
.dashboard-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    padding: 25px;
    margin-bottom: 20px;
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.card-header h3 {
    font-size: 18px;
    color: #1f2937;
}

.btn-primary {
    padding: 10px 20px;
    background: #6366f1;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    transition: background 0.3s;
}

.btn-primary:hover {
    background: #5558e3;
}

.btn-outline {
    padding: 10px 20px;
    background: white;
    color: #6366f1;
    border: 1px solid #6366f1;
    border-radius: 8px;
    cursor: pointer;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    transition: all 0.3s;
}

.btn-outline:hover {
    background: #6366f1;
    color: white;
}

.btn-danger {
    padding: 8px 16px;
    background: #ef4444;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 13px;
    transition: background 0.3s;
}

.btn-danger:hover {
    background: #dc2626;
}

.btn-success {
    padding: 8px 16px;
    background: #10b981;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 13px;
    transition: background 0.3s;
}

.btn-success:hover {
    background: #059669;
}

/* ROOMS GRID */
.rooms-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
}

.room-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: transform 0.3s, box-shadow 0.3s;
}

.room-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.room-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.room-content {
    padding: 20px;
}

.room-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 10px;
}

.room-header h4 {
    font-size: 16px;
    color: #1f2937;
}

.room-status {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.room-status.available {
    background: #d1fae5;
    color: #065f46;
}

.room-status.occupied {
    background: #fee2e2;
    color: #991b1b;
}

.room-details {
    font-size: 13px;
    color: #6b7280;
    margin-bottom: 15px;
}

.room-details p {
    margin-bottom: 5px;
}

.room-price {
    font-size: 20px;
    font-weight: bold;
    color: #6366f1;
    margin-bottom: 15px;
}

.room-actions {
    display: flex;
    gap: 10px;
}

.room-actions button,
.room-actions a {
    flex: 1;
    padding: 8px;
    text-align: center;
    border-radius: 6px;
    font-size: 13px;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.3s;
}

/* TABLE */
.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table thead {
    background: #f9fafb;
}

.data-table th {
    padding: 12px;
    text-align: left;
    font-size: 13px;
    font-weight: 600;
    color: #4b5563;
    border-bottom: 2px solid #e5e7eb;
}

.data-table td {
    padding: 12px;
    font-size: 14px;
    color: #1f2937;
    border-bottom: 1px solid #e5e7eb;
}

.data-table tbody tr:hover {
    background: #f9fafb;
}

.action-buttons {
    display: flex;
    gap: 8px;
}

.action-btn {
    padding: 6px 12px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 12px;
    transition: all 0.3s;
}

.action-btn.edit {
    background: #3b82f6;
    color: white;
}

.action-btn.edit:hover {
    background: #2563eb;
}

.action-btn.delete {
    background: #ef4444;
    color: white;
}

.action-btn.delete:hover {
    background: #dc2626;
}

.action-btn.view {
    background: #10b981;
    color: white;
}

.action-btn.view:hover {
    background: #059669;
}

/* FORM STYLES */
.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-size: 14px;
    font-weight: 500;
    color: #374151;
}

.form-control {
    width: 100%;
    padding: 12px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 14px;
    transition: border-color 0.3s;
}

.form-control:focus {
    outline: none;
    border-color: #6366f1;
}

.form-row {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

.form-actions {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
    margin-top: 30px;
}

/* FACILITIES TAGS */
.facilities-input {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 10px;
}

.facility-tag {
    padding: 8px 15px;
    background: #eef2ff;
    color: #6366f1;
    border-radius: 20px;
    font-size: 13px;
    cursor: pointer;
    transition: all 0.3s;
    border: 2px solid transparent;
}

.facility-tag.selected {
    background: #6366f1;
    color: white;
    border-color: #6366f1;
}

/* MODAL */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    z-index: 1000;
    align-items: center;
    justify-content: center;
}

.modal.active {
    display: flex;
}

.modal-content {
    background: white;
    border-radius: 12px;
    width: 90%;
    max-width: 600px;
    max-height: 90vh;
    overflow-y: auto;
    padding: 30px;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.modal-header h3 {
    font-size: 20px;
    color: #1f2937;
}

.close-modal {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #6b7280;
}

.close-modal:hover {
    color: #1f2937;
}

/* Alert Success */
.alert {
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    animation: slideDown 0.3s ease;
}

.alert-success {
    background: #d1fae5;
    color: #065f46;
    border: 1px solid #10b981;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.alert i {
    font-size: 20px;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .top-navbar {
        padding: 15px 20px;
    }
    
    .navbar-menu {
        display: none;
    }
    
    .sidebar {
        width: 200px;
    }
    
    .main-content {
        margin-left: 200px;
        padding: 20px;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .rooms-grid {
        grid-template-columns: 1fr;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 576px) {
    .navbar-brand span:not(.logo) {
        display: none;
    }
    
    .navbar-user span {
        display: none;
    }
    
    .sidebar {
        width: 70px;
    }
    
    .sidebar-header p,
    .menu-item span {
        display: none;
    }
    
    .main-content {
        margin-left: 70px;
    }
}
</style>
@endpush

@section('content')
<!-- TOP NAVBAR -->
<nav class="top-navbar">
    <a href="#" class="navbar-brand">
    </a>
    
    @if(!request()->is('admin*'))
    <div class="navbar-menu">
        <a href="{{ route('home') }}">HOME</a>
        <a href="{{ route('rooms') }}">ROOMS</a>
        <a href="{{ route('reservations.my') }}">MY RESERVATION</a>
        <a href="{{ route('about') }}">ABOUT</a>
        <a href="{{ route('contact') }}">CONTACT</a>
    </div>
    @endif

    
    <div class="navbar-right">
        <div class="navbar-user">
            <div class="navbar-avatar">A</div>
            <span>Admin Hotel</span>
        </div>
        <button class="btn-signout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            Sign Out
        </button>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
</nav>

<div class="dashboard-container">
    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <h2>
                Admin Hotel
            </h2>
            <p>Dashboard admin</p>
        </div>
        
        <nav class="sidebar-menu">
            <a href="#" class="menu-item active" data-tab="dashboard">
                <i data-feather="home"></i>
                <span>Dashboard</span>
            </a>
            <a href="#" class="menu-item" data-tab="rooms">
                <i data-feather="grid"></i>
                <span>Kelola Kamar</span>
            </a>
            <a href="#" class="menu-item" data-tab="tenants">
                <i data-feather="users"></i>
                <span>Daftar Penyewa</span>
            </a>
            <a href="#" class="menu-item" data-tab="payments">
                <i data-feather="credit-card"></i>
                <span>Pembayaran</span>
            </a>
            <!-- <a href="#" class="menu-item" data-tab="maintenance">
                <i data-feather="tool"></i>
                <span>Perbaikan</span>
            </a> -->
            <a href="#" class="menu-item" data-tab="reports">
                <i data-feather="bar-chart-2"></i>
                <span>Laporan Keuangan</span>
            </a>
            <!--<a href="#" class="menu-item" data-tab="settings">
                <i data-feather="settings"></i>
                <span>Pengaturan</span>
            </a> -->
        </nav>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="main-content">
        <!-- HEADER -->
        <div class="content-header">
            <div>
                <h1>Dashboard</h1>
                <p style="color: #6b7280; font-size: 14px; margin-top: 5px;">Welcome back, Admin Hotel!</p>
            </div>
        </div>

        <!-- DASHBOARD SECTION -->
        <div class="content-section active" id="dashboard">
            <!-- STATS GRID -->
            <div class="stats-grid">
                <div class="stat-card" style="background: white; color: #1f2937;">
                    <div class="stat-icon" style="color: #6366f1; background: #eef2ff; padding: 15px; border-radius: 12px;">
                        <i data-feather="bar-chart-2"></i>
                    </div>
                    <div class="stat-content">
                        <h3 style="font-size: 13px; color: #6b7280; font-weight: 500;">Total Revenue</h3>
                        <p style="color: #1f2937; font-size: 24px;">Rp 0</p>
                    </div>
                </div>
                
                <div class="stat-card" style="background: white; color: #1f2937;">
                    <div class="stat-icon" style="color: #6366f1; background: #eef2ff; padding: 15px; border-radius: 12px;">
                        <i data-feather="shopping-cart"></i>
                    </div>
                    <div class="stat-content">
                        <h3 style="font-size: 13px; color: #6b7280; font-weight: 500;">Total Reservations</h3>
                        <p style="color: #1f2937; font-size: 24px;">{{ $totalReservations }}</p>
                    </div>
                </div>
                
                <div class="stat-card" style="background: white; color: #1f2937;">
                    <div class="stat-icon" style="color: #6366f1; background: #eef2ff; padding: 15px; border-radius: 12px;">
                        <i data-feather="users"></i>
                    </div>
                    <div class="stat-content">
                        <h3 style="font-size: 13px; color: #6b7280; font-weight: 500;">Total Customers</h3>
                        <p style="color: #1f2937; font-size: 24px;">{{ $totalCustomers }}</p>
                    </div>
                </div>
                
                <div class="stat-card" style="background: white; color: #1f2937;">
                    <div class="stat-icon" style="color: #6366f1; background: #eef2ff; padding: 15px; border-radius: 12px;">
                        <i data-feather="home"></i>
                    </div>
                    <div class="stat-content">
                        <h3 style="font-size: 13px; color: #6b7280; font-weight: 500;">Available Rooms</h3>
                        <p style="color: #1f2937; font-size: 24px;">
                            {{ $availableRooms }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- RECENT RESERVATIONS -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h3>Recent Reservations</h3>
                </div>
                
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Guest</th>
                            <th>Room</th>
                            <th>Status</th>
                            <th>Amount</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentReservations as $res)
                        <tr>
                            <tr>
                                <td>{{ $res->guest_name }}</td>
                                <td>{{ $res->room->name ?? '-' }}</td>
                                <td>
                                    <span class="room-status {{ $res->status }}">
                                        {{ ucfirst($res->status) }}
                                    </span>
                                </td>
                                <td>Rp {{ number_format($res->total_price, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" style="text-align: center; color: #9ca3af; padding: 40px;">
                                    No recent reservations
                                </td>
                            </tr>
                            @endforelse
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- ALL RESERVATIONS -->
            <div class="dashboard-card">
                <div class="card-header">
                    <tbody>
                        @forelse($recentReservations as $res)
                            @empty
                            <tr>
                                <td colspan="4" style="text-align: center; color: #9ca3af; padding: 40px;">
                                    No recent reservations
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </div>
                
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>GUEST</th>
                            <th>ROOM</th>
                            <th>STATUS</th>
                            <th>PRICE</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            @forelse($recentReservations as $res)
                            <tr>
                                <td>{{ $res->guest_name }}</td>
                                <td>{{ $res->room->name ?? '-' }}</td>
                                <td>
                                    <span class="room-status {{ $res->status }}">
                                        {{ ucfirst($res->status) }}
                                    </span>
                                </td>
                                <td>Rp {{ number_format($res->total_price, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" style="text-align: center; color: #9ca3af; padding: 40px;">
                                    No recent reservations
                                </td>
                            </tr>
                            @endforelse
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ROOMS SECTION -->
<div class="content-section" id="rooms">
    <div class="dashboard-card">
        <div class="card-header">
            <h3>Manajemen Kamar</h3>
            <button class="btn-primary" onclick="openModal('addRoomModal')">
                <i data-feather="plus"></i>
                Tambah Kamar Baru
            </button>
        </div>
        
        @if(session('success'))
            <div class="alert alert-success">
                <i data-feather="check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        
        <div class="rooms-grid">
            @forelse($rooms as $room)
            <div class="room-card">
                <img src="{{ $room->image ? asset('storage/' . $room->image) : 'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?w=400' }}" alt="{{ $room->name }}" class="room-image">
                <div class="room-content">
                    <div class="room-header">
                        <h4>{{ $room->name }}</h4>
                        <span class="room-status {{ $room->status }}">
                            {{ ucfirst($room->status) }}
                        </span>
                    </div>
                    <div class="room-details">
                        @if($room->length && $room->width)
                        <p>📐 Ukuran: {{ $room->length }}x{{ $room->width }} meter</p>
                        @endif
                        <p>🛏️ Tipe: {{ ucfirst($room->type) }}</p>
                        <p>👥 Kapasitas: {{ $room->capacity }} orang</p>
                        @if($room->facilities)
                        {{ is_array($room->facilities) ? implode(', ', $room->facilities) : $room->facilities }}
                        @endif
                    </div>
                    <div class="room-price">Rp {{ number_format($room->price, 0, ',', '.') }}/malam</div>
                    <div class="room-actions">
                        <button type="button" class="btn-primary" onclick="editRoom({{ $room->id }})">Edit</button>
                        <form action="{{ route('admin.rooms.destroy', $room) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-danger" onclick="return confirm('Yakin ingin menghapus kamar ini?')">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <p style="grid-column: 1/-1; text-align: center; color: #9ca3af; padding: 40px;">Belum ada kamar. Klik "Tambah Kamar Baru" untuk memulai.</p>
            @endforelse
        </div>
    </div>
</div>

        <!-- TENANTS SECTION -->
        <div class="content-section" id="tenants">
            <div class="dashboard-card">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Kamar</th>
                            <th>No. Telepon</th>
                            <th>Tanggal Masuk</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Budi Santoso</td>
                            <td>Kamar #102</td>
                            <td>081234567890</td>
                            <td>01 Jan 2026</td>
                            <td><span class="room-status available">Aktif</span></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="action-btn view">Detail</button>
                                    <button class="action-btn edit">Edit</button>
                                    <button class="action-btn delete">Hapus</button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Siti Aminah</td>
                            <td>Kamar #105</td>
                            <td>082345678901</td>
                            <td>15 Des 2025</td>
                            <td><span class="room-status available">Aktif</span></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="action-btn view">Detail</button>
                                    <button class="action-btn edit">Edit</button>
                                    <button class="action-btn delete">Hapus</button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Ahmad Ridwan</td>
                            <td>Kamar #108</td>
                            <td>083456789012</td>
                            <td>20 Des 2025</td>
                            <td><span class="room-status available">Aktif</span></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="action-btn view">Detail</button>
                                    <button class="action-btn edit">Edit</button>
                                    <button class="action-btn delete">Hapus</button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- PAYMENTS SECTION -->
        <div class="content-section" id="payments">
            <div class="dashboard-card">
                <div class="card-header">
                    <h3>Riwayat Pembayaran</h3>
                </div>
                
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Penyewa</th>
                            <th>Kamar</th>
                            <th>Jumlah</th>
                            <th>Metode</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>11 Jan 2026</td>
                            <td>Budi Santoso</td>
                            <td>Kamar #102</td>
                            <td>Rp 1.500.000</td>
                            <td>Transfer Bank</td>
                            <td><span class="room-status available">Lunas</span></td>
                            <td>
                                <button class="action-btn view">Lihat Bukti</button>
                            </td>
                        </tr>
                        <tr>
                            <td>10 Jan 2026</td>
                            <td>Siti Aminah</td>
                            <td>Kamar #105</td>
                            <td>Rp 1.800.000</td>
                            <td>Transfer Bank</td>
                            <td><span class="room-status occupied">Pending</span></td>
                            <td>
                                <button class="action-btn view">Lihat Bukti</button>
                            </td>
                        </tr>
                        <tr>
                            <td>05 Jan 2026</td>
                            <td>Ahmad Ridwan</td>
                            <td>Kamar #108</td>
                            <td>Rp 2.000.000</td>
                            <td>Cash</td>
                            <td><span class="room-status available">Lunas</span></td>
                            <td>
                                <button class="action-btn view">Detail</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- MAINTENANCE SECTION -->
        <div class="content-section" id="maintenance">
            <div class="dashboard-card">
                <div class="card-header">
                    <h3>Daftar Perbaikan</h3>
                    <button class="btn-primary" onclick="openModal('addMaintenanceModal')">
                        <i data-feather="plus"></i>
                        Tambah Perbaikan
                    </button>
                </div>
                
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Kamar</th>
                            <th>Jenis Perbaikan</th>
                            <th>Deskripsi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>10 Jan 2026</td>
                            <td>Kamar #105</td>
                            <td>AC</td>
                            <td>AC tidak dingin</td>
                            <td><span class="room-status occupied">Pending</span></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="action-btn edit">Update Status</button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>08 Jan 2026</td>
                            <td>Kamar #112</td>
                            <td>Listrik</td>
                            <td>Lampu kamar mati</td>
                            <td><span class="room-status available">Selesai</span></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="action-btn view">Detail</button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>05 Jan 2026</td>
                            <td>Kamar #103</td>
                            <td>Plumbing</td>
                            <td>Keran bocor</td>
                            <td><span class="room-status available">Selesai</span></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="action-btn view">Detail</button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- REPORTS SECTION -->
        <div class="content-section" id="reports">
            <div class="dashboard-card">
                <div style="margin-bottom: 30px;">
                    <div class="stats-grid">
                        <div class="stat-card green">
                            <div class="stat-icon">💵</div>
                            <div class="stat-content">
                                <h3>Pendapatan Bulan Ini</h3>
                                <p>Rp 45.000.000</p>
                            </div>
                        </div>
                        
                        <div class="stat-card blue">
                            <div class="stat-icon">📊</div>
                            <div class="stat-content">
                                <h3>Total Pendapatan 2026</h3>
                                <p>Rp 45.000.000</p>
                            </div>
                        </div>
                        
                        <div class="stat-card orange">
                            <div class="stat-icon">💳</div>
                            <div class="stat-content">
                                <h3>Pending Pembayaran</h3>
                                <p>Rp 3.600.000</p>
                            </div>
                        </div>
                        
                        <div class="stat-card pink">
                            <div class="stat-icon">🔧</div>
                            <div class="stat-content">
                                <h3>Biaya Perbaikan</h3>
                                <p>Rp 2.500.000</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <h4 style="margin-bottom: 15px; color: #1f2937;">Riwayat Pendapatan</h4>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Bulan</th>
                            <th>Total Pendapatan</th>
                            <th>Kamar Terisi</th>
                            <th>Tingkat Hunian</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Januari 2026</td>
                            <td>Rp 45.000.000</td>
                            <td>18/24</td>
                            <td>75%</td>
                        </tr>
                        <tr>
                            <td>Desember 2025</td>
                            <td>Rp 42.000.000</td>
                            <td>17/24</td>
                            <td>71%</td>
                        </tr>
                        <tr>
                            <td>November 2025</td>
                            <td>Rp 40.500.000</td>
                            <td>16/24</td>
                            <td>67%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- SETTINGS SECTION -->
        <div class="content-section" id="settings">
            <div class="dashboard-card">
                <div class="card-header">
                    <h3>Pengaturan Kos</h3>
                </div>
                
                <form>
                    <div class="form-group">
                        <label>Nama Kos</label>
                        <input type="text" class="form-control" value="Kosan Pak Rahmat (C5)" placeholder="Masukkan nama kos">
                    </div>
                    
                    <div class="form-group">
                        <label>Alamat Lengkap</label>
                        <textarea class="form-control" rows="3" placeholder="Masukkan alamat lengkap">Jln. Puri asri C.5, RT.09 RW.11, Kel. Sukapada, Kec.Cibeunying Kidul, Kota Bandung.40125.</textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Nomor Telepon</label>
                            <input type="tel" class="form-control" placeholder="08123456789">
                        </div>
                        
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" placeholder="email@example.com">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Fasilitas Umum</label>
                        <div class="facilities-input">
                            <span class="facility-tag selected">Parkir Motor</span>
                            <span class="facility-tag selected">Parkir Mobil</span>
                            <span class="facility-tag">Dapur Bersama</span>
                            <span class="facility-tag selected">Ruang Tamu</span>
                            <span class="facility-tag">Laundry</span>
                            <span class="facility-tag selected">CCTV</span>
                            <span class="facility-tag selected">Security 24 Jam</span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Peraturan Kos</label>
                        <textarea class="form-control" rows="5" placeholder="Masukkan peraturan kos"></textarea>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" class="btn-outline">Batal</button>
                        <button type="submit" class="btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<!-- ADD ROOM MODAL -->
<div class="modal" id="addRoomModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Tambah Kamar Baru</h3>
            <button class="close-modal" onclick="closeModal('addRoomModal')">&times;</button>
        </div>
        
        <form action="{{ route('admin.rooms.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>Nomor Kamar *</label>
                <input type="text" name="room_number" class="form-control" placeholder="Contoh: 101" required>
            </div>
            
            <div class="form-group">
                <label>Nama Kamar *</label>
                <input type="text" name="name" class="form-control" placeholder="Contoh: Kamar #101" required>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Tipe Kamar *</label>
                    <select name="type" class="form-control" required>
                            <option value="">Pilih Tipe</option>
                            <option value="single">Single</option>
                            <option value="double">Double</option>
                            <option value="suite">Suite</option>
                            <option value="couple">Couple</option>
                            <option value="luxury">Luxury</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Harga per Malam *</label>
                    <input type="number" name="price" class="form-control" placeholder="1500000" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Kapasitas (orang) *</label>
                    <input type="number" name="capacity" class="form-control" value="1" min="1" required>
                </div>
                
                <div class="form-group">
                    <label>Status *</label>
                    <select name="status" class="form-control" required>
                        <option value="available">Tersedia</option>
                        <option value="occupied">Terisi</option>
                        <option value="maintenance">Dalam Perbaikan</option>
                    </select>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Panjang (meter)</label>
                    <input type="number" name="length" class="form-control" placeholder="3" step="0.1">
                </div>
                
                <div class="form-group">
                    <label>Lebar (meter)</label>
                    <input type="number" name="width" class="form-control" placeholder="4" step="0.1">
                </div>
            </div>
            
            <div class="form-group">
                <label>Fasilitas Kamar</label>
                <div class="facilities-input">
                    <label><input type="checkbox" name="facilities[]" value="AC"> <span class="facility-tag">AC</span></label>
                    <label><input type="checkbox" name="facilities[]" value="WiFi"> <span class="facility-tag">WiFi</span></label>
                    <label><input type="checkbox" name="facilities[]" value="Kamar Mandi Dalam"> <span class="facility-tag">Kamar Mandi Dalam</span></label>
                    <label><input type="checkbox" name="facilities[]" value="Lemari"> <span class="facility-tag">Lemari</span></label>
                    <label><input type="checkbox" name="facilities[]" value="Meja Belajar"> <span class="facility-tag">Meja Belajar</span></label>
                    <label><input type="checkbox" name="facilities[]" value="Kasur"> <span class="facility-tag">Kasur</span></label>
                    <label><input type="checkbox" name="facilities[]" value="Dapur"> <span class="facility-tag">Dapur</span></label>
                </div>
            </div>
            
            <div class="form-group">
                <label>Upload Foto Kamar</label>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>
            
            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Deskripsi singkat tentang kamar"></textarea>
            </div>
            
            <div class="form-actions">
                <button type="button" class="btn-outline" onclick="closeModal('addRoomModal')">Batal</button>
                <button type="submit" class="btn-primary">Simpan Kamar</button>
            </div>
        </form>
    </div>
</div>

<div class="modal" id="editRoomModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Edit Kamar</h3>
            <button class="close-modal" onclick="closeModal('editRoomModal')">&times;</button>
        </div>

        <form id="editRoomForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Nomor Kamar</label>
                <input type="text" name="room_number" id="edit_room_number" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Nama Kamar</label>
                <input type="text" name="name" id="edit_name" class="form-control" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Tipe</label>
                    <select name="type" id="edit_type" class="form-control" required>
                        <option value="single">Single</option>
                        <option value="double">Double</option>
                        <option value="suite">Suite</option>
                        <option value="couple">Couple</option>
                        <option value="luxury">Luxury</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Harga</label>
                    <input type="number" name="price" id="edit_price" class="form-control" required>
                </div>
            </div>

            <div class="form-group">
                <label>Kapasitas</label>
                <input type="number" name="capacity" id="edit_capacity" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="status" id="edit_status" class="form-control" required>
                    <option value="available">Tersedia</option>
                    <option value="occupied">Terisi</option>
                    <option value="maintenance">Maintenance</option>
                </select>
            </div>

            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="description" id="edit_description" class="form-control"></textarea>
            </div>

            <div class="form-group">
                <label>Foto (opsional)</label>
                <input type="file" name="image" class="form-control">
            </div>

            <div class="form-actions">
                <button type="button" class="btn-outline" onclick="closeModal('editRoomModal')">Batal</button>
                <button type="submit" class="btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>

<!-- ADD TENANT MODAL -->
<div class="modal" id="addTenantModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Tambah Penyewa Baru</h3>
            <button class="close-modal" onclick="closeModal('addTenantModal')">&times;</button>
        </div>
        
        <form>
            <div class="form-group">
                <label>Nama Lengkap *</label>
                <input type="text" class="form-control" placeholder="Masukkan nama lengkap" required>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Nomor Telepon *</label>
                    <input type="tel" class="form-control" placeholder="08123456789" required>
                </div>
                
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" class="form-control" placeholder="email@example.com">
                </div>
            </div>
            
            <div class="form-group">
                <label>Pilih Kamar *</label>
                <select class="form-control" required>
                    <option value="">Pilih Kamar</option>
                    <option value="101">Kamar #101 - Tersedia</option>
                    <option value="103">Kamar #103 - Tersedia</option>
                    <option value="107">Kamar #107 - Tersedia</option>
                </select>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Tanggal Masuk *</label>
                    <input type="date" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label>Durasi Sewa (bulan)</label>
                    <input type="number" class="form-control" placeholder="6" min="1">
                </div>
            </div>
            
            <div class="form-group">
                <label>Upload KTP</label>
                <input type="file" class="form-control" accept="image/*">
            </div>
            
            <div class="form-group">
                <label>Catatan</label>
                <textarea class="form-control" rows="3" placeholder="Catatan tambahan"></textarea>
            </div>
            
            <div class="form-actions">
                <button type="button" class="btn-outline" onclick="closeModal('addTenantModal')">Batal</button>
                <button type="submit" class="btn-primary">Simpan Penyewa</button>
            </div>
        </form>
    </div>
</div>

<!-- ADD MAINTENANCE MODAL -->
<div class="modal" id="addMaintenanceModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Tambah Perbaikan</h3>
            <button class="close-modal" onclick="closeModal('addMaintenanceModal')">&times;</button>
        </div>
        
        <form>
            <div class="form-group">
                <label>Pilih Kamar *</label>
                <select class="form-control" required>
                    <option value="">Pilih Kamar</option>
                    <option value="101">Kamar #101</option>
                    <option value="102">Kamar #102</option>
                    <option value="103">Kamar #103</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Jenis Perbaikan *</label>
                <select class="form-control" required>
                    <option value="">Pilih Jenis</option>
                    <option value="ac">AC</option>
                    <option value="listrik">Listrik</option>
                    <option value="plumbing">Plumbing</option>
                    <option value="furniture">Furniture</option>
                    <option value="lainnya">Lainnya</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Deskripsi Masalah *</label>
                <textarea class="form-control" rows="3" placeholder="Jelaskan masalah yang perlu diperbaiki" required></textarea>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Estimasi Biaya</label>
                    <input type="number" class="form-control" placeholder="500000">
                </div>
                
                <div class="form-group">
                    <label>Prioritas</label>
                    <select class="form-control">
                        <option value="low">Rendah</option>
                        <option value="medium" selected>Sedang</option>
                        <option value="high">Tinggi</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label>Upload Foto (opsional)</label>
                <input type="file" class="form-control" multiple accept="image/*">
            </div>
            
            <div class="form-actions">
                <button type="button" class="btn-outline" onclick="closeModal('addMaintenanceModal')">Batal</button>
                <button type="submit" class="btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- FEATHER ICONS -->
<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>

<script>
// Initialize Feather Icons
feather.replace();

// Tab Navigation
document.querySelectorAll('.menu-item').forEach(item => {
    item.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Remove active class from all menu items
        document.querySelectorAll('.menu-item').forEach(mi => mi.classList.remove('active'));
        
        // Add active class to clicked item
        this.classList.add('active');
        
        // Hide all content sections
        document.querySelectorAll('.content-section').forEach(section => {
            section.classList.remove('active');
        });
        
        // Show selected content section
        const tabId = this.getAttribute('data-tab');
        document.getElementById(tabId).classList.add('active');
        
        // Update page title
        const tabTitle = this.querySelector('span').textContent;
        document.querySelector('.content-header h1').textContent = tabTitle;
        
        // Refresh icons
        feather.replace();
    });
});

// Modal Functions
function openModal(modalId) {
    document.getElementById(modalId).classList.add('active');
    feather.replace();
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('active');
}

// Close modal when clicking outside
document.querySelectorAll('.modal').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.remove('active');
        }
    });
});

// Facility Tags Selection
document.querySelectorAll('.facility-tag').forEach(tag => {
    tag.addEventListener('click', function() {
        this.classList.toggle('selected');
    });
});

// Initialize icons on page load
document.addEventListener('DOMContentLoaded', function() {
    feather.replace();
});

async function editRoom(id) {
    try {
        const res = await fetch(`/admin/rooms/${id}`);
        if (!res.ok) throw new Error('Gagal mengambil data kamar');

        const room = await res.json();

        const form = document.getElementById('editRoomForm');
        form.action = `/admin/rooms/${id}`;

        document.getElementById('edit_room_number').value = room.room_number;
        document.getElementById('edit_name').value = room.name;
        document.getElementById('edit_type').value = room.type.toLowerCase();
        document.getElementById('edit_price').value = room.price;
        document.getElementById('edit_capacity').value = room.capacity;
        document.getElementById('edit_status').value = room.status.toLowerCase();
        document.getElementById('edit_description').value = room.description || '';

        openModal('editRoomModal');
    } catch (e) {
        alert('Error: ' + e.message);
        console.error(e);
    }
}

// Auto switch to active tab from session
document.addEventListener('DOMContentLoaded', function() {
    feather.replace();
    
    // Check if there's an active tab from redirect
    const activeTab = '{{ session("active_tab") }}';
    if (activeTab) {
        // Remove active class from all
        document.querySelectorAll('.menu-item').forEach(mi => mi.classList.remove('active'));
        document.querySelectorAll('.content-section').forEach(section => {
            section.classList.remove('active');
        });
        
        // Activate the specific tab
        const menuItem = document.querySelector(`[data-tab="${activeTab}"]`);
        const contentSection = document.getElementById(activeTab);
        
        if (menuItem && contentSection) {
            menuItem.classList.add('active');
            contentSection.classList.add('active');
            
            // Update page title
            const tabTitle = menuItem.querySelector('span').textContent;
            document.querySelector('.content-header h1').textContent = tabTitle;
        }
        
        // Refresh icons
        feather.replace();
    }
    
    // Auto-hide alert after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
});

</script>
@endsection