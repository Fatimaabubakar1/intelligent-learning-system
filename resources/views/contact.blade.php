<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Contact - LingNaija</title>
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    </head>
    <body>
        @include('nav')

        <div class="container">
            <div class="contact-section">
                <h1 class="page-title">Get in Touch</h1>
                <p class="contact-intro">
                    We'd love to hear from you! Whether you have questions, feedback, or need support,
                    our team is here to help.
                </p>

                <div class="contact-content">
                    <div class="contact-form-container">
                        <h2>Send Us a Message</h2>

                        @include('success')

                        <form class="contact-form" action="{{ route('contact.submit') }}" method="POST">
                            @csrf

                            <div class="form-group">
                                <label for="name">Full Name *</label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="email">Email Address *</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="subject">Subject</label>
                                <input type="text" id="subject" name="subject" value="{{ old('subject') }}">
                                @error('subject')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="message">Your Message *</label>
                                <textarea id="message" name="message" rows="5" required>{{ old('message') }}</textarea>
                                @error('message')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            <button type="submit" class="btn-submit">Send Message</button>
                        </form>
                    </div>

                    <div class="contact-info">
                        <h2>Contact Information</h2>
                        <div class="info-item">
                            <h3>📍 Address</h3>
                            <p>25 Cairo Street, Wuse 2, Abuja</p>
                        </div>

                        <div class="info-item">
                            <h3>📧 Email</h3>
                            <p><a href="mailto:support@lingnaija.com">support@lingnaija.com</a></p>
                        </div>

                        <div class="info-item">
                            <h3>📞 Phone</h3>
                            <p><a href="tel:+2348091052696">+234 (809) 105-2696</a></p>
                        </div>

                        <div class="social-links">
                            <h3>Follow Us</h3>
                            <div class="social-icons">
                                <a href="https://facebook.com/lingnaija" target="_blank" class="social-icon" aria-label="Facebook">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="https://linkedin.com/company/lingnaija" target="_blank" class="social-icon" aria-label="LinkedIn">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('footer')
    </body>
</html>
