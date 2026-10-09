<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title . ' | ZYRICZ Atelier' : 'ZYRICZ | Precision Living, Horology & Acoustics' }}</title>
    <meta name="description" content="{{ $metaDescription ?? 'Discover luxury horology, precision acoustics, and handcrafted Italian leather goods engineered for discerning modern lifestyles.' }}">

    <!-- Open Graph / SEO -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ isset($title) ? $title . ' | ZYRICZ' : 'ZYRICZ Atelier' }}">
    <meta property="og:description" content="{{ $metaDescription ?? 'Luxury horology, reference acoustics, and handcrafted carry essentials.' }}">
    <meta property="og:image" content="{{ $ogImage ?? 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=1200&auto=format&fit=crop&q=80' }}">
    <meta name="twitter:card" content="summary_large_image">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Structured Data (JSON-LD) -->
    <script type="application/ld+json">
    {
        "{{ '@context' }}": "https://schema.org",
        "@type": "OnlineStore",
        "name": "ZYRICZ Atelier",
        "url": "{{ url('/') }}",
        "description": "Luxury horology, reference acoustics, and handcrafted leather carry.",
        "currenciesAccepted": "USD",
        "paymentAccepted": "Credit Card, PayPal, Cash On Delivery"
    }
    </script>
</head>
<body class="min-h-screen bg-zinc-950 text-zinc-100 font-sans antialiased flex flex-col selection:bg-amber-500 selection:text-zinc-950"
      x-data>

    <!-- Header Navigation -->
    <x-header />

    <!-- Main View Content -->
    <main class="flex-1">
        {{ $slot }}
    </main>

    <!-- Slide-over Cart Drawer -->
    <x-cart-drawer />

    <!-- Toast Notifications -->
    <x-toast />

    <!-- Global Footer -->
    <x-footer />

    <!-- Global Flash Banner to Alpine Toast -->
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                Alpine.store('toast').add(@json(session('success')), 'success');
            });
        </script>
    @endif
    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                Alpine.store('toast').add(@json(session('error')), 'error');
            });
        </script>
    @endif
    @if(session('info'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                Alpine.store('toast').add(@json(session('info')), 'info');
            });
        </script>
    @endif
</body>
</html>
