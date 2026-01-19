<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Droplets Dojo - Digital Systems Built to Serve</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            overflow-x: hidden;
        }

        /* Glassmorphism */
        .glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .glass-dark {
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Gradient Text Masking */
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 25%, #f093fb 50%, #4facfe 75%, #00f2fe 100%);
            background-size: 200% 200%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: gradient-shift 8s ease infinite;
        }

        .gradient-text-yellow {
            background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        @keyframes gradient-shift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        /* Soft Floating Shadows */
        .float-shadow {
            box-shadow: 
                0 10px 40px rgba(0, 0, 0, 0.1),
                0 2px 8px rgba(0, 0, 0, 0.06);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .float-shadow:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 
                0 20px 60px rgba(0, 0, 0, 0.15),
                0 8px 16px rgba(0, 0, 0, 0.1);
        }

        /* Infinite Marquee */
        .marquee {
            display: flex;
            overflow: hidden;
            user-select: none;
            gap: 2rem;
        }

        .marquee-content {
            flex-shrink: 0;
            display: flex;
            justify-content: space-around;
            gap: 2rem;
            min-width: 100%;
            animation: scroll 30s linear infinite;
        }

        @keyframes scroll {
            from { transform: translateX(0); }
            to { transform: translateX(-100%); }
        }

        /* Magnetic Button */
        .magnetic-btn {
            position: relative;
            transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Bento Grid */
        .bento-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
            grid-auto-rows: 200px;
        }

        .bento-item-1 { grid-column: span 2; grid-row: span 2; }
        .bento-item-2 { grid-column: span 2; grid-row: span 2; }
        .bento-item-3 { grid-column: span 2; grid-row: span 1; }
        .bento-item-4 { grid-column: span 2; grid-row: span 1; }

        @media (max-width: 768px) {
            .bento-grid {
                grid-template-columns: 1fr;
                grid-auto-rows: 250px;
            }
            .bento-item-1, .bento-item-2, .bento-item-3, .bento-item-4 {
                grid-column: span 1 !important;
                grid-row: span 1 !important;
            }
        }

        /* Scroll Reveal */
        .reveal {
            opacity: 0;
            transform: translateY(60px);
        }

        /* Sticky Nav Blur */
        .nav-blur {
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            background: rgba(255, 255, 255, 0.8);
            border-bottom: 1px solid rgba(0, 0, 0, 0.06);
        }

        /* Parallax */
        .parallax {
            transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Micro-interactions */
        .micro-bounce:hover {
            animation: bounce 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .pulse-ring {
            position: relative;
        }

        .pulse-ring::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 2px solid #3b82f6;
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {
            0% { transform: translate(-50%, -50%) scale(1); opacity: 1; }
            100% { transform: translate(-50%, -50%) scale(1.5); opacity: 0; }
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Sticky Navigation with Blur -->
    <nav id="navbar" class="fixed w-full top-0 z-50 transition-all duration-300" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center space-x-3 micro-bounce">
                    <div class="h-10 w-10 rounded-xl overflow-hidden bg-gradient-to-br from-blue-600 to-purple-600 flex items-center justify-center pulse-ring">
                        @if(file_exists(public_path('storage/logo.png')))
                            <img src="{{ asset('storage/logo.png') }}" alt="Droplets Dojo" class="h-full w-full object-contain p-1">
                        @else
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        @endif
                    </div>
                    <span id="logo-text" class="text-xl font-bold text-white transition-all duration-300">Droplets Dojo</span>
                </div>

                <!-- Desktop Navigation Links -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#programs" class="nav-link text-white hover:text-yellow-400 font-medium transition-all hover:scale-105">Programs</a>
                    <a href="#features" class="nav-link text-white hover:text-yellow-400 font-medium transition-all hover:scale-105">Features</a>
                    <a href="#about" class="nav-link text-white hover:text-yellow-400 font-medium transition-all hover:scale-105">About</a>
                    <button class="magnetic-btn px-6 py-2.5 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-xl hover:shadow-xl transition-all">
                        <a href="{{ route('login') }}" class="text-white">Login</a>
                    </button>
                </div>

                <!-- Mobile Hamburger Button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 rounded-lg hover:bg-white/10 transition-colors">
                    <svg x-show="!mobileMenuOpen" class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <svg x-show="mobileMenuOpen" class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Mobile Menu Dropdown -->
            <div x-show="mobileMenuOpen" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-2"
                 @click.away="mobileMenuOpen = false"
                 class="md:hidden absolute top-full left-0 right-0 glass-dark shadow-2xl border-t border-white/10"
                 style="display: none;">
                <div class="px-4 py-4 space-y-3">
                    <a href="#programs" @click="mobileMenuOpen = false" class="block px-4 py-3 text-white hover:bg-white/10 rounded-lg font-medium transition-all">
                        Programs
                    </a>
                    <a href="#features" @click="mobileMenuOpen = false" class="block px-4 py-3 text-white hover:bg-white/10 rounded-lg font-medium transition-all">
                        Features
                    </a>
                    <a href="#about" @click="mobileMenuOpen = false" class="block px-4 py-3 text-white hover:bg-white/10 rounded-lg font-medium transition-all">
                        About
                    </a>
                    <a href="{{ route('login') }}" class="block px-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-lg text-center hover:shadow-xl transition-all">
                        Login
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section with Parallax -->
    <section class="relative min-h-screen flex items-center justify-center overflow-hidden pt-16">
        <!-- Parallax Background -->
        <div class="parallax-bg absolute inset-0">
            <img src="https://images.unsplash.com/photo-1555597673-b21d5c935865?q=80&w=2000" 
                 alt="Hero" 
                 class="w-full h-full object-cover scale-110">
            <div class="absolute inset-0 bg-gradient-to-br from-gray-900/95 via-purple-900/80 to-blue-900/90"></div>
        </div>

        <!-- Floating Elements -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-20 left-20 w-96 h-96 bg-blue-500/20 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-20 right-20 w-96 h-96 bg-purple-500/20 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
        </div>

        <!-- Content -->
        <div class="relative z-10 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="reveal">
                <div class="inline-block px-4 py-2 rounded-full glass-dark text-sm font-semibold text-white mb-6 micro-bounce">
                    🚀 Empowering Communities Through Technology
                </div>
            </div>
            
            <div class="reveal" style="transition-delay: 0.2s;">
                <h1 class="text-5xl md:text-7xl lg:text-8xl font-black text-white mb-6 leading-tight">
                    Digital Systems Built to Serve<br>
                    <span class="gradient-text-yellow">More Than Just Users</span>
                </h1>
            </div>

            <div class="reveal" style="transition-delay: 0.4s;">
                <p class="text-xl md:text-2xl text-gray-200 mb-10 max-w-3xl mx-auto leading-relaxed">
                    Carefully crafted technology designed to grow programs, strengthen communities, and create lasting impact.
                </p>
            </div>

            <div class="reveal flex flex-col sm:flex-row items-center justify-center gap-4" style="transition-delay: 0.6s;">
                <button class="magnetic-btn group relative px-8 py-4 bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 text-white font-bold rounded-2xl overflow-hidden">
                    <span class="relative z-10">Explore Our Programs</span>
                    <div class="absolute inset-0 bg-gradient-to-r from-pink-600 via-purple-600 to-blue-600 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                </button>
                <a href="{{ route('login') }}" class="magnetic-btn px-8 py-4 glass text-white font-bold rounded-2xl hover:scale-105 transition-all">
                    Get Started →
                </a>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 animate-bounce">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
            </svg>
        </div>
    </section>

    <!-- Infinite Marquee -->
    <section class="py-12 bg-gradient-to-r from-blue-600 to-purple-600 overflow-hidden">
        <div class="marquee">
            <div class="marquee-content">
                <span class="text-white text-xl font-bold whitespace-nowrap">✨ Built for Impact</span>
                <span class="text-white text-xl font-bold whitespace-nowrap">🔒 Reliable & Secure</span>
                <span class="text-white text-xl font-bold whitespace-nowrap">📈 Scalable by Design</span>
                <span class="text-white text-xl font-bold whitespace-nowrap">❤️ Rooted in Mercy</span>
                <span class="text-white text-xl font-bold whitespace-nowrap">🌟 Technology with Purpose</span>
            </div>
            <div class="marquee-content" aria-hidden="true">
                <span class="text-white text-xl font-bold whitespace-nowrap">✨ Built for Impact</span>
                <span class="text-white text-xl font-bold whitespace-nowrap">🔒 Reliable & Secure</span>
                <span class="text-white text-xl font-bold whitespace-nowrap">📈 Scalable by Design</span>
                <span class="text-white text-xl font-bold whitespace-nowrap">❤️ Rooted in Mercy</span>
                <span class="text-white text-xl font-bold whitespace-nowrap">🌟 Technology with Purpose</span>
            </div>
        </div>
    </section>

    <!-- Bento Grid Layout - Programs -->
    <section id="programs" class="py-24 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="reveal text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-4">
                    Our <span class="gradient-text">Digital Initiatives</span>
                </h2>
                <p class="text-xl text-gray-600">Purpose-built systems for the Droplets of Mercy ecosystem</p>
            </div>

            <div class="bento-grid">
                <!-- Droplets Dojo - Large Card -->
                <div class="bento-item-1 reveal float-shadow rounded-3xl overflow-hidden relative group">
                    <img src="https://images.unsplash.com/photo-1555597408-26bc8e548a46?q=80&w=1000" 
                         alt="Droplets Dojo" 
                         class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent"></div>
                    
                    <div class="absolute top-4 left-4">
                        <span class="px-4 py-2 rounded-full bg-yellow-400 text-gray-900 text-xs font-bold uppercase">Live Program</span>
                    </div>

                    <div class="absolute bottom-0 left-0 right-0 p-8">
                        <div class="mb-4">
                            <span class="text-3xl font-black gradient-text-yellow">Droplets Dojo</span>
                        </div>
                        <p class="text-white text-lg mb-6">Martial Arts Academy Management System</p>
                        <a href="{{ route('login') }}" class="magnetic-btn inline-block px-6 py-3 bg-white text-gray-900 font-bold rounded-xl hover:shadow-2xl transition-all">
                            Enter Program →
                        </a>
                    </div>
                </div>

                <!-- Coming Soon -->
                <div class="bento-item-2 reveal float-shadow rounded-3xl overflow-hidden relative group" style="transition-delay: 0.1s;">
                    <img src="https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?q=80&w=1000" 
                         alt="Coming Soon" 
                         class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-purple-900/90 via-purple-900/50 to-transparent"></div>
                    
                    <div class="absolute top-4 left-4">
                        <span class="px-4 py-2 rounded-full bg-blue-600 text-white text-xs font-bold uppercase">Coming Soon</span>
                    </div>

                    <div class="absolute bottom-0 left-0 right-0 p-8">
                        <h3 class="text-4xl font-black text-white mb-3">Next Innovation</h3>
                        <p class="text-gray-200 text-lg">Details will be announced soon</p>
                    </div>
                </div>

                <!-- Stats Card -->
                <div class="bento-item-3 reveal float-shadow rounded-3xl bg-gradient-to-br from-blue-600 to-purple-600 p-8 flex flex-col justify-center" style="transition-delay: 0.2s;">
                    <div class="text-6xl font-black text-white mb-2">100%</div>
                    <div class="text-white text-lg">Purpose-Driven Technology</div>
                </div>

                <!-- Mission Card -->
                <div class="bento-item-4 reveal float-shadow rounded-3xl glass-dark p-8 flex flex-col justify-center" style="transition-delay: 0.3s;">
                    <div class="text-2xl font-bold text-white mb-2">🎯 Our Mission</div>
                    <div class="text-gray-300">Serving communities with compassion and understanding</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features with Glassmorphism -->
    <section id="features" class="py-24 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-gray-50 to-blue-50">
        <div class="max-w-7xl mx-auto">
            <div class="reveal text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-4">
                    Technology with <span class="gradient-text">Purpose</span>
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Feature 1 -->
                <div class="reveal float-shadow glass rounded-3xl p-8 text-center group hover:scale-105 transition-all">
                    <div class="w-16 h-16 mx-auto mb-6 rounded-2xl bg-gradient-to-br from-blue-600 to-purple-600 flex items-center justify-center group-hover:rotate-12 transition-transform">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Built for Impact</h3>
                    <p class="text-gray-600">Every feature amplifies positive change</p>
                </div>

                <!-- Feature 2 -->
                <div class="reveal float-shadow glass rounded-3xl p-8 text-center group hover:scale-105 transition-all" style="transition-delay: 0.1s;">
                    <div class="w-16 h-16 mx-auto mb-6 rounded-2xl bg-gradient-to-br from-green-600 to-teal-600 flex items-center justify-center group-hover:rotate-12 transition-transform">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Reliable & Secure</h3>
                    <p class="text-gray-600">Enterprise-grade security</p>
                </div>

                <!-- Feature 3 -->
                <div class="reveal float-shadow glass rounded-3xl p-8 text-center group hover:scale-105 transition-all" style="transition-delay: 0.2s;">
                    <div class="w-16 h-16 mx-auto mb-6 rounded-2xl bg-gradient-to-br from-orange-600 to-pink-600 flex items-center justify-center group-hover:rotate-12 transition-transform">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Scalable Design</h3>
                    <p class="text-gray-600">Grows with your programs</p>
                </div>

                <!-- Feature 4 -->
                <div class="reveal float-shadow glass rounded-3xl p-8 text-center group hover:scale-105 transition-all" style="transition-delay: 0.3s;">
                    <div class="w-16 h-16 mx-auto mb-6 rounded-2xl bg-gradient-to-br from-purple-600 to-indigo-600 flex items-center justify-center group-hover:rotate-12 transition-transform">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Rooted in Mercy</h3>
                    <p class="text-gray-600">Compassion-driven technology</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="relative py-32 px-4 sm:px-6 lg:px-8 overflow-hidden">
        <div class="parallax-bg absolute inset-0">
            <img src="https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?q=80&w=2000" 
                 alt="CTA" 
                 class="w-full h-full object-cover scale-110">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-900/95 via-purple-900/90 to-pink-900/95"></div>
        </div>

        <div class="relative z-10 max-w-4xl mx-auto text-center">
            <div class="reveal">
                <h2 class="text-4xl md:text-6xl font-black text-white mb-6 leading-tight">
                    When Technology Serves with Intention,<br>
                    <span class="gradient-text-yellow">Impact Multiplies</span>
                </h2>
                <button class="magnetic-btn mt-8 px-10 py-5 bg-white text-gray-900 font-black rounded-2xl text-xl hover:shadow-2xl transition-all">
                    Explore Programs →
                </button>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="about" class="bg-gray-900 text-white py-16 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="h-10 w-10 bg-gradient-to-br from-blue-600 to-purple-600 rounded-xl"></div>
                        <span class="text-xl font-bold gradient-text">Droplets Digital</span>
                    </div>
                    <p class="text-gray-400 mb-4">A technology subsidiary of Droplets of Mercy</p>
                    <p class="text-sm text-gray-500">© {{ date('Y') }} Droplets Digital</p>
                </div>

                <div>
                    <h3 class="font-bold mb-4">Quick Links</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#programs" class="hover:text-white transition-colors">Programs</a></li>
                        <li><a href="#features" class="hover:text-white transition-colors">Features</a></li>
                        <li><a href="#about" class="hover:text-white transition-colors">About</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-bold mb-4">Get Started</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">Login</a></li>
                        <li><a href="#programs" class="hover:text-white transition-colors">View Programs</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-12 pt-8 text-center text-gray-500 text-sm">
                <p>Built with purpose. Serving with compassion. ❤️</p>
            </div>
        </div>
    </footer>

    <script>
        // GSAP ScrollTrigger Animations
        gsap.registerPlugin(ScrollTrigger);

        // Reveal elements on scroll
        gsap.utils.toArray('.reveal').forEach((elem) => {
            gsap.fromTo(elem, 
                { opacity: 0, y: 60 },
                {
                    opacity: 1,
                    y: 0,
                    duration: 1,
                    ease: 'power3.out',
                    scrollTrigger: {
                        trigger: elem,
                        start: 'top 80%',
                        end: 'top 20%',
                        toggleActions: 'play none none reverse'
                    }
                }
            );
        });

        // Parallax effect for backgrounds
        gsap.utils.toArray('.parallax-bg').forEach((bg) => {
            gsap.to(bg, {
                yPercent: 20,
                ease: 'none',
                scrollTrigger: {
                    trigger: bg,
                    start: 'top top',
                    end: 'bottom top',
                    scrub: true
                }
            });
        });

        // Sticky Navigation Blur
        const navbar = document.getElementById('navbar');
        const logoText = document.getElementById('logo-text');
        const navLinks = document.querySelectorAll('.nav-link');
        
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('nav-blur');
                
                // Change logo text to gradient
                logoText.classList.remove('text-white');
                logoText.classList.add('gradient-text');
                
                // Change nav links to dark
                navLinks.forEach(link => {
                    link.classList.remove('text-white', 'hover:text-yellow-400');
                    link.classList.add('text-gray-700', 'hover:text-blue-600');
                });
            } else {
                navbar.classList.remove('nav-blur');
                
                // Change logo text to white
                logoText.classList.remove('gradient-text');
                logoText.classList.add('text-white');
                
                // Change nav links to white
                navLinks.forEach(link => {
                    link.classList.remove('text-gray-700', 'hover:text-blue-600');
                    link.classList.add('text-white', 'hover:text-yellow-400');
                });
            }
        });

        // Magnetic Button Effect
        document.querySelectorAll('.magnetic-btn').forEach(btn => {
            btn.addEventListener('mousemove', (e) => {
                const rect = btn.getBoundingClientRect();
                const x = e.clientX - rect.left - rect.width / 2;
                const y = e.clientY - rect.top - rect.height / 2;
                
                btn.style.transform = `translate(${x * 0.2}px, ${y * 0.2}px)`;
            });

            btn.addEventListener('mouseleave', () => {
                btn.style.transform = 'translate(0, 0)';
            });
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    </script>
</body>
</html>
