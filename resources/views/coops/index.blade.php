<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>All Coops</title>
@vite('resources/css/app.css')
</head>
<body class="bg-gray-100">

<div class="container mx-auto px-4 py-8">

    <!-- Top bar -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">
            Welcome, <span class="text-indigo-600">{{ auth()->user()->name }}</span>!
        </h1>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                Logout
            </button>
        </form>
    </div>

    <!-- Search bar -->
    <div class="mb-6">
        <input type="text" id="coopSearch" placeholder="Search coops..." 
               class="w-full max-w-sm p-2 border rounded-lg focus:outline-none focus:ring focus:ring-indigo-300 transition-all duration-300">
    </div>

    <!-- Coops list -->
    <div class="space-y-4" id="coopsContainer">
        @forelse ($coops as $coop)
        <a href="{{ route('coops.show', $coop->id) }}" class="block transform hover:scale-105 transition-transform">
            <div class="bg-white rounded-lg shadow-md p-4 flex items-center hover:shadow-xl transition-shadow duration-300">

                <!-- Coop Logo -->
                <img src="{{ optional($coop->user)->image 
                    ? asset('images/' . $coop->user->image) 
                    : asset('images/default-coop.jpg') }}" 
                    alt="{{ optional($coop->user)->name ?? 'Default Coop' }}" 
                    class="w-16 h-16 rounded-full object-cover border-2 border-indigo-500 mr-4">


                <!-- Coop Info -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">{{ $coop->user->name }}</h2>
                    @if($coop->contact)
                        <p class="text-gray-600 text-sm">Contact: {{ $coop->contact }}</p>
                    @endif
                    <p class="text-gray-600 text-sm">Products: {{ $coop->products_count ?? 0 }}</p>
                </div>

            </div>
        </a>
        @empty
        <p class="text-center text-gray-500">No coops found. Be the first to add one!</p>
        @endforelse
    </div>

</div>

<script>
    // Smooth search filter
    const searchInput = document.getElementById('coopSearch');
    const coopsContainer = document.getElementById('coopsContainer');

    searchInput.addEventListener('input', () => {
        const filter = searchInput.value.toLowerCase();
        coopsContainer.querySelectorAll('a').forEach(card => {
            const name = card.querySelector('h2').textContent.toLowerCase();
            card.style.display = name.includes(filter) ? 'flex' : 'none';
        });
    });
</script>

</body>
</html>
