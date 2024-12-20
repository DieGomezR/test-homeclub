<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Gestión de Incidencias')</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-gray-50">
    <nav class="bg-white text-black p-4 border-b border-blue-gray-200">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-2xl  text-black font-bold">HOMECLUB</h1>
            <ul class="flex space-x-4">
                <li><a href="{{ route('properties.index') }}" class="hover:underline">Propiedades</a></li>
                <li><a href="{{ route('bookings.index') }}" class="hover:underline">Reservas</a></li>
                <li><a href="{{ route('incidences.index') }}" class="hover:underline">Incidentes</a></li>
            </ul>
        </div>
    </nav>
    <main class="container mx-auto p-6">
        @yield('content')
    </main>

</body>

</html>
