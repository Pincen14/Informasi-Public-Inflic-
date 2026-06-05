<x-app-layout>
    <!-- Header banner -->
    <div class="bg-gradient-to-r from-purple-700 to-indigo-800 text-white py-12 px-6 sm:px-8 lg:px-12 shadow-inner">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-3xl sm:text-4xl font-bold tracking-tight">Profil Admin</h1>
            <p class="text-purple-100 mt-2 text-sm sm:text-base">
                Kelola informasi akun admin, kata sandi, dan foto profil Anda.
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left: Profile Display Card -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-6">
                    <div class="h-24 bg-gradient-to-r from-purple-500 to-indigo-600"></div>
                    <div class="px-6 pb-8 text-center -mt-12 flex flex-col items-center">
                        <!-- Avatar -->
                        <div class="relative group">
                            @if(auth()->user()->profile_image)
                                <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" 
                                     class="w-28 h-28 rounded-full object-cover border-4 border-white shadow-md bg-white">
                            @else
                                <div class="w-28 h-28 rounded-full bg-purple-100 flex items-center justify-center border-4 border-white text-purple-700 font-bold text-3xl shadow-md">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            @endif
                            <!-- Role Badge -->
                            <span class="absolute bottom-0 right-0 px-2 py-0.5 text-xs font-semibold tracking-wider text-white bg-purple-600 rounded-full border-2 border-white shadow">
                                {{ strtoupper(auth()->user()->role) }}
                            </span>
                        </div>

                        <!-- Name & Username -->
                        <h2 class="text-xl font-bold text-gray-900 mt-4">{{ auth()->user()->name }}</h2>
                        <p class="text-sm text-gray-500 font-mono mt-1">@<span>{{ auth()->user()->username }}</span></p>

                        <hr class="w-full my-6 border-gray-100">

                        <!-- Quick Details -->
                        <div class="w-full text-left space-y-4 text-sm">
                            <div class="flex items-center text-gray-600">
                                <svg class="w-5 h-5 mr-3 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <span class="break-all">{{ auth()->user()->email }}</span>
                            </div>
                            <div class="flex items-center text-gray-600">
                                <svg class="w-5 h-5 mr-3 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 00.96.725h1.582a1 1 0 00.96-.725l.548-2.2A1 1 0 0115.3 3H18a2 2 0 012 2v3.28a1 1 0 01-.725.94l-2.2.548a1 1 0 00-.725.96v1.582a1 1 0 00.725.96l2.2.548a1 1 0 01.725.94V18a2 2 0 01-2 2h-3.28a1 1 0 01-.94-.725l-.548-2.2a1 1 0 00-.96-.725h-1.582a1 1 0 00-.96.725l-.548 2.2a1 1 0 01-.94.725H5a2 2 0 01-2-2v-3.28a1 1 0 01.725-.94l2.2-.548a1 1 0 00.725-.96v-1.582a1 1 0 00-.725-.96l-2.2-.548A1 1 0 013 8.3V5z"></path>
                                </svg>
                                <span>{{ auth()->user()->phone }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Forms -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Profile Info Form -->
                <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-gray-100">
                    @include('admin.partials.update-profile-information-form')
                </div>

                <!-- Update Password Form -->
                <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-gray-100">
                    @include('profile.partials.update-password-form')
                </div>

                <!-- Delete Account Form -->
                <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-gray-100">
                    @include('admin.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
