<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - HARF Donation System</title>
    <!-- Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
    <meta name="apple-mobile-web-app-title" content="HARF Admin" />
    <link rel="manifest" href="/site.webmanifest" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#4361ee',
                        secondary: '#3f37c9',
                        success: '#4cc9f0',
                        info: '#4895ef',
                        warning: '#f72585',
                        danger: '#e63946',
                    }
                }
            }
        }
    </script>
    

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background-color: #f8fafc;
            color: #334155;
            display: flex;
            min-height: 100vh;
        }
        
        .sidebar {
            width: 260px;
            background: white;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            z-index: 1000;
            display: flex;
            flex-direction: column;
        }
        
        .main-content {
            flex: 1;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        
        .nav-link {
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
        }
        
        .nav-link:hover, .nav-link.active {
            background-color: #f1f5f9;
            color: #3b82f6;
            border-left-color: #3b82f6;
        }
        
        .nav-link.active i {
            color: #3b82f6;
        }
        
        .hamburger {
            display: none;
            cursor: pointer;
        }

        input, select, textarea {
            padding: 0.5rem !important;
            border: 1px solid #d1d5db !important;
            box-shadow: none !important;
        }
        
        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.04);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
                .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        
        .progress-bar {
            height: 8px;
            border-radius: 4px;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            border-radius: 4px;
        }
        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.07);
        }
        
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
                position: fixed;
                height: 100vh;
                top: 0;
                left: 0;
            }
            
            .sidebar.open {
                transform: translateX(0);
            }
            
            .overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 999;
            }
            
            .overlay.open {
                display: block;
            }
            
            .hamburger {
                display: block;
            }
        }
        
        .logo {
            height: 40px;
            object-fit: contain;
        }
        
        .content-area {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
        }
        
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background-color: #ecfdf5;
            color: #065f46;
            border-left: 4px solid #10b981;
        }
        
        .alert-error {
            background-color: #fef2f2;
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }
    </style>
</head>
<body>
    <!-- Overlay for mobile -->
    <div class="overlay" id="overlay"></div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="p-4 flex items-center justify-center">
            <img src="{{ asset("logo.jpeg") }}" alt="HARF Donations" class="logo">
            <span class="text-xl font-bold ml-3">HARF Admin</span>
        </div>
        
<nav class="mt-8 flex-1">
    <div class="px-4 space-y-1">
        <a href="{{ route('admin.dashboard') }}" class="nav-link flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt text-gray-500 mr-3"></i>
            Dashboard
        </a>
        <a href="{{ route('admin.donations.index') }}" class="nav-link flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('admin.donations.*') ? 'active' : '' }}">
            <i class="fas fa-heart text-gray-500 mr-3"></i>
            Donations
        </a>
        <a href="{{ route('admin.pledges.index') }}" class="nav-link flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('admin.pledges.*') ? 'active' : '' }}">
            <i class="fas fa-handshake text-gray-500 mr-3"></i>
            Pledges
        </a>
        <a href="{{ route('admin.users.index') }}" class="nav-link flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="fas fa-users text-gray-500 mr-3"></i>
            Users
        </a>
        <a href="{{ route('admin.notifications.index') }}" class="nav-link flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
            <i class="fas fa-bell text-gray-500 mr-3"></i>
            Notifications
        </a>
        <a href="{{ route('admin.reports.index') }}" class="nav-link flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
            <i class="fas fa-chart-bar text-gray-500 mr-3"></i>
            Reports
        </a>
        <a href="{{ route('admin.settings.index') }}" class="nav-link flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
            <i class="fas fa-cog text-gray-500 mr-3"></i>
            Settings
        </a>
    </div>

    <div class="border-t border-gray-200 mt-8 pt-4 px-4 space-y-1">
        <a href="{{ route('admin.profile.show') }}" class="nav-link flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
            <i class="fas fa-user text-gray-500 mr-3"></i>
            My Profile
        </a>
        <a href="{{ route('admin.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-link flex items-center px-4 py-3 rounded-lg">
            <i class="fas fa-sign-out-alt text-gray-500 mr-3"></i>
            Logout
        </a>
        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="hidden">
            @csrf
        </form>
    </div>
</nav>


        
        <div class="p-4 text-center text-xs text-gray-500">
            HARF Donations &copy; 2023
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <header class="bg-white shadow-sm border-b border-gray-200 py-4 px-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <button class="hamburger mr-4 text-gray-600 focus:outline-none" id="hamburger">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h1 class="text-2xl font-semibold text-gray-900">Dashboard</h1>
                </div>
                
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <button class="p-2 rounded-full hover:bg-gray-100">
                            <i class="fas fa-bell text-gray-600"></i>
                            <span class="absolute top-0 right-0 h-5 w-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">3</span>
                        </button>
                    </div>
                    <div class="text-sm text-gray-600 hidden md:block">
                        Welcome, <span class="font-medium">Admin User</span>
                    </div>
                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                        <span class="text-blue-600 font-semibold">AU</span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content Area -->
        <div class="content-area">
            <!-- Flash Messages -->
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-error">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Main Content -->
            @yield('content')   
        </div>
    </div>

    <script>
        // Toggle sidebar on mobile
        document.getElementById('hamburger').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('open');
            document.getElementById('overlay').classList.toggle('open');
        });
        
        // Close sidebar when clicking overlay
        document.getElementById('overlay').addEventListener('click', function() {
            document.getElementById('sidebar').classList.remove('open');
            document.getElementById('overlay').classList.remove('open');
        });
        
        // Active nav link
        const navLinks = document.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                navLinks.forEach(l => l.classList.remove('active'));
                this.classList.add('active');
                
                // On mobile, close sidebar after selection
                if (window.innerWidth < 1024) {
                    document.getElementById('sidebar').classList.remove('open');
                    document.getElementById('overlay').classList.remove('open');
                }
            });
        });


          document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('donationsChart').getContext('2d');
            const chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30'],
                    datasets: [{
                        label: 'Daily Donations ($)',
                        data: [1250, 1300, 1180, 1400, 1520, 1680, 1750, 1620, 1580, 1490, 1630, 1720, 1680, 1740, 1820, 1950, 2010, 1920, 1850, 1760, 1830, 1910, 2050, 2120, 2080, 1980, 2160, 2240, 2180, 2310],
                        borderColor: 'rgb(67, 97, 238)',
                        backgroundColor: 'rgba(67, 97, 238, 0.1)',
                        tension: 0.3,
                        fill: true,
                        pointBackgroundColor: 'rgb(67, 97, 238)',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: 'rgb(67, 97, 238)',
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                drawBorder: false
                            },
                            ticks: {
                                callback: function(value) {
                                    return '$' + value.toLocaleString();
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    }
                }
            });
        });
    </script>
</body>
</html>