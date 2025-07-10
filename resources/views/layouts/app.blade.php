<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Панель управления статистикой</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/svg+xml" href="https://laravel.com/favicon.svg">


</head>
<body class="max-w-6xl mx-auto px-4 bg-gray-100 text-gray-800">
<header class="bg-gradient-to-r from-pink-500 to-purple-700 shadow">
    <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
        <a href="/" class="text-2xl font-extrabold text-white hover:text-yellow-200 transition-colors duration-200">
            🛍️ Laravel API
        </a>
        <nav class="flex space-x-3">
            <a href="/sales"
               class="text-white font-medium hover:bg-white hover:text-purple-700 px-4 py-2 rounded-full transition duration-200">
                Продажи
            </a>
            <a href="/orders"
               class="text-white font-medium hover:bg-white hover:text-purple-700 px-4 py-2 rounded-full transition duration-200">
                Заказы
            </a>
            <a href="/stocks"
               class="text-white font-medium hover:bg-white hover:text-purple-700 px-4 py-2 rounded-full transition duration-200">
                Остатки
            </a>
            <a href="/incomes"
               class="text-white font-medium hover:bg-white hover:text-purple-700 px-4 py-2 rounded-full transition duration-200">
                Доходы
            </a>
        </nav>
    </div>
</header>


    <main class="mt-8 mb-10">
        @yield('content')
    </main>
</body>
</html>
