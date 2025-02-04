<nav class="bg-gradient-to-r from-gray-800 via-gray-700 to-gray-600 py-4">
    <div class="container mx-auto flex justify-between items-center">
        <a class="text-2xl font-bold text-white" href="/">My Stream</a>
        <button class="md:hidden text-white focus:outline-none">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                 xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
        <div class="w-full md:flex md:items-center md:w-auto">
            <ul class="md:flex md:space-x-6">
                @auth
                    <span class="text-white font-bold mr-4">Welcome, {{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="block md:px-8 md:flex-1 uppercase text-gray-300 font-bold transition-transform duration-300 ease-in-out hover:text-white">
                            Logout
                        </button>
                    </form>
                @else
                    <form method="GET" action="/login">
                        <button type="submit"
                                class="block md:px-8 md:flex-1 uppercase text-gray-300 font-bold transition-transform duration-300 ease-in-out hover:text-white">
                            Login
                        </button>
                    </form>
                    <form method="GET" action="/register">
                        <button type="submit"
                                class="block md:px-8 md:flex-1 uppercase text-gray-300 font-bold transition-transform duration-300 ease-in-out hover:text-white">
                            Register
                        </button>
                    </form>
                @endauth
            </ul>
        </div>
    </div>
</nav>
