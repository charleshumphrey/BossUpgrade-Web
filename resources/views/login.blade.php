<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ config('app.name') }}</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite('resources/css/app.css')

    @vite('resources/js/app.js')
</head>

<body class="p-0 m-0">
    <div class="bg-zinc-100 w-screen h-screen justify-center flex items-center">
        <div class="bg-white flex flex-col md:flex-row max-w-5xl shadow-md w-full rounded-md mx-5 outline-1 outline-red-800">
            <div class="md:flex flex-col hidden w-1/2">
                <div class="clip-path-polygon flex justify-center items-center relative h-2/3">
                    <img class="absolute" src="{{ asset('build/assets/images/restobar.jpg') }}" alt="restobar">
                    <div class="z-10 bg-black opacity-70 absolute flex h-full w-full">
                    </div>
                    <div class="rounded-md w-24 h-24 bg-primary-color z-20 absolute w-10 h-10 p-2">
                        <img src="{{ asset('build/assets/images/BossUpgrade_logo.jpg') }}" alt="">
                    </div>
                </div>
                <div class="h-1/3 px-5 py-3 flex flex-col gap-2">
                    <h1 class="font-bold text-xl">Admin/Staff Control Panel</h1>
                    <p class="font-thin text-xs">
                        Welcome to the Admin/Staff Control Panel. Please log in to manage orders, menu items, and customer transactions for BossUpgrade Resto Bar. Ensure secure access to streamline your restaurant operations and enhance customer service.
                    </p>
                </div>
            </div>
            <div class="md:w-1/2 w-full md:px-14 px-10 py-10 flex flex-col">
                <div class="md:hidden h-20 w-20 self-center rounded-md mb-8">
                    <img src="{{ asset('build/assets/images/BossUpgrade_logo.jpg') }}" alt="">
                </div>
                <form action="{{ route('login-auth') }}" method="GET" class="w-full flex flex-col gap-4">
                    @csrf
                    <h1 class="self-center font-poppins_regular font-bold text-md md:text-lg text-gray-800">Login</h1>

                    @if (session('error'))
                    <p class="text-red-600">
                        {{ session('error') }}
                    </p>
                    @endif

                    <div>
                        <label for="username" class="p-1 sm:text-sm text-xs font-poppins_bold font-bold text-gray-500">Username</label>
                        <div class="focus-within:border-black border-1 border-zinc-300 bg-zinc-50 rounded-md flex w-full border border-gray-400 p-2 items-center">
                            <label for="username" class="flex justify-center">
                                <i class="p-1 text-gray-400 fa-solid fa-user"></i>
                            </label>
                            <input name="username" id="username" type="text" class="pl-2 w-full outline-none bg-zinc-50" placeholder="e.g, admin1234">
                        </div>
                        @error('username')
                        <p class="text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password" class="p-1 sm:text-sm text-xs font-poppins_bold font-bold text-gray-500">Password</label>
                        <div class="focus-within:border-black border-1 border-zinc-300 bg-zinc-50 rounded-md flex w-full border border-gray-400 p-2 items-center">
                            <label for="password" class="flex justify-center">
                                <i class="p-1 text-gray-400 fa-solid fa-lock"></i>
                            </label>
                            <input name="password" id="password" type="password" class="pl-2 w-full outline-none bg-zinc-50" placeholder="••••••••">
                        </div>
                        @error('password')
                        <p class="text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <button class="hover:bg-gray-900 bg-primary-color text-gray-100 text-sm py-3 sm:text-md rounded-sm font-poppins_regular mt-7">Log In</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>