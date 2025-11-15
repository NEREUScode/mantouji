<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toutes les Coopératives</title>
    {{-- Assuming you are using Laravel Mix/Vite for assets --}}
    @vite('resources/css/app.css')
    <style>
        .absolute{
            position:unset;
            background:currentColor;
            border-radius:8px;
        }
    </style>
</head>
<body class="bg-gray-50">

<div class="container mx-auto px-4 py-6">

    <div class="relative flex justify-center items-center mb-8">
        <img src="{{ asset('images/mantouj-removebg-preview.png') }}" 
              alt="Logo Mantouj" 
              class="h-16 object-contain">

        <form method="POST" action="{{ route('logout') }}" class="absolute right-0">
            @csrf
            <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded-lg transition-colors shadow-md">
                Déconnexion
            </button>
        </form>
    </div>

    <h1 class="text-3xl font-bold mb-6 text-center">
        Bienvenue, <span class="text-orange-600">{{ auth()->user()->name }}</span>!
    </h1>

    <div class="mb-6 flex justify-center">
        <input type="text" id="coopSearch" placeholder="Rechercher des coopératives..." 
               class="w-full sm:w-1/2 p-2 border rounded-lg focus:outline-none focus:ring focus:ring-orange-300 transition-all duration-300 shadow-sm">
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" id="coopsContainer">
        @forelse ($coops as $coop)
        {{-- S'assure que toutes les cartes ont la même hauteur --}}
        <a href="{{ route('coops.show', $coop->id) }}" class="transform hover:scale-105 transition-transform h-full">
            <div class="p-1 shadow-md hover:shadow-xl transition-shadow duration-300 rounded-lg h-full bg-white">
                <div class="bg-white rounded-lg overflow-hidden flex flex-col items-center p-4 h-full">
                    <img src="{{ $coop->user?->image ? asset('images/' . $coop->user?->image) : asset('images/default-coop.jpg') }}" 
                          alt="{{ $coop->user?->name ?? 'Coopérative Inconnue' }}" 
                          class="w-20 h-20 rounded-full object-cover border-2 border-orange-600 mb-4 flex-shrink-0">

                    <div class="text-center w-full">
                        <h2 class="text-lg font-semibold text-gray-800">
                            {{ $coop->user?->name ?? 'Coopérative Inconnue' }}
                        </h2>
                        
                        @if($coop->contact)
                            <p class="text-gray-600 text-sm mt-1">Contact: {{ $coop->contact }}</p>
                        @else
                            <p class="text-gray-600 text-sm mt-1 invisible">Contact: placeholder</p>
                        @endif
                        
                        <p class="text-gray-600 text-sm mt-1">Produits: {{ $coop->products_count ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </a>
        @empty
        <p class="text-center text-gray-500 col-span-3">Aucune coopérative trouvée. Soyez le premier à en ajouter une !</p>
        @endforelse
    </div>

</div>

<script>
    // Le script JavaScript n'a pas besoin d'être traduit
    const searchInput = document.getElementById('coopSearch');
    const coopsContainer = document.getElementById('coopsContainer');

    searchInput.addEventListener('input', () => {
        const filter = searchInput.value.toLowerCase();
        coopsContainer.querySelectorAll('a').forEach(card => {
            const name = card.querySelector('h2').textContent.toLowerCase();
            card.style.display = name.includes(filter) ? '' : 'none';
        });
    });
</script>

</body>
</html>