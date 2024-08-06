<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('images/Vector-white.svg') }}">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <style>
        :root {
            --primary-color: #1e90ff;
            --secondary-color: #4169e1;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
        }

        body {
            font-family: 'Roboto', sans-serif;
            padding-top: 56px;
        }

        .navbar {
            transition: all 0.3s ease;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
        }

        .navbar::before,
        .navbar::after {
            content: '';
            position: absolute;
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.1);
            transform: rotate(45deg);
        }

        .navbar::before {
            top: -50px;
            left: -50px;
        }

        .navbar::after {
            bottom: -50px;
            right: -50px;
        }

        .navbar-brand,
        .nav-link {
            color: white !important;
            position: relative;
            z-index: 1;
        }

        .navbar.scrolled {
            background: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, .1);
        }

        .navbar.scrolled::before,
        .navbar.scrolled::after {
            display: none;
        }

        .navbar.scrolled .navbar-brand,
        .navbar.scrolled .nav-link {
            color: var(--dark-color) !important;
        }

        .navbar.scrolled .nav-link:hover {
            color: var(--primary-color) !important;
        }

        .navbar.scrolled .btn-enroll {
            background-color: var(--primary-color);
            color: white !important;
        }

        .btn-enroll {
            background-color: white;
            color: var(--primary-color) !important;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .btn-enroll:hover {
            background-color: var(--light-color);
            color: var(--primary-color) !important;
        }

        .hero {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 100px 0;
            position: relative;
            overflow: hidden;
        }

        .hero::before,
        .hero::after {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.1);
            transform: rotate(45deg);
        }

        .hero::before {
            top: -150px;
            left: -150px;
        }

        .hero::after {
            bottom: -150px;
            right: -150px;
        }

        #appCarousel {
            width: 100%;
            max-height: 800px;
            overflow: hidden;
        }

        .carousel-item {
            height: 800px;
        }

        .carousel-item img {
            object-fit: cover;
            height: 100%;
            width: 100%;
            border-top-left-radius: 20px;
            border-bottom-left-radius: 20px;
        }

        #features {
            background-color: white;
            padding: 80px 0;
        }

        .feature-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: var(--primary-color);
        }

        #contact {
            background-color: var(--dark-color);
            color: white;
            padding: 80px 0 0 0;
            position: relative;
            overflow: hidden;
        }

        #contact::before,
        #contact::after {
            content: '';
            position: absolute;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.05);
            transform: rotate(45deg);
        }

        #contact::before {
            top: -100px;
            left: -100px;
        }

        #contact::after {
            bottom: -100px;
            right: -100px;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .scroll-animate {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.5s ease, transform 0.5s ease;
        }

        .scroll-animate.show {
            opacity: 1;
            transform: translateY(0);
        }

        #clients {
            background-color: var(--light-color);
            padding: 80px 0;
        }

        .client-logo {
            max-width: 150px;
            height: auto;
            filter: grayscale(100%);
            transition: all 0.3s ease;
        }

        .client-logo:hover {
            filter: grayscale(0%);
            transform: scale(1.1);
        }

        .client-name {
            margin-top: 10px;
            font-weight: bold;
            color: var(--dark-color);
        }

        #video-section {
            background-color: var(--light-color);
            padding: 80px 0;
        }

        .video-container {
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
            max-width: 100%;
        }

        .video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>

    <body>
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand" href="#">
                    <img src="{{ asset('images/Vector-white.svg') }}" alt="Logo" id="logo"
                        class="brand-image text-start opacity-75 shadow"> {{ config('app.name') }}</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="#features">Nos fonctionnalités</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#clients">Clients</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#contact">Contact</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-enroll ms-2"
                                href="https://websimcreation.engine/app-school/enroll">Commencer</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <section class="hero">
            <div class="container-fluid px-0">
                <div class="row g-0 align-items-center">
                    <div class="col-md-6 mb-4 mb-md-0">
                        <div class="px-4 py-5 text-center text-md-start scroll-animate">
                            <h1 class="display-4 mb-4">Bienvenue sur {{ config('app.name') }}</h1>
                            <p class="lead mb-5">Simplifier le processus de gestion de votre école en un clic et rester
                                en contacter les parent de vos élèves.</p>
                            <a href="https://websimcreation.engine/app-school/enroll"
                                class="btn btn-lg btn-light">Commencer</a>
                        </div>
                    </div>
                    <div class="col-md-6 scroll-animate">
                        <div id="appCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="{{ asset('images/car-1.jpg') }}" alt="App Dashboard 1"
                                        class="d-block w-100">
                                </div>
                                <div class="carousel-item">
                                    <img src="{{ asset('images/car-2.jpg') }}" alt="App Dashboard 2"
                                        class="d-block w-100">
                                </div>
                                <div class="carousel-item">
                                    <img src="{{ asset('images/car-3.png') }}" alt="App Dashboard 3"
                                        class="d-block w-100">
                                </div>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#appCarousel"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#appCarousel"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="features">
            <div class="container">
                <h2 class="text-center mb-5 scroll-animate">Nos fonctionnalités</h2>
                <div class="row">
                    <div class="col-md-4 mb-4 scroll-animate">
                        <div class="text-center">
                            <div class="feature-icon">
                                <i class="bi bi-laptop"></i>
                            </div>
                            <h3>E-Learning</h3>
                            <p>Accédez à notre plateforme d'apprentissage en ligne complète à tout moment et en tout
                                lieu</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4 scroll-animate">
                        <div class="text-center">
                            <div class="feature-icon">
                                <i class="bi bi-cash-coin"></i>
                            </div>
                            <h3>Finance</h3>
                            <p>
                                Apprenez la gestion financière et la budgétisation de vos projets d'applications</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4 scroll-animate">
                        <div class="text-center">
                            <div class="feature-icon">
                                <i class="bi bi-clipboard-data"></i>
                            </div>
                            <h3>Administration</h3>
                            <p>
                                Maîtrisez les compétences administratives nécessaires pour gérer avec succès des
                                entreprises d'applications</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="video-section">
            <div class="container">
                <h2 class="text-center mb-5 scroll-animate">Voir Schoola en action</h2>
                <div class="row justify-content-center">
                    <div class="col-lg-8 scroll-animate">
                        <div class="video-container">
                            <iframe width="560" height="315" src="https://youtu.be/5n-tiNha5kw" frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="clients">
            <div class="container">
                <h2 class="text-center mb-5 scroll-animate">Nos clients</h2>
                <div class="row justify-content-center align-items-center">
                    <div class="col-6 col-md-3 mb-4 scroll-animate text-center">
                        <img src="{{ asset('images/place-holder-logo.png') }}" alt="TechNova Logo"
                            class="img-fluid client-logo">
                        <p class="client-name">C.S. AQUILA</p>
                    </div>
                    <div class="col-6 col-md-3 mb-4 scroll-animate text-center">
                        <img src="{{ asset('images/place-holder-logo.png') }}" alt="DataSphere Logo"
                            class="img-fluid client-logo">
                        <p class="client-name">C.S TEST</p>
                    </div>

                </div>
            </div>
        </section>

        <section id="contact">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 scroll-animate">
                        <h2 class="mb-4">Contactez-nous</h2>
                        <p>Avoir des questions? Nous sommes là pour vous aider !</p>
                        <p>Email: <a href="mailto:info@appschool.com" class="text-white">info@schoola.com</a></p>
                        <p>Phone: +(243) 971-330-007</p>
                    </div>
                    <div class="col-md-6 scroll-animate">
                        <h2 class="mb-4">Entrer en contact</h2>
                        <form action="https://websimcreation.engine/app-school/contact" method="POST">
                            <div class="mb-3">
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Votre nom" required>
                            </div>
                            <div class="mb-3">
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="Votre Email" required>
                            </div>
                            <div class="mb-3">
                                <textarea class="form-control" id="message" name="message" rows="4" placeholder="Entre le message"
                                    required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Envoyer Message</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <footer class="bg-dark text-white text-center py-3">
            <p>&copy; 2024 {{ config('app.name') }}. Tous droits réservés.</p>
        </footer>
        <script>
            // Navbar behavior on scroll
            const navbar = document.querySelector('.navbar');
            const logo = document.getElementById('logo');

            window.addEventListener('scroll', function() {
                if (window.scrollY > 50) {
                    navbar.classList.add('scrolled');
                    logo.style.display = 'none';
                } else if (window.scrollY == 0) {
                    logo.style.display = 'block';
                    console.log("Top");
                } else {
                    navbar.classList.remove('scrolled');

                }
            });

            // Smooth scrolling for navbar links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.querySelector(this.getAttribute('href')).scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });

            // Scroll animation
            const animateOnScroll = () => {
                const elements = document.querySelectorAll('.scroll-animate');
                elements.forEach(item => {
                    const itemTop = item.getBoundingClientRect().top;
                    const itemBottom = item.getBoundingClientRect().bottom;
                    if (itemTop < window.innerHeight - 100 && itemBottom > 0) {
                        item.classList.add('show');
                    }
                });
            };

            window.addEventListener('scroll', animateOnScroll);
            window.addEventListener('load', animateOnScroll);
        </script>
    </body>

</html>
