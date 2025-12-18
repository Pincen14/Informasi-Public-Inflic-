{{-- resources/views/shop.blade.php --}}
@extends('layouts.app')

@section('content')

<!-- Hero -->
<section class="relative">
    <img src="https://images.unsplash.com/photo-1505691938895-1758d7feb511" class="w-full h-[420px] object-cover" />
    <div class="absolute inset-0 bg-black/30 flex items-center">
        <h1 class="text-white text-6xl font-bold ml-20">Shop</h1>
    </div>
</section>

<!-- Main -->
<main class="max-w-7xl mx-auto px-6 py-12 grid grid-cols-1 md:grid-cols-4 gap-8">

    <!-- Sidebar -->
    <aside class="bg-white rounded-xl p-6 shadow">
        <h3 class="font-semibold mb-4">Category</h3>
        <ul class="space-y-2 text-sm">
            <li><input type="checkbox"> All Product</li>
            <li><input type="checkbox"> For Home</li>
            <li><input type="checkbox"> For Music</li>
            <li><input type="checkbox"> For Office</li>
        </ul>
    </aside>

    <!-- Product Grid -->
    <section class="md:col-span-3 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($products ?? range(1,6) as $product)
        <div class="bg-white rounded-2xl p-4 shadow hover:shadow-lg transition">
            <img src="https://via.placeholder.com/300" class="rounded-xl mb-4">
            <span class="text-xs bg-gray-100 px-2 py-1 rounded">Other</span>
            <h4 class="font-semibold mt-2">{{ $product['name'] ?? 'Product Name' }}</h4>
            <p class="text-sm text-gray-500">⭐⭐⭐⭐☆ (120 reviews)</p>
            <p class="font-bold mt-1">{{ $product['price'] ?? '$29.90' }}</p>
            <div class="flex gap-2 mt-4">
                <button class="flex-1 border rounded-full py-1 text-sm">Add to Cart</button>
                <button class="flex-1 bg-black text-white rounded-full py-1 text-sm">Buy Now</button>
            </div>
        </div>
        @endforeach
    </section>
</main>

<!-- Recommendation -->
<section class="max-w-7xl mx-auto px-6 py-12">
    <h2 class="text-2xl font-semibold mb-6">Explore our recommendations</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        @for($i = 0; $i < 3; $i++)
        <div class="bg-white rounded-2xl p-4 shadow">
            <img src="https://via.placeholder.com/300" class="rounded-xl mb-4">
            <h4 class="font-semibold">Product Name</h4>
            <p class="font-bold">$29.90</p>
            <button class="mt-3 w-full bg-black text-white rounded-full py-1 text-sm">Buy Now</button>
        </div>
        @endfor
    </div>
</section>

<!-- CTA -->
<section class="bg-gradient-to-r from-gray-900 to-gray-700 text-white py-16">
    <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-8">
        <div>
            <h2 class="text-3xl font-bold mb-2">Ready to Get Our New Stuff?</h2>
            <p class="text-gray-300">Subscribe to get updates and recommendations.</p>
        </div>
        <form class="flex items-center gap-2">
            <input type="email" placeholder="Your email" class="flex-1 px-4 py-2 rounded-full text-black">
            <button class="bg-white text-black px-6 py-2 rounded-full">Send</button>
        </form>
    </div>
</section>

@endsection
