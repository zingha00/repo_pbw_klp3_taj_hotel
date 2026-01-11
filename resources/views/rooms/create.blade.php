@extends('layouts.app')

@section('title', 'Add New Room - Admin')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-room-form.css') }}">
@endpush

@section('content')
    <section class="admin-form-page">
        <div class="container">
            <div class="form-header">
                <div>
                    <h1>Add New Room</h1>
                    <p>Fill in the details to add a new room to the hotel</p>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="btn-back-dashboard">
                    ← Back to Dashboard
                </a>
            </div>

            <div class="form-card">
                <form action="{{ route('admin.rooms.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-grid">
                        @include('admin.rooms.partials.form-left')
                        @include('admin.rooms.partials.form-right')
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('admin.dashboard') }}" class="btn-cancel">Cancel</a>
                        <button type="submit" class="btn-save">💾 Save Room</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('js/admin-room-form.js') }}"></script>
@endpush