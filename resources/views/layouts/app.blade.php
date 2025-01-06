<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta
        name="description"
        content="SafeSpeak is an AI-powered application that predicts whether text contains cyberbullying, identifying categories like flaming, harassment, and denigration. It also provides insights into Indonesian cyber laws (UU ITE), so users can understand the potential legal consequences of harmful online behavior."
    />
    <link rel="icon" href="{{ asset('/favicon/favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('/favicon/apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('/favicon/site.webmanifest') }}">
    <link rel="icon" sizes="192x192" href="{{ asset('/favicon/android-chrome-192x192.png') }}">
    <link rel="icon" sizes="512x512" href="{{ asset('/favicon/android-chrome-512x512.png') }}">
    <link
      href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;600;700&display=swap"
      rel="stylesheet"
    />
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.3/font/bootstrap-icons.min.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.canvasjs.com/jquery.canvasjs.min.js"></script>
    <style>
        /* Global Colors & Fonts */
        :root {
            --primary-blue: #1e3a8a;
            --light-blue: #3b82f6;
            --accent-blue: #93c5fd;
            --bg-color: rgb(240, 242, 245);
            --card-bg: #f8fafc;
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: "Rubik";
            background: var(--bg-color);
            position: relative;
        }

        /* Flex Utility */
        .flex {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Sidebar Styles */
        .sidebar-container {
            width: 260px;
            background: linear-gradient(rgb(66, 66, 74), rgb(28, 28, 28));
            color: #fff;
            border-radius: 12px;
            box-shadow: var(--shadow);
            position: fixed;
            height: 90vh;
            margin: 40px 20px;
            display: flex;
            flex-direction: column;
        }
        .logo-container{
            margin-top: 20px;
        }
        .sidebar-heading {
            font-weight: bold;
            text-align: center;
            margin: 0;
        }
        .nav-container {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            flex-grow: 1;
        }
        .nav-menu-container {
            flex-grow: 1;
        }
        .nav-link {
            color: #e0e7ff;
            font-weight: 500;
            border-radius: 8px;
            margin: 5px 15px;
            padding: 10px;
            text-decoration: none;
            transition: all 0.3s;
            display: block;
        }
        .nav-link.active,
        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }
        .nav-link .bi {
            margin-right: 8px;
            font-size: 1.2em;
        }
        .nav-logout-container .nav-link {
            padding: 10px 15px;
            background: linear-gradient(135deg, rgb(64, 11, 11), rgb(179, 64, 64)); 
            color: #fff;
            border-radius: 8px;
            text-align: center;
            text-decoration: none;
            transition: all 0.3s;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            margin: 20px;
        }
        .nav-logout-container .nav-link:hover {
            transform: translateY(-2px);
        }

        /* Navbar */
        .navbar {
            background: rgb(40, 41, 61);
            color: #fff;
            border-radius: 8px;
            margin-top: 20px;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--shadow);
        }
        .navbar .navbar-brand {
            color: #fff;
            font-weight: bold;
        }
        .navbar .nav-link {
            color: #e0f2fe;
            font-weight: 500;
            text-decoration: none;
        }

        /* Main Content Area */
        .content {
            margin-left: 280px;
            padding: 20px;
            width: calc(100% - 280px);
        }

        /* Card Styles */
        .card {
            background-color: var(--card-bg);
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            border-radius: 8px;
            overflow: hidden;
            /* margin-top: 20px; */
        }
        .card-header {
            background: linear-gradient(135deg, rgb(11, 29, 64), rgb(64, 108, 179)); 
            color: #fff;
            font-weight: bold;
            padding: 10px;
        }
        .divider {
            margin-top: 10px;
            border-bottom: 1px solid rgb(86, 86, 92);
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div style="height:5px;position:absolute;top:0;background: linear-gradient(135deg, rgb(11, 29, 64), rgb(64, 108, 179)); width:100%; z-index:-999;"></div>
    <!-- Sidebar -->
    <div class="sidebar-container">
        <div class="flex logo-container">
            <img src="{{asset('/img/logo.png')}}" alt="Safe Speak Logo" style="width:3.5rem;margin-right:5px;">
            <h3 class="sidebar-heading">SafeSpeak</h3>
        </div>
        <div class=" divider"></div>
        <nav class="nav-container">
            <div class="nav-menu-container">
                <a class="nav-link" href="{{route('post.index')}}">
                    <i class="bi bi-house-door"></i> <span>Post</span>
                </a>
                @if (Auth::user()->role == 'pakar')
                <a class="nav-link" href="{{route('scrape.index')}}">
                    <i class="bi bi-binoculars"></i> <span>Scrape</span>
                </a>
                <a class="nav-link" href="{{route('dataset.index')}}">
                    <i class="bi bi-database"></i> <span>Dataset</span>
                </a>
                @endif
                <a class="nav-link" href="{{route('law.index')}}">
                    <i class="bi bi-gear"></i> <span>Law</span>
                </a>
            </div>
            <div class="nav-logout-container">
                <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>

        </nav>
    </div>

    <!-- Content Area -->
    <div class="content">
        <!-- Top Navbar -->
        <nav class="navbar">
            <div class="navbar-brand">
                @yield('title')
            </div>
            <a class="nav-link" href="#">
                <i class="bi bi-person"></i> {{Auth::user()->name }}
            </a>
        </nav>

        <!-- Main Content -->
        <main style="margin-top: 20px;">
            @yield('content')
        </main>
    </div>

    @yield('javascript')
</body>
</html>
