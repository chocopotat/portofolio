<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portofolio Fullstack Developer - Laravel | React | TypeScript</title>
    <meta name="description" content="Fullstack Developer dengan pengalaman 1 tahun di Laravel, React, Supabase, MySQL, TypeScript. Membangun aplikasi web yang scalable dan mudah digunakan.">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js untuk interaksi -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- AOS untuk scroll reveal (optional) -->
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    
    <style>
        /* ========================================
           PERFORMANCE-FIRST ANIMATIONS
           ======================================== */
        
        @keyframes floatOrb {
            0%, 100% {
                transform: translate3d(0, 0, 0) scale(1);
            }
            50% {
                transform: translate3d(0, -18px, 0) scale(1.06);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px) scale(0.98);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        .float-orb {
            animation: floatOrb 9s ease-in-out infinite;
            will-change: transform;
        }

        .float-orb.delay-1 {
            animation-delay: 1.2s;
        }

        .float-orb.delay-2 {
            animation-delay: 2.4s;
        }
        
        /* 1. HERO SECTION ENTRANCE */
        .hero-title {
            animation: fadeInUp 1s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: transform, opacity;
        }
        
        .hero-subtitle {
            animation: fadeInUp 1s 0.2s cubic-bezier(0.16, 1, 0.3, 1) both;
            will-change: transform, opacity;
        }
        
        .hero-cta {
            animation: fadeInUp 1s 0.4s cubic-bezier(0.16, 1, 0.3, 1) both;
            will-change: transform, opacity;
        }
        
        /* 2. SKILL BADGE HOVER - Spring effect */
        .skill-badge {
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            will-change: transform;
        }
        
        .skill-badge:hover {
            transform: scale(1.1) translateY(-4px);
        }
        
        /* 3. PROJECT CARD HOVER */
        .project-card {
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            will-change: transform;
        }
        
        .project-card:hover {
            transform: translateY(-8px);
        }
        
        .project-card:hover .project-image {
            transform: scale(1.05);
        }
        
        .project-image {
            transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            will-change: transform;
        }
        
        /* 4. SMOOTH SCROLL */
        html {
            scroll-behavior: smooth;
        }
        
        /* 5. LAZY ANIMATION CLASS */
        [data-animate] {
            opacity: 0;
            transform: translate3d(0, 42px, 0) scale(0.98);
            transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s ease;
            will-change: transform, opacity;
        }
        
        [data-animate].animate-in {
            opacity: 1;
            transform: translate3d(0, 0, 0) scale(1);
        }

        .parallax-orb {
            will-change: transform;
            transform: translate3d(0, 0, 0);
        }

        [data-animate].animate-in:hover {
            box-shadow: 0 18px 36px rgba(15, 23, 42, 0.08);
        }

        /* 6. CONTACT FORM SUBMIT STATE */
        .form-submit {
            transition: all 0.3s ease;
        }
        
        .form-submit:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }
        
        /* 7. REDUCE MOTION ACCESSIBILITY */
        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
        
        /* 8. GLASSMORPHISM */
        .glass-panel {
            background: rgba(255, 255, 255, 0.48);
            border: 1px solid rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);
        }

        .glass-chip {
            background: rgba(255, 255, 255, 0.45);
            border: 1px solid rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        /* 9. GRADIENT TEXT */
        .gradient-text {
            background: linear-gradient(135deg, #1f2937 0%, #475569 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* 10. PROFILE CARD / PREMIUM HERO */
        .profile-card {
            background: rgba(255, 255, 255, 0.52);
            border: 1px solid rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
        }

        .profile-image-wrap {
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(15, 23, 42, 0.08);
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        }

        .profile-status {
            position: absolute;
            right: 1rem;
            bottom: 1rem;
            background: rgba(17, 24, 39, 0.76);
            color: white;
            box-shadow: 0 10px 20px rgba(17, 24, 39, 0.15);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }

        .stat-card {
            background: rgba(248, 250, 252, 0.75);
            border: 1px solid rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        /* 10. GLOW EFFECT (performant) */
        .glow-border {
            position: relative;
            border: 1px solid rgba(15, 23, 42, 0.12);
            transition: border-color 0.3s ease;
        }
        
        .glow-border:hover {
            border-color: rgba(15, 23, 42, 0.2);
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.08);
        }
    </style>
</head>
<body class="bg-white text-gray-900 dark:bg-gray-950 dark:text-white">
    <!-- ============================================
         1. HERO SECTION
         ============================================ -->
    <header class="relative min-h-screen flex items-center justify-center bg-stone-50 overflow-hidden">
        <!-- Background decoration -->
        <div class="absolute inset-0 opacity-70">
            <div class="float-orb parallax-orb absolute top-20 left-10 w-64 h-64 bg-slate-200 rounded-full blur-3xl"></div>
            <div class="float-orb delay-1 parallax-orb absolute top-40 right-10 w-72 h-72 bg-zinc-200 rounded-full blur-3xl"></div>
            <div class="float-orb delay-2 parallax-orb absolute bottom-10 left-1/2 w-80 h-80 bg-stone-200 rounded-full blur-3xl transform -translate-x-1/2"></div>
        </div>
        
        <div class="relative z-10 container mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid items-center gap-10 lg:grid-cols-[1.1fr_0.9fr]">
                <div class="max-w-2xl lg:max-w-full text-left">
                    <!-- Badge -->
                    <div class="hero-cta mb-8 inline-block">
                        <span class="glass-panel inline-flex items-center rounded-full px-4 py-2 text-xs font-medium uppercase tracking-[0.18em] text-slate-600 shadow-sm">
                            Fullstack Developer
                        </span>
                    </div>
                    
                    <!-- Main Headline -->
                    <h1 class="hero-title text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-black mb-6 leading-tight tracking-tight">
                        <span class="gradient-text">Saya membangun produk</span>
                        <br>
                        <span class="text-slate-900">yang terasa sederhana dan bekerja dengan baik.</span>
                    </h1>
                    
                    <!-- Subtitle -->
                    <p class="hero-subtitle text-base sm:text-lg lg:text-xl text-slate-600 mb-10 max-w-xl lg:max-w-2xl leading-relaxed">
                        Saya terutama bekerja dengan <span class="font-semibold text-slate-800">Laravel & React </span>, <span class="font-semibold text-slate-800">React</span>, dan <span class="font-semibold text-slate-800">TypeScript</span> untuk membuat aplikasi web yang praktis, bersih, dan mudah digunakan.
                    </p>
                    
                    <!-- CTA Buttons -->
                    <div class="hero-cta flex flex-col sm:flex-row gap-4 justify-start mb-10">
                        <a href="#projects" class="px-7 py-4 bg-slate-900 text-white font-semibold rounded-xl hover:bg-slate-800 transition-all duration-300 shadow-lg shadow-slate-900/10 inline-flex items-center justify-center gap-2">
                            Lihat Project saya
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                            </svg>
                        </a>
                        <a href="/cv/resume.pdf" download class="px-7 py-4 border border-slate-200 bg-white text-slate-700 font-semibold rounded-xl hover:bg-slate-50 transition-all duration-300 inline-flex items-center justify-center gap-2">
                            Unduh CV
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v12m0 0l-4-4m4 4l4-4M5 19h14"></path>
                            </svg>
                        </a>
                        <a href="https://mail.google.com/mail/?view=cm&fs=1&to=hidayahfirman84@gmail.com&su=Ketertarikan%20Kerja%20Sama&body=Halo%2C%20saya%20ingin%20berdiskusi%20mengenai%20kerja%20sama." class="px-7 py-4 border border-slate-200 bg-white text-slate-700 font-semibold rounded-xl hover:bg-slate-50 transition-all duration-300 inline-flex items-center justify-center gap-2">
                            Mari bicara
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </a>
                    </div>
                    
                    <!-- Tech Stack Preview -->
                    <div class="hero-cta flex flex-wrap gap-3">
                        <span class="glass-chip px-4 py-2 text-sm font-medium text-slate-700 rounded-full shadow-sm">Laravel</span>
                        <span class="glass-chip px-4 py-2 text-sm font-medium text-slate-700 rounded-full shadow-sm">React</span>
                        <span class="glass-chip px-4 py-2 text-sm font-medium text-slate-700 rounded-full shadow-sm">TypeScript</span>
                        <span class="glass-chip px-4 py-2 text-sm font-medium text-slate-700 rounded-full shadow-sm">MySQL</span>
                        <span class="glass-chip px-4 py-2 text-sm font-medium text-slate-700 rounded-full shadow-sm">Supabase</span>
                    </div>
                </div>

                <div class="flex justify-center lg:justify-end">
                    <div class="profile-card w-full max-w-md rounded-4xl p-4 sm:p-5">
                        <div class="profile-image-wrap relative rounded-3xl overflow-hidden aspect-4/5">
                            <img 
                                src="/img/profil.webp" 
                                alt="Potret profesional" 
                                class="h-full w-full object-cover transition-transform duration-500 hover:scale-105"
                            >
                            <div class="profile-status inline-flex items-center gap-2 rounded-full px-3 py-2 text-[10px] font-medium uppercase tracking-[0.12em]">
                                <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                                Tersedia
                            </div>
                        </div>

                        <div class="mt-5 grid grid-cols-3 gap-3">
                            <div class="stat-card rounded-2xl p-3 text-center">
                                <div class="text-xl font-bold text-slate-900">1+</div>
                                <div class="text-[10px] uppercase tracking-wide text-slate-500">Tahun</div>
                            </div>
                            <div class="stat-card rounded-2xl p-3 text-center">
                                <div class="text-xl font-bold text-slate-900">12+</div>
                                <div class="text-[10px] uppercase tracking-wide text-slate-500">Proyek</div>
                            </div>
                            <div class="stat-card rounded-2xl p-3 text-center">
                                <div class="text-xl font-bold text-slate-900">24h</div>
                                <div class="text-[10px] uppercase tracking-wide text-slate-500">Balasan</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- ============================================
         2. SKILLS SECTION
         ============================================ -->
    <section id="skills" class="py-20 bg-white dark:bg-gray-900">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Title -->
            <div data-aos="fade-up" class="text-center mb-16">
                <h2 class="text-4xl sm:text-5xl font-bold mb-4">Teknologi & Keahlian</h2>
                <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                    Menguasai teknologi pengembangan web modern dengan fokus pada kode yang scalable, rapi, dan mudah dipelihara.
                </p>
            </div>
            
<!-- Skills Grid -->
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">

    <!-- Laravel -->
    <div data-animate class="flex flex-col items-center p-6 rounded-xl border border-gray-200 dark:border-gray-700 hover:shadow-lg dark:hover:shadow-lg/20 transition-shadow">

        <div class="skill-badge w-16 h-16 flex items-center justify-center rounded-lg bg-red-100 dark:bg-red-900/30 mb-4 p-3">
            <img
                src="https://cdn.simpleicons.org/laravel"
                alt="Laravel Logo"
                class="w-10 h-10 object-contain"
            >
        </div>

        <h3 class="font-bold text-lg mb-2">Laravel</h3>

        <p class="text-sm text-gray-600 dark:text-gray-400 text-center">
            Framework backend, API, dan desain database
        </p>
    </div>


    <!-- React -->
    <div data-animate class="flex flex-col items-center p-6 rounded-xl border border-gray-200 dark:border-gray-700 hover:shadow-lg dark:hover:shadow-lg/20 transition-shadow">

        <div class="skill-badge w-16 h-16 flex items-center justify-center rounded-lg bg-cyan-100 dark:bg-cyan-900/30 mb-4 p-3">
            <img
                src="https://cdn.simpleicons.org/react"
                alt="React Logo"
                class="w-10 h-10 object-contain"
            >
        </div>

        <h3 class="font-bold text-lg mb-2">React</h3>

        <p class="text-sm text-gray-600 dark:text-gray-400 text-center">
            UI komponen, state management, dan hooks
        </p>
    </div>


    <!-- TypeScript -->
    <div data-animate class="flex flex-col items-center p-6 rounded-xl border border-gray-200 dark:border-gray-700 hover:shadow-lg dark:hover:shadow-lg/20 transition-shadow">

        <div class="skill-badge w-16 h-16 flex items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900/30 mb-4 p-3">
            <img
                src="https://cdn.simpleicons.org/typescript"
                alt="TypeScript Logo"
                class="w-10 h-10 object-contain"
            >
        </div>

        <h3 class="font-bold text-lg mb-2">TypeScript</h3>

        <p class="text-sm text-gray-600 dark:text-gray-400 text-center">
            Type safety, interface, dan tipe lanjutan
        </p>
    </div>


    <!-- MySQL -->
    <div data-animate class="flex flex-col items-center p-6 rounded-xl border border-gray-200 dark:border-gray-700 hover:shadow-lg dark:hover:shadow-lg/20 transition-shadow">

        <div class="skill-badge w-16 h-16 flex items-center justify-center rounded-lg bg-orange-100 dark:bg-orange-900/30 mb-4 p-3">
            <img
                src="https://cdn.simpleicons.org/mysql"
                alt="MySQL Logo"
                class="w-10 h-10 object-contain"
            >
        </div>

        <h3 class="font-bold text-lg mb-2">MySQL</h3>

        <p class="text-sm text-gray-600 dark:text-gray-400 text-center">
            Desain database, optimasi, dan query
        </p>
    </div>


    <!-- Supabase -->
    <div data-animate class="flex flex-col items-center p-6 rounded-xl border border-gray-200 dark:border-gray-700 hover:shadow-lg dark:hover:shadow-lg/20 transition-shadow">

        <div class="skill-badge w-16 h-16 flex items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900/30 mb-4 p-3">
            <img
                src="https://cdn.simpleicons.org/supabase"
                alt="Supabase Logo"
                class="w-10 h-10 object-contain"
            >
        </div>

        <h3 class="font-bold text-lg mb-2">Supabase</h3>

        <p class="text-sm text-gray-600 dark:text-gray-400 text-center">
            PostgreSQL, Auth, real-time, dan storage
        </p>
    </div>


    <!-- JavaScript -->
    <div data-animate class="flex flex-col items-center p-6 rounded-xl border border-gray-200 dark:border-gray-700 hover:shadow-lg dark:hover:shadow-lg/20 transition-shadow">

        <div class="skill-badge w-16 h-16 flex items-center justify-center rounded-lg bg-yellow-100 dark:bg-yellow-900/30 mb-4 p-3">
            <img
                src="https://cdn.simpleicons.org/javascript"
                alt="JavaScript Logo"
                class="w-10 h-10 object-contain"
            >
        </div>

        <h3 class="font-bold text-lg mb-2">JavaScript</h3>

        <p class="text-sm text-gray-600 dark:text-gray-400 text-center">
            ES6+, DOM, integrasi API, dan async
        </p>
    </div>


    <!-- Tailwind CSS -->
    <div data-animate class="flex flex-col items-center p-6 rounded-xl border border-gray-200 dark:border-gray-700 hover:shadow-lg dark:hover:shadow-lg/20 transition-shadow">

        <div class="skill-badge w-16 h-16 flex items-center justify-center rounded-lg bg-cyan-100 dark:bg-cyan-900/30 mb-4 p-3">
            <img
                src="https://cdn.simpleicons.org/tailwindcss"
                alt="Tailwind CSS Logo"
                class="w-10 h-10 object-contain"
            >
        </div>

        <h3 class="font-bold text-lg mb-2">Tailwind CSS</h3>

        <p class="text-sm text-gray-600 dark:text-gray-400 text-center">
            Desain responsif, utility-first, dan performa
        </p>
    </div>

</div>

    </section>

    <!-- ============================================
         3. FEATURED PROJECTS SECTION
         ============================================ -->
    <section id="projects" class="py-20 bg-gray-50 dark:bg-gray-800">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Title -->
            <div data-aos="fade-up" class="text-center mb-16">
                <h2 class="text-4xl sm:text-5xl font-bold mb-4">Proyek Terpilih</h2>
                <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                    Karya-karya pilihan yang menunjukkan pengalaman saya dalam pengembangan fullstack, arsitektur modern, dan solusi yang scalable.
                </p>
            </div>
            
<!-- Projects Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

    <!-- Project 1 -->
    <div data-animate
        class="project-card bg-white dark:bg-gray-700 rounded-xl overflow-hidden border border-gray-200 dark:border-gray-600">

        <!-- Project Image -->
        <div class="h-80 overflow-hidden relative">
            <img
                src="{{ asset('img/Pr1cylinder.png') }}"
                alt="Monitoring Stock"
                class="project-image w-full h-full object-cover transition-transform duration-500 hover:scale-105"
            >
        </div>

        <!-- Project Info -->
        <div class="p-8">

            <h3 class="text-2xl font-bold mb-3">
                Monitoring Stock 
            </h3>

            <p class="text-gray-600 dark:text-gray-300 mb-2 text-sm font-semibold">
                Sistem informasi Monitoring Stock Cylinder Rotogravure masuk dan Keluar Gudang 
            </p>

            <p class="text-gray-600 dark:text-gray-300 mb-6 leading-relaxed text-sm">
                Membuat monitoring barang di gudang Cylinder Rotogravure dengan fitur masuk dan keluar barang, serta menampilkan laporan barang yang masuk dan keluar. Sistem ini menggunakan Laravel untuk backend, React untuk frontend, dan MySQL untuk database.
            </p>

            <!-- Dampak -->
            <div class="mb-4 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-100 dark:border-blue-800">
                <p class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">
                    Dampak:
                </p>

                <ul class="text-xs text-gray-600 dark:text-gray-400 space-y-0.5">
                    <li>• Penurunan 60% lebih efisien dan lebih optimal</li>
                    <li>• Peningkatan 40% konversi mobile dari desain responsif</li>
                    <li>• Uptime 99,9% dengan backup otomatis</li>
                </ul>
            </div>

            <!-- Technologies -->
            <div class="flex flex-wrap gap-2 mb-6">
                <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-200 text-sm font-medium rounded-full">
                    TypeScript
                </span>

                <span class="px-3 py-1 bg-cyan-100 dark:bg-cyan-900 text-cyan-700 dark:text-cyan-200 text-sm font-medium rounded-full">
                    React js
                </span>

                <span class="px-3 py-1 bg-orange-100 dark:bg-orange-900 text-orange-700 dark:text-orange-200 text-sm font-medium rounded-full">
                   Supabase
                </span>

                <span class="px-3 py-1 bg-purple-100 dark:bg-purple-900 text-purple-700 dark:text-purple-200 text-sm font-medium rounded-full">
                    Stripe API
                </span>
            </div>

            <!-- Features -->
            <div class="mb-6">
                <p class="font-semibold text-sm mb-2 text-gray-700 dark:text-gray-200">
                    Fitur utama:
                </p>

                <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                    <li>✓ Manajemen inventaris produk</li>
                    <li>✓ Proses pembayaran aman</li>
                    <li>✓ Dashboard analitik admin</li>
                    <li>✓ Desain responsif mobile</li>
                </ul>
            </div>

            <!-- GitHub -->
            <a
                href="https://cylinder-amber.vercel.app/"
                target="_blank"
                rel="noopener noreferrer"
                class="inline-flex items-center gap-2 text-blue-600 dark:text-blue-400 font-semibold hover:gap-4 transition-all"
            >
                Lihat proyek

                <svg
                    class="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M13 7l5 5m0 0l-5 5m5-5H6"
                    ></path>
                </svg>
            </a>

        </div>
    </div>


    <!-- Project 2 -->
    <div data-animate
        class="project-card bg-white dark:bg-gray-700 rounded-xl overflow-hidden border border-gray-200 dark:border-gray-600">

        <!-- Project Image -->
        <div class="h-80 overflow-hidden relative">
            <img
                src="{{ asset('img/Pr2company.png') }}"
                alt="Company Profile"
                class="project-image w-full h-full object-cover transition-transform duration-500 hover:scale-105"
            >
        </div>

        <!-- Project Info -->
        <div class="p-8">

            <h3 class="text-2xl font-bold mb-3">
                Company Profile
            </h3>

            <p class="text-gray-600 dark:text-gray-300 mb-2 text-sm font-semibold">
                Membangun website company profile untuk perusahaan manufaktur dengan desain modern dan responsif.
            </p>

            <p class="text-gray-600 dark:text-gray-300 mb-6 leading-relaxed text-sm">
                Website ini menampilkan informasi perusahaan, layanan, portofolio, dan kontak. Dibangun menggunakan Laravel untuk backend dan React untuk frontend, dengan desain yang responsif agar dapat diakses dengan baik di berbagai perangkat.
            </p>

            <!-- Dampak -->
            <div class="mb-4 p-3 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg border border-emerald-100 dark:border-emerald-800">
                <p class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">
                    Dampak:
                </p>

                <ul class="text-xs text-gray-600 dark:text-gray-400 space-y-0.5">
                    <li>• Mengenalkan 80% Company lebih cepat dengan pembaruan real-time</li>
                    <li>• Penurunan 50% email switching konteks</li>
                    <li>• Uptime 99,95% melalui infrastruktur Supabase</li>
                </ul>
            </div>

            <!-- Technologies -->
            <div class="flex flex-wrap gap-2 mb-6">
                <span class="px-3 py-1 bg-purple-100 dark:bg-purple-900 text-purple-700 dark:text-purple-200 text-sm font-medium rounded-full">
                    React + TS
                </span>

                <span class="px-3 py-1 bg-emerald-100 dark:bg-emerald-900 text-emerald-700 dark:text-emerald-200 text-sm font-medium rounded-full">
                    Supabase
                </span>

                <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-200 text-sm font-medium rounded-full">
                    WebSocket
                </span>

                <span class="px-3 py-1 bg-yellow-100 dark:bg-yellow-900 text-yellow-700 dark:text-yellow-200 text-sm font-medium rounded-full">
                    Tailwind
                </span>
            </div>

            <!-- Features -->
            <div class="mb-6">
                <p class="font-semibold text-sm mb-2 text-gray-700 dark:text-gray-200">
                    Fitur utama:
                </p>

                <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                    <li>✓ Pembaruan informasi real-time</li>
                    <li>✓ Antarmuka yang ramah pengguna</li>
                    <li>✓ Animasi dan transisi halus</li>
                    <li>✓ Pengenalan Company yang lebih modern dan menarik</li>
                </ul>
            </div>

            <!-- GitHub -->
            <a
                href="https://company-profile-rho-dusky.vercel.app/"
                target="_blank"
                rel="noopener noreferrer"
                class="inline-flex items-center gap-2 text-blue-600 dark:text-blue-400 font-semibold hover:gap-4 transition-all"
            >
                Lihat proyek

                <svg
                    class="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M13 7l5 5m0 0l-5 5m5-5H6"
                    ></path>
                </svg>
            </a>

        </div>
    </div>

</div>


    <!-- ============================================
         4. CONTACT FORM SECTION
         ============================================ -->
    <section id="contact" class="py-20 bg-white dark:bg-gray-900">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Title -->
            <div data-aos="fade-up" class="text-center mb-12">
                <h2 class="text-4xl sm:text-5xl font-bold mb-4">Hubungi Saya</h2>
                <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                    Punya proyek yang ingin dibangun? Mari kolaborasi dan buat sesuatu yang bermanfaat bersama.
                </p>
            </div>
            
            <!-- Contact Form -->
            <div class="max-w-2xl mx-auto" data-animate>
                <form id="contactForm" class="bg-gray-50 dark:bg-gray-800 p-8 rounded-xl border border-gray-200 dark:border-gray-700"
                      x-data="contactForm()" @submit.prevent="handleSubmit">
                    
                    <!-- Name Field -->
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">
                            Nama Anda
                        </label>
                        <input type="text" id="name" name="name" required
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                               placeholder="John Doe"
                               x-model="form.name">
                    </div>
                    
                    <!-- Email Field -->
                    <div class="mb-6">
                        <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">
                            Alamat Email
                        </label>
                        <input type="email" id="email" name="email" required
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                               placeholder="john@example.com"
                               x-model="form.email">
                    </div>
                    
                    <!-- Subject Field -->
                    <div class="mb-6">
                        <label for="subject" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">
                            Subjek
                        </label>
                        <input type="text" id="subject" name="subject" required
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                               placeholder="Kolaborasi proyek"
                               x-model="form.subject">
                    </div>
                    
                    <!-- Message Field -->
                    <div class="mb-6">
                        <label for="message" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">
                            Pesan
                        </label>
                        <textarea id="message" name="message" required rows="5"
                                  class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition resize-none"
                                  placeholder="Ceritakan tentang proyek Anda..."
                                  x-model="form.message"></textarea>
                    </div>
                    
                    <!-- Status Messages -->
                    <div x-show="status.type === 'success'" class="mb-6 p-4 rounded-lg bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-200">
                        ✓ Pesan berhasil dikirim! Saya akan segera membalas Anda.
                    </div>
                    
                    <div x-show="status.type === 'error'" class="mb-6 p-4 rounded-lg bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-200">
                        ✗ Terjadi kesalahan saat mengirim pesan. Silakan coba lagi.
                    </div>
                    
                    <!-- Submit Button -->
<a
    href="https://wa.me/6287842799905?text=Halo%2C%20saya%20tertarik%20dengan%20project%20Anda."
    target="_blank"
    rel="noopener noreferrer"
    class="form-submit w-full px-8 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-all flex items-center justify-center gap-2"
>
    Kirim Pesan

    <svg
        class="w-5 h-5"
        fill="currentColor"
        viewBox="0 0 24 24"
    >
        <path d="M20.52 3.48A11.84 11.84 0 0 0 12.05 0C5.5 0 .18 5.32.18 11.87c0 2.09.55 4.13 1.6 5.92L.1 24l6.35-1.66a11.83 11.83 0 0 0 5.6 1.42h.01c6.55 0 11.87-5.32 11.87-11.87 0-3.17-1.24-6.15-3.41-8.41ZM12.06 21.7h-.01a9.83 9.83 0 0 1-5.01-1.37l-.36-.21-3.77.99 1.01-3.67-.23-.38a9.84 9.84 0 0 1-1.51-5.19c0-5.42 4.42-9.84 9.85-9.84 2.63 0 5.1 1.03 6.96 2.9a9.79 9.79 0 0 1 2.88 6.96c0 5.42-4.42 9.84-9.81 9.84Zm5.4-7.37c-.3-.15-1.77-.87-2.04-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.94 1.17-.17.2-.35.22-.65.07-.3-.15-1.26-.46-2.4-1.48-.89-.79-1.49-1.76-1.66-2.06-.17-.3-.02-.46.13-.61.13-.13.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.07-.15-.67-1.61-.92-2.21-.24-.58-.49-.5-.67-.51h-.57c-.2 0-.52.07-.79.37-.27.3-1.04 1.02-1.04 2.49s1.07 2.89 1.22 3.09c.15.2 2.1 3.21 5.09 4.5.71.31 1.27.49 1.7.63.71.23 1.36.2 1.87.12.57-.09 1.77-.72 2.02-1.42.25-.7.25-1.3.17-1.42-.07-.12-.27-.2-.57-.35Z"/>
    </svg>
</a>

                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- ============================================
         5. FOOTER
         ============================================ -->
    <footer class="bg-gray-900 text-white py-12 border-t border-gray-800">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 mb-8">
                <div>
                    <h3 class="font-bold text-lg mb-4">Tautan Cepat</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#skills" class="hover:text-white transition">Keahlian</a></li>
                        <li><a href="#projects" class="hover:text-white transition">Proyek</a></li>
                        <li><a href="#contact" class="hover:text-white transition">Kontak</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-bold text-lg mb-4">Sosial</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="https://github.com/chocopotat" class="hover:text-white transition">GitHub</a></li>
                        <li><a href="https://www.linkedin.com/in/firman-hidayah-a36a8827a" class="hover:text-white transition">LinkedIn</a></li>
                        <li><a href="https://www.instagram.com/firmansnhida/?igsh=MWJ3Y2J5dTB2d2M3bQ%3D%3D" class="hover:text-white transition">Instagram</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-bold text-lg mb-4">Kontak</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="mailto:hidayahfirman84@gmail.com" class="hover:text-white transition">hidayahfirman84@gmail.com</a></li>
                        <li><a href="tel:+6287842799905" class="hover:text-white transition">+62 878-4279-9905</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 pt-8 text-center text-gray-400">
                <p>&copy; 2024 Fullstack Developer. Dibuat dengan React, Laravel, dan TypeScript.</p>
            </div>
        </div>
    </footer>

    <!-- ============================================
         6. SCRIPTS
         ============================================ -->
    
    <!-- Initialize AOS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                duration: 800,
                easing: 'ease-out-cubic',
                once: true,
                offset: 50
            });
        });
    </script>
    
    <!-- Intersection Observer for [data-animate] elements -->
    <script>
        const observerOptions = {
            threshold: 0.18,
            rootMargin: '0px 0px -80px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    const delay = Number(entry.target.dataset.delay || index * 120);
                    entry.target.style.transitionDelay = `${delay}ms`;
                    requestAnimationFrame(() => {
                        entry.target.classList.add('animate-in');
                    });
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);
        
        document.querySelectorAll('[data-animate]').forEach(el => {
            observer.observe(el);
        });
    </script>

    <script>
        const parallaxItems = document.querySelectorAll('.parallax-orb');

        const updateParallax = () => {
            const scrollY = window.scrollY;
            parallaxItems.forEach((item, index) => {
                const speed = (index + 1) * 0.08;
                const offset = scrollY * speed;
                item.style.transform = `translate3d(0, ${offset * -1}px, 0)`;
            });
        };

        window.addEventListener('scroll', () => {
            requestAnimationFrame(updateParallax);
        }, { passive: true });

        updateParallax();
    </script>
    
    <!-- Alpine.js Contact Form Handler -->
    <script>
        function contactForm() {
            return {
                form: {
                    name: '',
                    email: '',
                    subject: '',
                    message: ''
                },
                loading: false,
                status: {
                    type: null,
                    message: ''
                },
                async handleSubmit() {
                    this.loading = true;
                    this.status.type = null;
                    
                    try {
                        const response = await fetch('/api/contact', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                            },
                            body: JSON.stringify(this.form)
                        });
                        
                        if (response.ok) {
                            this.status.type = 'success';
                            this.form = { name: '', email: '', subject: '', message: '' };

                            const data = await response.json();

                            if (data.whatsapp_url) {
                                setTimeout(() => {
                                    window.open(data.whatsapp_url, '_blank');
                                }, 600);
                            }

                            setTimeout(() => {
                                this.status.type = null;
                            }, 5000);
                        } else {
                            this.status.type = 'error';
                        }
                    } catch (error) {
                        console.error('Form submission error:', error);
                        this.status.type = 'error';
                    } finally {
                        this.loading = false;
                    }
                }
            }
        }
    </script>
</body>
</html>
