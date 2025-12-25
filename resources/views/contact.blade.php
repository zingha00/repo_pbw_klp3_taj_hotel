<!-- FILE: resources/views/contact.blade.php -->
@extends('layouts.app')

@section('title', 'Contact Us - Hotel Booking')

@section('content')

    <!-- PAGE HEADER -->
    <section class="page-header contact-bg">
        <div class="container">
            <h1>Contact Us</h1>
            <p>Get in touch with us</p>
        </div>
    </section>

    <!-- CONTACT SECTION -->
    <section class="contact-section">
        <div class="container">
            <div class="contact-grid">

                <!-- CONTACT INFO -->
                <div class="contact-info">
                    <h2>Get In Touch</h2>
                    <p>
                        Lorem ipsum dolor sit amet consectetur adipisicing elit.
                        Quisquam, quod. We are always ready to help you.
                    </p>

                    <div class="info-item">
                        <strong>
                            <i data-feather="map-pin"></i>
                            Address
                        </strong>
                        <p>Jl. Hotel Mewah No. 123, Jakarta, Indonesia</p>
                    </div>

                    <div class="info-item">
                        <strong>
                            <i data-feather="phone"></i>
                            Phone
                        </strong>
                        <p>+62 812-3456-7890</p>
                    </div>

                    <div class="info-item">
                        <strong>
                            <i data-feather="mail"></i>
                            Email
                        </strong>
                        <p>info@hotel.com</p>
                    </div>

                    <div class="info-item">
                        <strong>
                            <i data-feather="clock"></i>
                            Working Hours
                        </strong>
                        <p>24/7 Available</p>
                    </div>
                </div>

                <!-- CONTACT FORM -->
                <div class="contact-form-wrapper">
                    <h2>Send Us a Message</h2>

                    <form class="contact-form" method="POST" action="#">
                        @csrf

                        <div class="form-group">
                            <input type="text" name="name" placeholder="Your Name" required>
                        </div>

                        <div class="form-group">
                            <input type="email" name="email" placeholder="Your Email" required>
                        </div>

                        <div class="form-group">
                            <input type="text" name="subject" placeholder="Subject" required>
                        </div>

                        <div class="form-group">
                            <textarea name="message" rows="5" placeholder="Your Message" required></textarea>
                        </div>

                        <button type="submit" class="btn-orange">
                            Send Message
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </section>

@endsection