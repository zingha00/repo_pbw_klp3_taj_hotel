<!-- FILE: resources/views/about.blade.php -->
@extends('layouts.app')

@section('title', 'About Us - Hotel Booking')

@section('content')
    <section class="page-header about-bg">
        <div class="container">
            <h1>About Us</h1>
            <p>Learn more about our hotel</p>
        </div>
    </section>

    <section class="about-section">
        <div class="container">
            <div class="about-content">
                <div class="about-image">
                    <img src="https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8M3x8aG90ZWx8ZW58MHx8MHx8fDA%3D"
                        alt="Hotel">
                </div>
                <div class="about-text">
                    <h2>Welcome to Our Luxury Hotel</h2>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quod. Voluptatum, quia. Quisquam,
                        quod. Voluptatum, quia.</p>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quod. Voluptatum, quia. Quisquam,
                        quod. Voluptatum, quia. Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>

                    <div class="features-grid">
                        <div class="feature-box">
                            <h3>
                                <i data-feather="home"></i>
                                Luxury Rooms
                            </h3>
                            <p>Premium rooms with modern facilities</p>
                        </div>

                        <div class="feature-box">
                            <h3>
                                <i data-feather="coffee"></i>
                                Restaurant
                            </h3>
                            <p>Delicious cuisine from expert chefs</p>
                        </div>

                        <div class="feature-box">
                            <h3>
                                <i data-feather="droplet"></i>
                                Swimming Pool
                            </h3>
                            <p>Olympic-size outdoor pool</p>
                        </div>

                        <div class="feature-box">
                            <h3>
                                <i data-feather="heart"></i>
                                Spa & Wellness
                            </h3>
                            <p>Relax and rejuvenate your body</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection