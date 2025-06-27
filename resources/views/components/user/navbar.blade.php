@php
    $isLandingPage = Route::currentRouteName() === 'user.landingpage';
@endphp

<!-- NAVBAR -->
<nav id="navbar" class="navbar navbar-expand-lg navbar-light fixed-top shadow-sm"
    style="{{ $isLandingPage ? 'background: transparent;' : 'background: linear-gradient(to right, #A28C4A, #241E01);' }} transition: background 0.3s ease;">
    <div class="container">
        <a class="navbar-brand fw-bold text-white" href="{{ route('user.landingpage') }}">
            <img src="{{ asset('images/logo.png') }}" alt="Eventara" width="30" height="30"
                class="d-inline-block align-text-top">
            Eventara
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active text-white" href="{{ route('user.landingpage') }}">Beranda</a>
                </li>
                <a class="nav-link text-white" href="{{ route('user.events') }}">Event</a>

                <li class="nav-item"><a class="nav-link text-white" href="#">About</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="#">Contact</a></li>

                @guest
                    <li class="nav-item">
                        <a class="btn btn-outline-light me-2" href="{{ route('user.login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn text-white" style="background-color: #8B5E3C;" href="{{ route('user.register') }}">Register</a>
                    </li>
                @endguest

                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white d-flex align-items-center" href="#"
                            id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=F4A825&color=fff"
                                alt="Avatar" class="rounded-circle me-2" width="32" height="32">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="{{ route('user.profil') }}">Profil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="#" id="logout-btn">Logout</a>
                                <form id="logout-form" action="{{ route('user.logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- SCRIPT LOGOUT DAN SCROLL -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const logoutBtn = document.getElementById("logout-btn");
        const logoutForm = document.getElementById("logout-form");

        if (logoutBtn && logoutForm) {
            logoutBtn.addEventListener("click", function (e) {
                e.preventDefault();

                Swal.fire({
                    title: "Konfirmasi Logout",
                    text: "Apakah Anda yakin ingin logout?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Logout",
                    cancelButtonText: "Batal"
                }).then((result) => {
                    if (result.isConfirmed) {
                        logoutForm.submit();
                    }
                });
            });
        }

        // Efek scroll hanya untuk landing page
        @if($isLandingPage)
        window.addEventListener('scroll', function () {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 10) {
                navbar.style.background = 'linear-gradient(to right, #A28C4A, #241E01)';
            } else {
                navbar.style.background = 'transparent';
            }
        });
        @endif
    });
</script>
