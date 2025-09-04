<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | HARF Donations</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(120deg, #f0f9ff, #e0f2fe);
        }

        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .btn {
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .input-field:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Card -->
        <div class="card bg-white rounded-xl shadow-xl overflow-hidden">
            <!-- Card Header -->
            <div class="bg-blue-600 py-5 px-6">
                <div class="flex items-center justify-center">
                    <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center mr-3">
                        <span class="text-blue-600 font-bold text-xl">H</span>
                    </div>
                    <h1 class="text-2xl font-bold text-white">HARF Donations</h1>
                </div>
                <h2 class="text-lg text-white text-center mt-2">Admin Portal</h2>
            </div>

            <!-- Card Body -->
            <div class="p-6">
                <!-- Error Messages -->
                <div class="error-messages mb-4">
                    @if ($errors->any())
                        <div id="errorAlert" class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-3 hidden">
                            @foreach ($errors->all() as $error)
                                <div id="errorContent">{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif
                    @if (session('success'))
                        <div id="successAlert"
                            class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-3 hidden">
                            <div id="successContent"> {{ session('success') }}</div>
                        </div>
                    @endif

                    @if (session('error'))

                        <div id="errorAlert" class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-3 hidden">
                            @foreach ($errors->all() as $error)
                                <div id="errorContent"> {{ session('error') }}</div>
                            @endforeach
                        </div>
                    @endif



                </div>

                <!-- Login Form -->
                <form id="loginForm" class="space-y-5" method="POST" action="{{ route('admin.login.post') }}">
                    @csrf
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent input-field"
                                placeholder="admin@example.com" required>
                                 
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input type="password" id="password" name="password"
                                class="pl-10 pr-10 w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent input-field"
                                placeholder="Enter your password" required>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <button type="button" id="togglePassword" name="remember"
                                    class="text-gray-400 hover:text-gray-600 focus:outline-none">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember" type="checkbox"
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="remember" class="ml-2 block text-sm text-gray-700">Remember me</label>
                        </div>

                        <a href="#"
                            class="text-sm text-blue-600 hover:text-blue-800 transition duration-200">Forgot
                            password?</a>
                    </div>

                    <button type="submit" name="login"
                        class="btn w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                        <i class="fas fa-sign-in-alt mr-2"></i> Login to Dashboard
                    </button>
                </form>
            </div>

            <!-- Card Footer -->
            <div class="bg-gray-50 py-4 px-6 border-t border-gray-200">
                <p class="text-xs text-center text-gray-500">
                    &copy; 2023 HARF Donations. Secure admin access only.
                </p>
            </div>
        </div>


    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
          
            const togglePassword = document.getElementById('togglePassword');


            // Toggle password visibility
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);

                // Toggle eye icon
                const eyeIcon = this.querySelector('i');
                if (type === 'text') {
                    eyeIcon.classList.remove('fa-eye');
                    eyeIcon.classList.add('fa-eye-slash');
                } else {
                    eyeIcon.classList.remove('fa-eye-slash');
                    eyeIcon.classList.add('fa-eye');
                }
            });

 
        });
    </script>
</body>

</html>
