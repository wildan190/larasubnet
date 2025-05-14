<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <!-- CDN Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <!-- Custom CSS to make the sidebar fixed -->
    <style>
        /* Sidebar styling */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            /* Adjust width as needed */
            height: 100vh;
            background-color: #343a40;
            color: white;
            padding-top: 20px;
            overflow-y: auto;
        }

        .sidebar ul {
            padding-left: 0;
        }

        .sidebar ul li {
            list-style: none;
            padding: 10px;
        }

        .sidebar ul li a {
            color: white;
            text-decoration: none;
            font-size: 16px;
        }

        .sidebar ul li a:hover {
            background-color: #007bff;
        }

        /* Add some padding to the main content so it doesn't overlap the sidebar */
        .main-content {
            margin-left: 250px;
            /* Same width as sidebar */
            padding-top: 20px;
        }

        .navbar {
            position: fixed;
            top: 0;
            left: 250px;
            /* same as sidebar width */
            right: 0;
            z-index: 1030;
            width: calc(100% - 250px);
        }

        .main-content {
            margin-top: 70px;
            /* adjust based on navbar height */
        }
    </style>
</head>

<body>
    <div class="d-flex">
        <!-- Sidebar -->
        @include('layouts.partials.sidebar')

        <!-- Main Content -->
        <div class="main-content w-100">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-light rounded-bottom shadow-sm">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#"></a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav ms-auto">
                            <!-- Logout Button -->
                            <li class="nav-item">
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-danger">
                                        Logout <i class="fa fa-sign-out-alt"></i>
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Main content area -->
            <div class="container-fluid mt-4">
                <div class="row">
                    <div class="col-12">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
