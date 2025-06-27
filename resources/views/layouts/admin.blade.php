<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EvenTara Admin</title>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
        }

        /* === Sidebar === */
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            width: 250px;
            background-color: #4e342e;
            color: #fff;
            padding: 20px 15px;
            overflow-y: auto;
            z-index: 1030;
        }

        .sidebar a {
            color: #fff;
            text-decoration: none;
        }

        .sidebar a:hover,
        .sidebar .active {
            background-color: #F4A825;
            color: #4e342e !important;
            font-weight: bold;
        }

        .sidebar .nav-link i {
            margin-right: 10px;
        }

        /* === Navbar Custom === */
        .navbar-custom {
            background-color: #ffffff;
            border-bottom: 1px solid #e0e0e0;
            margin-left: 250px; /* Geser karena sidebar fixed */
        }

        /* === Main Content === */
        .main-content {
            margin-left: 250px; /* Sama dengan sidebar width */
            padding: 20px;
        }

        /* Prevent content overlap on small screens */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                position: relative;
            }

            .navbar-custom,
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    @include('components.admin.sidebar')

    <!-- Navbar -->
    <div class="navbar-custom">
        @include('components.admin.navbar')
    </div>

    <!-- Main Content -->
    <div class="main-content">
        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
