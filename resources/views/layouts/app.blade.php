<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Tracker</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">

    {{-- Navbar --}}
    <nav class="bg-white shadow px-6 py-4 flex justify-between items-center">
        <h1 class="text-xl font-bold text-indigo-600">Expense Tracker</h1>
        <div class="flex items-center gap-4">
            <img src="{{ auth()->user()->avatar }}"
                 class="w-6 h-6 rounded-full">
            <span>{{ auth()->user()->name }}</span>
            <a href="{{ route('logout') }}" class="text-red-500 hover">
                Logout
            </a>
        </div>
    </nav>

    {{-- Content --}}
    <main class="max-w-6xl mx-auto p-6">
        @yield('content')
    </main>

</body>
</html>
