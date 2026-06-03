<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Tracker — Login</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded-lg shadow-md w-96">
        <h1 class="text-2xl font-bold text-center mb-6">
            Expense Tracker
        </h1>

        <a href="{{ route('auth.google') }}"
           class="flex items-center justify-center gap-3
                  bg-white border border-gray-300 rounded-lg
                  px-6 py-3 text-gray-700 font-medium
                  hover:bg-gray-50 w-full">
            <img src="https://www.google.com/favicon.ico" class="w-5 h-5">
            Sign in with Google
        </a>
    </div>

</body>
</html>
