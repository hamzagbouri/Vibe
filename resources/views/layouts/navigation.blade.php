<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <img src="{{ asset('uploads/logo.jpeg') }}" alt="Vibe" class="w-16">
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('requests')" :active="request()->routeIs('requests')">
                        {{ __('Requests') }}
                    </x-nav-link>
                    <x-nav-link :href="route('friends')" :active="request()->routeIs('friends')">
                        {{ __('Friends') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6 relative">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <div class="flex items-center space-x-2">
                            <!-- Authenticated User Name -->
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>

                                <div class="ml-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>

                            <!-- Notification Button -->
                            <button id="notificationButton" class="relative text-gray-500 hover:text-gray-700 focus:outline-none">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14V10A6 6 0 006 10v4c0 .386-.146.763-.405 1.062L4 17h5m6 0a3 3 0 01-6 0"></path>
                                </svg>
                                <span id="notificationBadge" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full px-1 hidden">0</span>
                            </button>
                        </div>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                             onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>

                <!-- Notification Dropdown -->
                <div id="notificationDropdown" class="absolute right-0 mt-2 w-64 bg-white border rounded-lg shadow-lg hidden">
                    <ul id="notificationList" class="py-2 px-4 text-gray-700">
                        <li class="text-center text-gray-500 text-sm">No new notifications</li>
                    </ul>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    if (typeof Echo !== "undefined") {
                        Echo.private(`user.${{{ auth()->id() }}}`)
                            .listen("FriendRequestAccepted", (event) => {
                                let notificationList = document.getElementById("notificationList");
                                let notificationBadge = document.getElementById("notificationBadge");

                                // Add new notification
                                let newNotification = document.createElement("li");
                                newNotification.textContent = `${event.sender_name} accepted your friend request!`;
                                notificationList.appendChild(newNotification);

                                // Update badge count
                                let count = parseInt(notificationBadge.textContent) || 0;
                                notificationBadge.textContent = count + 1;
                                notificationBadge.classList.remove("hidden");
                            });

                        // Show dropdown when clicking the notification icon
                        document.getElementById("notificationButton").addEventListener("click", function () {
                            document.getElementById("notificationDropdown").classList.toggle("hidden");
                            document.getElementById("notificationBadge").classList.add("hidden");
                        });
                    } else {
                        console.error("Echo is not defined. Make sure Laravel Echo is set up correctly.");
                    }
                });

            </script>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('requests')" :active="request()->routeIs('requests')">
                {{ __('Requests') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('friends')" :active="request()->routeIs('friends')">
                {{ __('Friends') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
