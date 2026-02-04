<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>About - LingNaija</title>
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    </head>
    <body>
        @include('nav')

        <div class="container">
            <div class="about-section">
                <h1 class="page-title">About LingNaija</h1>
                <p class="about-intro">
                    Welcome to LingNaija – your intelligent partner in mastering new languages.
                    We believe language learning should be smart, engaging, and connected.
                </p>

                <div class="about-content">
                    <h2>Our Mission</h2>
                    <p>
                        To make language learning accessible, effective, and enjoyable for everyone,
                        using smart technology and personalized approaches.
                    </p>

                    <h2>What We Offer</h2>
                    <ul class="features-list">
                        <li>📚 Interactive lessons tailored to your level</li>
                        <li>🗣️ Real-time pronunciation practice</li>
                        <li>🌍 Connect with native speakers worldwide</li>
                        <li>📊 Smart progress tracking</li>
                        <li>🎮 Gamified learning experiences</li>
                    </ul>

                    <h2>Our Story</h2>
                    <p>
                        Founded in 2024, LingNaija started as a final-year project by students of
                        Lincoln College and language enthusiasts with a passion for bridging communication
                        gaps. Today, it focuses on helping learners around the world learn Nigerian languages.
                    </p>

                    <h2>Why Choose LingNaija?</h2>
                    <div class="why-us">
                        <div class="reason">
                            <h3>Smart Learning</h3>
                            <p>AI-powered recommendations adapt to your pace and style.</p>
                        </div>
                        <div class="reason">
                            <h3>Community Driven</h3>
                            <p>Learn with and from a global community of language lovers.</p>
                        </div>
                        <div class="reason">
                            <h3>Always Evolving</h3>
                            <p>We continuously update our content and features based on user feedback.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('footer')
    </body>
</html>
