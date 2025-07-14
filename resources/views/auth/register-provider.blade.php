<x-guest-layout>
    <div class="max-w-md mx-auto mt-10 bg-white dark:bg-gray-800 p-8 rounded-lg shadow">
        <div class="flex items-center justify-center mb-6">
            <svg class="w-10 h-10 bg-gradient-to-br from-indigo-400 to-purple-600 rounded-lg flex items-center justify-center text-blue p-2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="utensils" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M221.6 148.7C224.7 161.3 224.8 174.5 222.1 187.2C219.3 199.1 213.6 211.9 205.6 222.1C191.1 238.6 173 249.1 151.1 254.1V472C151.1 482.6 147.8 492.8 140.3 500.3C132.8 507.8 122.6 512 111.1 512C101.4 512 91.22 507.8 83.71 500.3C76.21 492.8 71.1 482.6 71.1 472V254.1C50.96 250.1 31.96 238.9 18.3 222.4C10.19 212.2 4.529 200.3 1.755 187.5C-1.019 174.7-.8315 161.5 2.303 148.8L32.51 12.45C33.36 8.598 35.61 5.197 38.82 2.9C42.02 .602 45.97-.4297 49.89 .0026C53.82 .4302 57.46 2.303 60.1 5.259C62.74 8.214 64.18 12.04 64.16 16V160H81.53L98.62 11.91C99.02 8.635 100.6 5.621 103.1 3.434C105.5 1.248 108.7 .0401 111.1 .0401C115.3 .0401 118.5 1.248 120.9 3.434C123.4 5.621 124.1 8.635 125.4 11.91L142.5 160H159.1V16C159.1 12.07 161.4 8.268 163.1 5.317C166.6 2.366 170.2 .474 174.1 .0026C178-.4262 181.1 .619 185.2 2.936C188.4 5.253 190.6 8.677 191.5 12.55L221.6 148.7zM448 472C448 482.6 443.8 492.8 436.3 500.3C428.8 507.8 418.6 512 408 512C397.4 512 387.2 507.8 379.7 500.3C372.2 492.8 368 482.6 368 472V352H351.2C342.8 352 334.4 350.3 326.6 347.1C318.9 343.8 311.8 339.1 305.8 333.1C299.9 327.1 295.2 320 291.1 312.2C288.8 304.4 287.2 296 287.2 287.6L287.1 173.8C288 136.9 299.1 100.8 319.8 70.28C340.5 39.71 369.8 16.05 404.1 2.339C408.1 .401 414.2-.3202 419.4 .2391C424.6 .7982 429.6 2.62 433.9 5.546C438.2 8.472 441.8 12.41 444.2 17.03C446.7 21.64 447.1 26.78 448 32V472z"></path></svg>
        </div>
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800 dark:text-gray-100">FreshLocal</h2>
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800 dark:text-gray-100">Provider Sign Up</h2>
        <form method="POST" action="{{ route('register.provider') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-200 mb-2">Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required autofocus class="w-full border border-gray-300 rounded-md px-3 py-2 dark:bg-gray-900 dark:text-gray-100 dark:border-gray-700">
                @error('name')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-200 mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full border border-gray-300 rounded-md px-3 py-2 dark:bg-gray-900 dark:text-gray-100 dark:border-gray-700">
                @error('email')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-200 mb-2">Password</label>
                <input type="password" name="password" required class="w-full border border-gray-300 rounded-md px-3 py-2 dark:bg-gray-900 dark:text-gray-100 dark:border-gray-700">
                @error('password')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 dark:text-gray-200 mb-2">Confirm Password</label>
                <input type="password" name="password_confirmation" required class="w-full border border-gray-300 rounded-md px-3 py-2 dark:bg-gray-900 dark:text-gray-100 dark:border-gray-700">
            </div>
            <div class="mb-4 text-sm text-gray-600 dark:text-gray-300">
                Your account will be reviewed by an admin. You will be notified once approved and can then log in to manage your food items.
            </div>
            <button type="submit" class="w-full bg-orange-600 text-white px-4 py-2 rounded-md hover:bg-orange-700 font-semibold">Sign Up as Provider</button>
        </form>
        <div class="mt-4 text-center">
            <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Already have an account? Login</a>
        </div>
    </div>
</x-guest-layout> 