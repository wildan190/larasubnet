<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />

    <style>
        /* Sidebar */
        .sidebar {
            background-color: #343a40;
            color: #fff;
            height: 100vh;
            padding-top: 20px;
        }

        .sidebar ul {
            padding-left: 0;
            margin: 0;
        }

        .sidebar ul li {
            list-style: none;
            padding: 10px;
        }

        .sidebar ul li a {
            color: #fff;
            text-decoration: none;
            font-size: 16px;
            display: block;
            transition: background-color 0.3s ease;
        }

        .sidebar ul li a:hover {
            background-color: #007bff;
        }

        /* Main content & Navbar */
        .main-content {
            padding-top: 70px;
        }

        .navbar {
            position: fixed;
            top: 0;
            right: 0;
            left: 0;
            z-index: 1030;
        }

        @media (min-width: 768px) {
            .sidebar {
                position: fixed;
                width: 250px;
                overflow-y: auto;
            }

            .main-content {
                margin-left: 250px;
            }

            .navbar {
                left: 250px;
                width: calc(100% - 250px);
            }
        }

        @media (max-width: 576px) {
            .sidebar ul li a {
                font-size: 14px;
                padding: 8px;
            }
            .navbar {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <div class="d-flex">
        {{-- Sidebar --}}
        <div class="sidebar d-md-block d-none">
            @include('layouts.partials.sidebar')
        </div>

        {{-- Offcanvas Sidebar for mobile --}}
        <div class="offcanvas offcanvas-start d-md-none" tabindex="-1" id="mobileSidebar" aria-labelledby="mobileSidebarLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="mobileSidebarLabel">Menu</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body p-2">
                @include('layouts.partials.sidebar')
            </div>
        </div>

        {{-- Main Content --}}
        <div class="main-content w-100">
            {{-- Navbar --}}
            <nav class="navbar navbar-expand-lg navbar-light bg-light rounded-bottom shadow-sm">
                <div class="container-fluid">
                    <button class="btn btn-primary btn-sm d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar" aria-controls="mobileSidebar">
                        <i class="fas fa-bars"></i>
                    </button>
                    <a class="navbar-brand ms-2" href="#">@yield('title')</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item">
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        Logout <i class="fa fa-sign-out-alt"></i>
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            {{-- Page Content --}}
            <div class="container-fluid mt-4">
                @yield('content')
            </div>
        </div>
    </div>

    {{-- Bootstrap Bundle JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
