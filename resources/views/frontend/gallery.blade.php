<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Gallery - Uploader</title>
    <link rel="shortcut icon" href="{{ asset('img/icons/icon-48x48.png') }}" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <style>
        body {
            font-family: 'Segoe UI', Roboto, sans-serif;
            background-color: #f8f9fa;
        }

        .logo {
            color: #ff3366;
            animation: colorShift 6s linear infinite;
            font-size: 1.8rem;
            font-weight: 800;
            text-decoration: none;
        }

        @keyframes colorShift {
            0% { color: #ff3366; }
            25% { color: #ffcc00; }
            50% { color: #00c3ff; }
            75% { color: #4834d4; }
            100% { color: #ff3366; }
        }

        .section-title {
            background: linear-gradient(90deg, blue, violet);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: bold;
        }

        .gallery-card {
            border-radius: 16px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
            transition: transform 0.4s ease, box-shadow 0.4s ease;
            cursor: pointer;
        }

        .gallery-card:hover {
            transform: scale(1.05);
            box-shadow: 0 12px 25px rgba(0,0,0,0.15);
            z-index: 10;
        }

        .gallery-card img {
            width: 100%;
            height: 220px;
            object-fit: cover;
        }

        .btn-gradientshow {
            color: white;
            border: none;
            border-top-left-radius: 20px;
            border-bottom-right-radius: 20px;
            background: linear-gradient(270deg, yellow, green, cyan, blue, violet);
            background-size: 600% 600%;
            transition: all 0.4s ease-in-out;
        }

        .btn-gradientshow:hover {
            background-position: right center;
            box-shadow: 0 4px 15px rgba(23, 162, 184, 0.4);
            color: #fff;
        }

        footer {
            background: #222;
            color: #ddd;
            padding: 60px 0;
            margin-top: 60px;
        }

        footer a { color: #ddd; text-decoration: none; }
        footer a:hover { color: #0d6efd; }
    </style>
</head>

<body>
    
    @include('frontend.layouts.navbar')

    <!-- Gallery Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5 section-title">Our Gallery</h2>

            <div class="row g-4" id="gallery-container">
                @foreach($allgallery->take(9) as $item)
                <div class="col-md-6 col-lg-4 gallery-item">
                    <div class="gallery-card">
                        <img src="{{ Storage::disk('s3')->temporaryUrl($item->image, now()->addMinutes(120)) }}" alt="Gallery Image">
                    </div>
                </div>
                @endforeach
            </div>

            @if($allgallery->count() > 9)
            <div class="text-center mt-4">
                <button id="load-more-gallery" class="btn btn-gradientshow">Load More</button>
            </div>
            @endif
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row gy-4">
                <div class="col-md-4">
                    <h5 class="fw-bold">Uploader</h5>
                    <p>Uploader is your trusted platform for sharing and managing content. Building seamless experiences since 2024.</p>
                </div>
                <div class="col-md-4">
                    <h5 class="fw-bold">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('homepage') }}">Home</a></li>
                        <li><a href="{{ route('news') }}">News</a></li>
                        <li><a href="{{ route('banners') }}">Gallery</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5 class="fw-bold">Contact</h5>
                    <p><i class="bi bi-geo-alt-fill me-2"></i>Jaipur, India</p>
                    <p><i class="bi bi-telephone-fill me-2"></i>+91 9876543210</p>
                    <p><i class="bi bi-envelope-fill me-2"></i>support@uploader.com</p>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center small">&copy; 2025 Uploader. All Rights Reserved.</div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        let gOffset = 9;
        document.getElementById('load-more-gallery')?.addEventListener('click', function() {
            fetch(`{{ route('gallery.loadMore') }}?offset=${gOffset}`)
                .then(res => res.json())
                .then(data => {
                    if (data.length === 0) {
                        this.style.display = 'none';
                        return;
                    }
                    const container = document.getElementById('gallery-container');
                    data.forEach(item => {
                        const div = document.createElement('div');
                        div.classList.add('col-md-6', 'col-lg-4', 'gallery-item');
                        div.innerHTML = `
                            <div class="gallery-card">
                                <img src="{{ Storage::disk('s3')->temporaryUrl($item->image, now()->addMinutes(120)) }}" alt="Gallery Image">
                            </div>
                        `;
                        container.appendChild(div);
                    });
                    gOffset += 9;
                });
        });
    </script>
</body>
</html>
