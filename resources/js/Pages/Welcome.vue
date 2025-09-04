<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import UrlShortener from '@/Components/UrlShortener.vue';

defineProps<{
    canLogin?: boolean;
    canRegister?: boolean;
    laravelVersion: string;
    phpVersion: string;
}>();
</script>

<template>
    <Head title="Linqify - Organize Your Links" />
    
    <!-- Animated Background -->
    <div class="fixed inset-0 -z-10 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-violet-50 via-cyan-50 to-pink-50"></div>
        <div class="absolute top-0 -left-4 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
        <div class="absolute top-0 -right-4 w-72 h-72 bg-yellow-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-8 left-20 w-72 h-72 bg-pink-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-4000"></div>
    </div>
    
    <!-- Navigation -->
    <nav class="fixed top-0 w-full bg-white/70 backdrop-blur-xl border-b border-white/20 shadow-lg shadow-purple-500/5 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center space-x-3 group">
                    <div class="relative">
                        <ApplicationLogo class="h-8 w-8 text-transparent bg-gradient-to-r from-violet-600 to-pink-600 bg-clip-text transition-transform group-hover:scale-110" />
                        <div class="absolute inset-0 bg-gradient-to-r from-violet-600 to-pink-600 rounded-lg opacity-20 blur-sm group-hover:opacity-30 transition-opacity"></div>
                    </div>
                    <span class="text-xl font-bold bg-gradient-to-r from-violet-600 to-pink-600 bg-clip-text text-transparent">Linqify</span>
                </div>
                
                <!-- Auth Links -->
                <div v-if="canLogin" class="flex items-center space-x-4">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('dashboard')"
                        class="bg-gradient-to-r from-violet-600 to-pink-600 text-white px-6 py-2.5 rounded-full font-medium hover:shadow-lg hover:shadow-violet-500/25 transform hover:-translate-y-0.5 transition-all duration-200"
                    >
                        Dashboard
                    </Link>
                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="text-gray-700 hover:text-violet-600 font-medium transition-colors relative after:content-[''] after:absolute after:w-0 after:h-0.5 after:bottom-0 after:left-0 after:bg-gradient-to-r after:from-violet-600 after:to-pink-600 hover:after:w-full after:transition-all after:duration-300"
                        >
                            Sign In
                        </Link>
                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="bg-gradient-to-r from-violet-600 to-pink-600 text-white px-6 py-2.5 rounded-full font-medium hover:shadow-lg hover:shadow-violet-500/25 transform hover:-translate-y-0.5 transition-all duration-200"
                        >
                            Get Started
                        </Link>
                    </template>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="pt-32 pb-20 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <!-- Floating elements -->
            <div class="absolute top-10 right-10 w-20 h-20 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-2xl opacity-20 animate-float"></div>
            <div class="absolute bottom-10 left-10 w-16 h-16 bg-gradient-to-r from-green-400 to-blue-500 rounded-full opacity-30 animate-float animation-delay-1000"></div>
            
            <div class="text-center relative">
                <div class="mb-8">
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-gradient-to-r from-violet-100 to-pink-100 text-violet-800 border border-violet-200 shadow-sm">
                        ✨ New & Improved
                    </span>
                </div>
                
                <h1 class="text-5xl md:text-7xl font-bold mb-6 leading-tight">
                    <span class="bg-gradient-to-r from-violet-600 via-pink-600 to-cyan-600 bg-clip-text text-transparent animate-gradient-x">
                        Organize Your Links
                    </span>
                    <br>
                    <span class="text-gray-800 relative">
                        Beautifully
                        <svg class="absolute -bottom-2 left-1/2 transform -translate-x-1/2 w-48 h-4 text-pink-400" viewBox="0 0 192 16" fill="none">
                            <path d="M2 14C42.6667 6.66667 85.3333 6.66667 130 14C152.667 8 175.333 8 198 14" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                        </svg>
                    </span>
                </h1>
                
                <p class="text-xl md:text-2xl text-gray-600 mb-10 max-w-4xl mx-auto leading-relaxed">
                    Transform chaos into clarity. Linqify helps you collect, organize, and manage all your important links with 
                    <span class="font-semibold text-violet-600">style</span> and <span class="font-semibold text-pink-600">intelligence</span>.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-6 justify-center items-center">
                    <Link
                        v-if="canRegister && !$page.props.auth.user"
                        :href="route('register')"
                        class="group relative bg-gradient-to-r from-violet-600 to-pink-600 text-white px-8 py-4 rounded-full font-semibold text-lg hover:shadow-2xl hover:shadow-violet-500/25 transform hover:-translate-y-1 transition-all duration-300 overflow-hidden"
                    >
                        <span class="relative z-10">Start Your Journey</span>
                        <div class="absolute inset-0 bg-gradient-to-r from-pink-600 to-violet-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </Link>
                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('dashboard')"
                        class="group relative bg-gradient-to-r from-violet-600 to-pink-600 text-white px-8 py-4 rounded-full font-semibold text-lg hover:shadow-2xl hover:shadow-violet-500/25 transform hover:-translate-y-1 transition-all duration-300 overflow-hidden"
                    >
                        <span class="relative z-10">Go to Dashboard</span>
                        <div class="absolute inset-0 bg-gradient-to-r from-pink-600 to-violet-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </Link>
                    <button class="group relative border-2 border-gray-300 text-gray-700 px-8 py-4 rounded-full font-semibold text-lg hover:border-violet-300 hover:text-violet-600 transition-all duration-300 bg-white/50 backdrop-blur-sm hover:bg-white/80">
                        <span class="flex items-center space-x-2">
                            <span>See It In Action</span>
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </span>
                    </button>
                </div>
                
                <div class="mt-16 flex items-center justify-center space-x-8 text-sm text-gray-500">
                    <div class="flex items-center space-x-2">
                        <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                        <span>Free to start</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-2 h-2 bg-blue-400 rounded-full animate-pulse animation-delay-500"></div>
                        <span>No credit card required</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-2 h-2 bg-purple-400 rounded-full animate-pulse animation-delay-1000"></div>
                        <span>Setup in 2 minutes</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- URL Shortener Section -->
    <section class="py-20 bg-gradient-to-r from-violet-50 via-cyan-50 to-pink-50 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-gradient-to-r from-blue-100 to-purple-100 text-blue-800 border border-blue-200 shadow-sm mb-6">
                    ⚡ Quick & Easy
                </div>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                    <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                        URL Shortener
                    </span>
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Create short, shareable links instantly. Perfect for social media, emails, or anywhere you need clean, professional URLs.
                </p>
            </div>
            
            <div class="flex justify-center">
                <UrlShortener />
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20">
                <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-gradient-to-r from-cyan-100 to-blue-100 text-cyan-800 border border-cyan-200 shadow-sm mb-6">
                    🚀 Powerful Features
                </div>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                    Everything you need to
                    <span class="bg-gradient-to-r from-cyan-600 to-blue-600 bg-clip-text text-transparent">manage links</span>
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                    Powerful features designed to make link management effortless, intuitive, and surprisingly delightful.
                </p>
            </div>
            
            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Feature 1: Categories -->
                <div class="group relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-violet-600 to-purple-600 rounded-3xl opacity-0 group-hover:opacity-100 transform group-hover:scale-105 transition-all duration-300 blur-xl"></div>
                    <div class="relative bg-white/80 backdrop-blur-xl p-8 rounded-3xl border border-white/20 shadow-xl hover:shadow-2xl transition-all duration-300 transform group-hover:-translate-y-2">
                        <div class="mb-6">
                            <div class="w-16 h-16 bg-gradient-to-r from-violet-600 to-purple-600 rounded-2xl flex items-center justify-center mb-4 group-hover:rotate-6 transition-transform duration-300">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14-7l2 2-2 2m2-2H9m10 7l2 2-2 2m2-2H9"></path>
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Smart Categories</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Organize your links into intelligent categories that adapt to your workflow. Create folders for work, personal, research, or any topic that matters to you.
                        </p>
                        <div class="mt-6 flex items-center text-violet-600 font-medium group-hover:translate-x-2 transition-transform duration-200">
                            <span>Learn more</span>
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Feature 2: Search -->
                <div class="group relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-emerald-600 to-teal-600 rounded-3xl opacity-0 group-hover:opacity-100 transform group-hover:scale-105 transition-all duration-300 blur-xl"></div>
                    <div class="relative bg-white/80 backdrop-blur-xl p-8 rounded-3xl border border-white/20 shadow-xl hover:shadow-2xl transition-all duration-300 transform group-hover:-translate-y-2">
                        <div class="mb-6">
                            <div class="w-16 h-16 bg-gradient-to-r from-emerald-600 to-teal-600 rounded-2xl flex items-center justify-center mb-4 group-hover:rotate-6 transition-transform duration-300">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Lightning Search</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Find any link in milliseconds with our AI-powered search. Search by title, description, tags, or even content inside the linked pages.
                        </p>
                        <div class="mt-6 flex items-center text-emerald-600 font-medium group-hover:translate-x-2 transition-transform duration-200">
                            <span>Try it out</span>
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Feature 3: Dashboard -->
                <div class="group relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-pink-600 to-rose-600 rounded-3xl opacity-0 group-hover:opacity-100 transform group-hover:scale-105 transition-all duration-300 blur-xl"></div>
                    <div class="relative bg-white/80 backdrop-blur-xl p-8 rounded-3xl border border-white/20 shadow-xl hover:shadow-2xl transition-all duration-300 transform group-hover:-translate-y-2">
                        <div class="mb-6">
                            <div class="w-16 h-16 bg-gradient-to-r from-pink-600 to-rose-600 rounded-2xl flex items-center justify-center mb-4 group-hover:rotate-6 transition-transform duration-300">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Visual Analytics</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Get beautiful insights into your link collection with stunning charts, usage patterns, and personalized recommendations for better organization.
                        </p>
                        <div class="mt-6 flex items-center text-pink-600 font-medium group-hover:translate-x-2 transition-transform duration-200">
                            <span>View demo</span>
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How it Works Section -->
    <section class="py-20 relative overflow-hidden">
        <!-- Background pattern -->
        <div class="absolute inset-0 bg-gradient-to-br from-gray-50 to-white"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_1px_1px,rgba(0,0,0,0.08)_1px,transparent_0)] bg-[size:32px_32px] opacity-30"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="text-center mb-20">
                <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-gradient-to-r from-orange-100 to-red-100 text-orange-800 border border-orange-200 shadow-sm mb-6">
                    ⚡ Simple Process
                </div>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                    How <span class="bg-gradient-to-r from-orange-600 to-red-600 bg-clip-text text-transparent">Linqify Works</span>
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                    Get started in minutes with our beautifully simple three-step process that feels almost magical.
                </p>
            </div>
            
            <div class="grid lg:grid-cols-3 gap-12 relative">
                <!-- Connection lines -->
                <div class="hidden lg:block absolute top-1/2 left-1/3 w-1/3 h-0.5 bg-gradient-to-r from-orange-300 to-red-300 transform -translate-y-1/2"></div>
                <div class="hidden lg:block absolute top-1/2 right-1/3 w-1/3 h-0.5 bg-gradient-to-r from-red-300 to-pink-300 transform -translate-y-1/2"></div>
                
                <!-- Step 1 -->
                <div class="text-center group">
                    <div class="relative mb-8">
                        <div class="absolute inset-0 bg-gradient-to-r from-orange-600 to-red-600 rounded-full opacity-20 blur-xl transform group-hover:scale-110 transition-all duration-300"></div>
                        <div class="relative w-20 h-20 bg-gradient-to-r from-orange-600 to-red-600 text-white rounded-full flex items-center justify-center mx-auto text-2xl font-bold shadow-lg transform group-hover:rotate-12 transition-all duration-300">
                            1
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4 group-hover:text-orange-600 transition-colors duration-200">Create Categories</h3>
                    <p class="text-gray-600 leading-relaxed text-lg">
                        Start by creating beautiful categories that match your lifestyle. Work, Personal, Learning, or anything that sparks joy.
                    </p>
                    <div class="mt-6 inline-flex items-center text-orange-600 font-medium opacity-0 group-hover:opacity-100 transition-all duration-300">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        30 seconds setup
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="text-center group">
                    <div class="relative mb-8">
                        <div class="absolute inset-0 bg-gradient-to-r from-red-600 to-pink-600 rounded-full opacity-20 blur-xl transform group-hover:scale-110 transition-all duration-300"></div>
                        <div class="relative w-20 h-20 bg-gradient-to-r from-red-600 to-pink-600 text-white rounded-full flex items-center justify-center mx-auto text-2xl font-bold shadow-lg transform group-hover:rotate-12 transition-all duration-300">
                            2
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4 group-hover:text-red-600 transition-colors duration-200">Add Your Links</h3>
                    <p class="text-gray-600 leading-relaxed text-lg">
                        Save links with smart titles, rich descriptions, and tags. Our AI helps suggest the perfect category automatically.
                    </p>
                    <div class="mt-6 inline-flex items-center text-red-600 font-medium opacity-0 group-hover:opacity-100 transition-all duration-300">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        Lightning fast
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="text-center group">
                    <div class="relative mb-8">
                        <div class="absolute inset-0 bg-gradient-to-r from-pink-600 to-purple-600 rounded-full opacity-20 blur-xl transform group-hover:scale-110 transition-all duration-300"></div>
                        <div class="relative w-20 h-20 bg-gradient-to-r from-pink-600 to-purple-600 text-white rounded-full flex items-center justify-center mx-auto text-2xl font-bold shadow-lg transform group-hover:rotate-12 transition-all duration-300">
                            3
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4 group-hover:text-pink-600 transition-colors duration-200">Stay Organized</h3>
                    <p class="text-gray-600 leading-relaxed text-lg">
                        Access your curated links anywhere, discover patterns in your interests, and keep your digital life beautifully organized.
                    </p>
                    <div class="mt-6 inline-flex items-center text-pink-600 font-medium opacity-0 group-hover:opacity-100 transition-all duration-300">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        Pure joy
                    </div>
                </div>
            </div>
            
            <!-- Fun stats -->
            <div class="mt-20 grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="text-3xl md:text-4xl font-bold bg-gradient-to-r from-violet-600 to-pink-600 bg-clip-text text-transparent mb-2">10k+</div>
                    <div class="text-gray-600">Happy Users</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl md:text-4xl font-bold bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent mb-2">500k+</div>
                    <div class="text-gray-600">Links Saved</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl md:text-4xl font-bold bg-gradient-to-r from-orange-600 to-red-600 bg-clip-text text-transparent mb-2">99.9%</div>
                    <div class="text-gray-600">Uptime</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl md:text-4xl font-bold bg-gradient-to-r from-pink-600 to-purple-600 bg-clip-text text-transparent mb-2">2 min</div>
                    <div class="text-gray-600">Setup Time</div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 relative overflow-hidden">
        <!-- Animated background -->
        <div class="absolute inset-0 bg-gradient-to-br from-violet-600 via-purple-600 to-pink-600"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_50%,rgba(255,255,255,0.1)_0%,transparent_50%)] animate-pulse"></div>
        
        <!-- Floating shapes -->
        <div class="absolute top-10 left-10 w-20 h-20 bg-white/10 rounded-full animate-float"></div>
        <div class="absolute bottom-10 right-10 w-32 h-32 bg-white/5 rounded-full animate-float animation-delay-2000"></div>
        <div class="absolute top-1/2 right-20 w-16 h-16 bg-white/10 rounded-2xl rotate-45 animate-float animation-delay-1000"></div>
        
        <div class="max-w-5xl mx-auto text-center px-4 sm:px-6 lg:px-8 relative">
            <div class="mb-8">
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-white/20 text-white border border-white/20 backdrop-blur-sm">
                    🎉 Join the Revolution
                </span>
            </div>
            
            <h2 class="text-4xl md:text-6xl font-bold text-white mb-6 leading-tight">
                Ready to transform
                <br>
                <span class="relative">
                    your digital life?
                    <svg class="absolute -bottom-2 left-1/2 transform -translate-x-1/2 w-64 h-4 text-pink-300" viewBox="0 0 256 16" fill="none">
                        <path d="M2 14C64 6.66667 128 6.66667 192 14C213.333 8 234.667 8 256 14" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                    </svg>
                </span>
            </h2>
            
            <p class="text-xl md:text-2xl text-white/90 mb-12 max-w-3xl mx-auto leading-relaxed">
                Join thousands of users who have already transformed chaos into clarity. 
                Your future organized self will thank you.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-6 justify-center items-center mb-12">
                <Link
                    v-if="canRegister && !$page.props.auth.user"
                    :href="route('register')"
                    class="group relative bg-white text-violet-600 px-10 py-4 rounded-full font-bold text-lg hover:shadow-2xl hover:shadow-black/20 transform hover:-translate-y-1 transition-all duration-300 overflow-hidden"
                >
                    <span class="relative z-10 flex items-center space-x-2">
                        <span>Start Your Journey</span>
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </span>
                    <div class="absolute inset-0 bg-gradient-to-r from-white to-gray-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </Link>
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('dashboard')"
                    class="group relative bg-white text-violet-600 px-10 py-4 rounded-full font-bold text-lg hover:shadow-2xl hover:shadow-black/20 transform hover:-translate-y-1 transition-all duration-300 overflow-hidden"
                >
                    <span class="relative z-10 flex items-center space-x-2">
                        <span>Go to Dashboard</span>
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </span>
                    <div class="absolute inset-0 bg-gradient-to-r from-white to-gray-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </Link>
                <button class="group relative border-2 border-white/30 text-white px-10 py-4 rounded-full font-semibold text-lg hover:border-white hover:bg-white/10 transition-all duration-300 backdrop-blur-sm">
                    <span class="flex items-center space-x-2">
                        <span>Watch Demo</span>
                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </span>
                </button>
            </div>
            
            <!-- Trust indicators -->
            <div class="flex flex-wrap items-center justify-center gap-8 text-white/80">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-green-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span>No spam, ever</span>
                </div>
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-blue-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span>Bank-level security</span>
                </div>
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-purple-300" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                    <span>5-star rated</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="relative bg-gradient-to-br from-gray-900 via-gray-800 to-black text-white py-16 overflow-hidden">
        <!-- Background pattern -->
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_1px_1px,rgba(255,255,255,0.05)_1px,transparent_0)] bg-[size:24px_24px]"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="grid lg:grid-cols-4 gap-8 mb-12">
                <!-- Brand -->
                <div class="lg:col-span-2">
                    <div class="flex items-center space-x-3 mb-6 group">
                        <div class="relative">
                            <ApplicationLogo class="h-10 w-10 text-transparent bg-gradient-to-r from-violet-400 to-pink-400 bg-clip-text transition-transform group-hover:scale-110" />
                            <div class="absolute inset-0 bg-gradient-to-r from-violet-400 to-pink-400 rounded-lg opacity-20 blur-sm group-hover:opacity-30 transition-opacity"></div>
                        </div>
                        <span class="text-2xl font-bold bg-gradient-to-r from-violet-400 to-pink-400 bg-clip-text text-transparent">Linqify</span>
                    </div>
                    <p class="text-gray-400 mb-6 text-lg leading-relaxed max-w-md">
                        The beautifully simple way to organize all your important links in one place. 
                        Transform digital chaos into organized bliss.
                    </p>
                    
                    <!-- Social links -->
                    <div class="flex items-center space-x-4">
                        <a href="#" class="w-10 h-10 bg-white/5 rounded-lg flex items-center justify-center hover:bg-gradient-to-r hover:from-violet-500 hover:to-pink-500 transition-all duration-300 group">
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-white transition-colors" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-white/5 rounded-lg flex items-center justify-center hover:bg-gradient-to-r hover:from-violet-500 hover:to-pink-500 transition-all duration-300 group">
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-white transition-colors" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-white/5 rounded-lg flex items-center justify-center hover:bg-gradient-to-r hover:from-violet-500 hover:to-pink-500 transition-all duration-300 group">
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-white transition-colors" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-white/5 rounded-lg flex items-center justify-center hover:bg-gradient-to-r hover:from-violet-500 hover:to-pink-500 transition-all duration-300 group">
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-white transition-colors" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.219-5.175 1.219-5.175s-.31-.219-.31-.219c0-1.735 1.004-3.029 2.254-3.029 1.063 0 1.576.8 1.576 1.755 0 1.07-.681 2.672-1.032 4.155-.293 1.239.62 2.25 1.84 2.25 2.207 0 3.905-2.325 3.905-5.686 0-2.971-2.134-5.046-5.179-5.046-3.53 0-5.606 2.625-5.606 5.336 0 1.057.409 2.19.918 2.805.101.123.115.23.085.355-.092.393-.297 1.19-.337 1.357-.053.218-.173.265-.4.159-1.492-.694-2.424-2.875-2.424-4.627 0-3.769 2.737-7.229 7.892-7.229 4.144 0 7.365 2.953 7.365 6.899 0 4.117-2.595 7.431-6.199 7.431-1.211 0-2.348-.63-2.738-1.378 0 0-.599 2.282-.744 2.840-.269 1.045-1.004 2.352-1.498 3.146 1.123.345 2.306.535 3.55.535 6.624 0 11.99-5.367 11.99-11.987C24.007 5.367 18.641.001.012.001z.005-.001z"/>
                            </svg>
                        </a>
                    </div>
                </div>
                
                <!-- Product Links -->
                <div>
                    <h3 class="font-bold mb-6 text-lg bg-gradient-to-r from-violet-400 to-pink-400 bg-clip-text text-transparent">Product</h3>
                    <ul class="space-y-4">
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-200 flex items-center group">
                            <span>Features</span>
                            <svg class="w-4 h-4 ml-1 opacity-0 group-hover:opacity-100 group-hover:translate-x-1 transition-all duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-200 flex items-center group">
                            <span>Pricing</span>
                            <svg class="w-4 h-4 ml-1 opacity-0 group-hover:opacity-100 group-hover:translate-x-1 transition-all duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-200 flex items-center group">
                            <span>API</span>
                            <svg class="w-4 h-4 ml-1 opacity-0 group-hover:opacity-100 group-hover:translate-x-1 transition-all duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-200 flex items-center group">
                            <span>Support</span>
                            <svg class="w-4 h-4 ml-1 opacity-0 group-hover:opacity-100 group-hover:translate-x-1 transition-all duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a></li>
                    </ul>
                </div>
                
                <!-- Company Links -->
                <div>
                    <h3 class="font-bold mb-6 text-lg bg-gradient-to-r from-emerald-400 to-cyan-400 bg-clip-text text-transparent">Company</h3>
                    <ul class="space-y-4">
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-200 flex items-center group">
                            <span>About Us</span>
                            <svg class="w-4 h-4 ml-1 opacity-0 group-hover:opacity-100 group-hover:translate-x-1 transition-all duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-200 flex items-center group">
                            <span>Privacy Policy</span>
                            <svg class="w-4 h-4 ml-1 opacity-0 group-hover:opacity-100 group-hover:translate-x-1 transition-all duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-200 flex items-center group">
                            <span>Terms of Service</span>
                            <svg class="w-4 h-4 ml-1 opacity-0 group-hover:opacity-100 group-hover:translate-x-1 transition-all duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-200 flex items-center group">
                            <span>Contact</span>
                            <svg class="w-4 h-4 ml-1 opacity-0 group-hover:opacity-100 group-hover:translate-x-1 transition-all duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 mb-4 md:mb-0">
                    &copy; 2025 Linqify. Built with ❤️
                </p>
                <div class="flex items-center space-x-6 text-gray-400 text-sm">
                    <span class="flex items-center space-x-1">
                        <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                        <span>All systems operational</span>
                    </span>
                </div>
            </div>
        </div>
        </footer>
