<!DOCTYPE html>
<html lang="hr">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-snow text-slate-text antialiased font-sans">
        <x-public-navigation />

        <main>
            {{ $slot }}
        </main>

        <x-public-footer />

        @fluxScripts
    </body>
</html>
