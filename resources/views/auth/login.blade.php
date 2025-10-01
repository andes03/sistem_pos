<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sebelas Coofee</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'poppins': ['Poppins', 'sans-serif'],
                    },
                    animation: {
                        'slide-in': 'slideIn 0.8s cubic-bezier(0.25, 1, 0.5, 1)',
                        'slide-out': 'slideOut 0.8s cubic-bezier(0.25, 1, 0.5, 1)',
                        'fade-in': 'fadeIn 0.8s ease-in-out',
                    },
                    keyframes: {
                        slideIn: {
                            '0%': { transform: 'translateX(100%)', opacity: '0' },
                            '100%': { transform: 'translateX(0)', opacity: '1' },
                        },
                        slideOut: {
                            '0%': { transform: 'translateX(0)', opacity: '1' },
                            '100%': { transform: 'translateX(-100%)', opacity: '0' },
                        },
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen flex items-center justify-center p-4 bg-gray-100 font-poppins">
    <div class="max-w-sm w-full mx-auto">
        <div class="bg-white rounded-lg overflow-hidden shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200 ease-in-out">
            <div class="relative h-48 sm:h-64 overflow-hidden">
                <!-- Carousel Images -->
                <img src="/gambar.png" alt="Gambar 1" class="carousel-image absolute inset-0 w-full h-full object-cover opacity-100 transform translate-x-0 transition-all duration-800 ease-in-out">
                <img src="/gambar2.png" alt="Gambar 2" class="carousel-image absolute inset-0 w-full h-full object-cover opacity-0 transform translate-x-full transition-all duration-800 ease-in-out">
                <img src="/gambar3.png" alt="Gambar 3" class="carousel-image absolute inset-0 w-full h-full object-cover opacity-0 transform translate-x-full transition-all duration-800 ease-in-out">
                
                <!-- Back Button -->
                <a href="/" class="absolute top-4 left-4 z-20 p-2 rounded-full bg-black bg-opacity-30 text-white hover:bg-opacity-50 hover:-translate-x-0.5 focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50 transition-all duration-200 ease-in-out">
                    <i data-feather="arrow-left" class="h-5 w-5"></i>
                </a>
                
                <!-- Overlay Content -->
                <div class="absolute inset-0 bg-black bg-opacity-40 flex flex-col justify-center items-center text-white z-10 p-4">
                    <h1 class="text-4xl font-extrabold tracking-tight drop-shadow-lg">Sebelas Coofee</h1>
                    <p class="mt-2 text-lg font-light text-blue-100 drop-shadow-md">Admin & Pegawai Login</p>
                </div>
            </div>

            <div class="px-8 pb-8 sm:px-10 mt-6">
                @if ($errors->any())
                    <div class="mb-4 p-2 bg-red-100 border-l-4 border-red-500 text-red-800 rounded-lg shadow-sm" role="alert">
                        <div class="flex items-start text-sm">
                            <i data-feather="alert-triangle" class="h-4 w-4 text-red-500 mr-2 mt-0.5 flex-shrink-0"></i>
                            <p>{{ $errors->first() }}</p>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf
                    
                    <!-- Username Field -->
                    <div>
                        <label for="username" class="sr-only">Username</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i data-feather="user" class="h-5 w-5 text-gray-400"></i>
                            </div>
                            <input type="text" 
                                   id="username" 
                                   name="username" 
                                   value="{{ old('username') }}"
                                   class="block w-full pl-12 pr-4 py-3 border {{ $errors->has('username') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 ease-in-out" 
                                   placeholder="Username"
                                   autofocus
                                   required autocomplete="username">
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="password" class="sr-only">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i data-feather="lock" class="h-5 w-5 text-gray-400"></i>
                            </div>
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   class="block w-full pl-12 pr-12 py-3 border {{ $errors->has('password') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 ease-in-out" 
                                   placeholder="Password"
                                   required autocomplete="current-password">
                            
                            <!-- Password Toggle Button -->
                            <button type="button" id="password-toggle" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none focus:text-gray-600 transition-colors duration-200">
                                <i data-feather="eye" class="h-5 w-5" id="icon-eye"></i>
                                <i data-feather="eye-off" class="h-5 w-5 hidden" id="icon-eye-off"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit" 
                                class="w-full flex items-center justify-center py-3 px-4 border border-transparent rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 active:bg-blue-800 transition-all duration-200 ease-in-out transform hover:scale-[1.02] active:scale-[0.98]">
                            <i data-feather="log-in" class="h-5 w-5 mr-2"></i>
                            Masuk
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-8 text-center text-gray-500 text-sm">
            &copy; {{ date('Y') }} Balcos Compound. All rights reserved.
        </div>
    </div>

    <script>
        feather.replace();

        // Carousel Logic
        const images = document.querySelectorAll('.carousel-image');
        let currentIndex = 0;
        
        function nextSlide() {
            const currentImage = images[currentIndex];
            const nextIndex = (currentIndex + 1) % images.length;
            const nextImage = images[nextIndex];

            // Hide current image
            currentImage.classList.remove('opacity-100', 'translate-x-0');
            currentImage.classList.add('opacity-0', '-translate-x-full');

            // Show next image
            setTimeout(() => {
                nextImage.classList.remove('opacity-0', 'translate-x-full');
                nextImage.classList.add('opacity-100', 'translate-x-0');
            }, 50);

            // Reset current image position after transition
            setTimeout(() => {
                currentImage.classList.remove('-translate-x-full');
                currentImage.classList.add('translate-x-full');
            }, 850);

            currentIndex = nextIndex;
        }

        // Auto-advance carousel every 5 seconds
        setInterval(nextSlide, 5000);

        // Password Toggle Logic
        const passwordToggle = document.getElementById('password-toggle');
        const passwordInput = document.getElementById('password');
        const iconEye = document.getElementById('icon-eye');
        const iconEyeOff = document.getElementById('icon-eye-off');

        passwordToggle.addEventListener('click', function() {
            const currentType = passwordInput.getAttribute('type');
            const newType = currentType === 'password' ? 'text' : 'password';
            
            passwordInput.setAttribute('type', newType);
            
            if (newType === 'text') {
                iconEye.classList.add('hidden');
                iconEyeOff.classList.remove('hidden');
            } else {
                iconEye.classList.remove('hidden');
                iconEyeOff.classList.add('hidden');
            }
        });

        // Enhanced input focus effects
        const inputs = document.querySelectorAll('input[type="text"], input[type="password"]');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentNode.classList.add('transform', 'scale-[1.02]');
            });
            
            input.addEventListener('blur', function() {
                this.parentNode.classList.remove('transform', 'scale-[1.02]');
            });
        });
    </script>
</body>
</html>