</template>

<style scoped>
@keyframes blob {
    0% {
        transform: translate(0px, 0px) scale(1);
    }
    33% {
        transform: translate(30px, -50px) scale(1.1);
    }
    66% {
        transform: translate(-20px, 20px) scale(0.9);
    }
    100% {
        transform: translate(0px, 0px) scale(1);
    }
}

@keyframes float {
    0%, 100% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-20px);
    }
}

@keyframes gradient-x {
    0%, 100% {
        background-size: 200% 200%;
        background-position: left center;
    }
    50% {
        background-size: 200% 200%;
        background-position: right center;
    }
}

.animate-blob {
    animation: blob 7s infinite;
}

.animate-float {
    animation: float 3s ease-in-out infinite;
}

.animate-gradient-x {
    animation: gradient-x 3s ease infinite;
}

.animation-delay-2000 {
    animation-delay: 2s;
}

.animation-delay-4000 {
    animation-delay: 4s;
}

.animation-delay-1000 {
    animation-delay: 1s;
}

.animation-delay-500 {
    animation-delay: 0.5s;
}

/* Hover effects for feature cards */
.group:hover .group-hover\:scale-105 {
    transform: scale(1.05);
}

.group:hover .group-hover\:rotate-6 {
    transform: rotate(6deg);
}

.group:hover .group-hover\:-translate-y-2 {
    transform: translateY(-8px);
}

/* Custom gradient backgrounds */
.bg-gradient-to-br {
    background-image: linear-gradient(to bottom right, var(--tw-gradient-stops));
}

.bg-gradient-to-r {
    background-image: linear-gradient(to right, var(--tw-gradient-stops));
}
</style>
