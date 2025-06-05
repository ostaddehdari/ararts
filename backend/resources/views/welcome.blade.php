<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AR Arts</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=montserrat:300,400,500,600,700" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Montserrat', sans-serif;
            background: #000;
            color: #fff;
            overflow-x: hidden;
        }

        .video-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        .video-container video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .content {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 2rem;
        }

        .logo {
            max-width: 200px;
            margin-bottom: 2rem;
        }

        .title {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .subtitle {
            font-size: 1.5rem;
            font-weight: 300;
            margin-bottom: 2rem;
            max-width: 600px;
            line-height: 1.6;
        }

        .cta-button {
            background: #fff;
            color: #000;
            padding: 1rem 2rem;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255,255,255,0.2);
        }

        .templates-section {
            width: 100%;
            padding: 4rem 2rem;
            background: rgba(0, 0, 0, 0.8);
        }

        .templates-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .template-item {
            position: relative;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        }

        .template-item video {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .template-info {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 1rem;
            background: linear-gradient(transparent, rgba(0,0,0,0.8));
            color: #fff;
        }

        .template-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .template-description {
            font-size: 0.9rem;
            opacity: 0.8;
        }

        @media (max-width: 768px) {
            .title {
                font-size: 2rem;
            }
            .subtitle {
                font-size: 1.2rem;
            }
            .templates-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="video-container">
        <video autoplay muted loop playsinline>
            <source src="https://cdn.artivive.com/videos/template3.webm" type="video/webm">
        </video>
    </div>

    <div class="content">
        <img src="{{ asset('images/logo.png') }}" alt="AR Arts Logo" class="logo">
        <h1 class="title">هنر واقعیت افزوده</h1>
        <p class="subtitle">تجربه هنر به روشی جدید و تعاملی با استفاده از فناوری واقعیت افزوده</p>
        <a href="{{ route('login') }}" class="cta-button">شروع کنید</a>
    </div>

    <section class="templates-section">
        <div class="templates-grid">
            <div class="template-item">
                <video autoplay muted loop playsinline>
                    <source src="https://cdn.artivive.com/videos/template4.webm" type="video/webm">
                </video>
                <div class="template-info">
                    <h3 class="template-title">قالب ۱</h3>
                    <p class="template-description">قالب آماده برای نمایش آثار هنری</p>
                </div>
            </div>
            <div class="template-item">
                <video autoplay muted loop playsinline>
                    <source src="https://cdn.artivive.com/videos/template5.webm" type="video/webm">
                </video>
                <div class="template-info">
                    <h3 class="template-title">قالب ۲</h3>
                    <p class="template-description">قالب آماده برای نمایش آثار هنری</p>
                </div>
            </div>
            <div class="template-item">
                <video autoplay muted loop playsinline>
                    <source src="https://cdn.artivive.com/videos/template-valentine3.mp4" type="video/mp4">
                </video>
                <div class="template-info">
                    <h3 class="template-title">قالب ۳</h3>
                    <p class="template-description">قالب آماده برای نمایش آثار هنری</p>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Preload other videos
        const videos = [
            'https://cdn.artivive.com/videos/template4.webm',
            'https://cdn.artivive.com/videos/template5.webm',
            'https://cdn.artivive.com/videos/template-valentine3.mp4'
        ];

        videos.forEach(videoUrl => {
            const video = document.createElement('video');
            video.src = videoUrl;
            video.preload = 'auto';
            document.body.appendChild(video);
        });
    </script>
</body>
</html>
