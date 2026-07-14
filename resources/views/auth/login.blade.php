<x-layouts.app>
    <main class="flex min-h-screen items-center justify-center px-6 py-12">
        <section class="w-full max-w-md rounded-lg border border-zinc-200 bg-white p-8 shadow-sm">
            <p class="text-sm font-semibold uppercase tracking-wide text-emerald-700">Motor de Procesamiento</p>
            <h1 class="mt-3 text-3xl font-semibold text-zinc-950">Dashboard de Pedidos</h1>
            <p class="mt-3 text-sm leading-6 text-zinc-600">Acceso exclusivo mediante OAuth 2.0. No hay registro manual ni contrasenas locales.</p>

            <a href="{{ route('auth.github.redirect') }}" class="mt-8 inline-flex w-full items-center justify-center gap-2 rounded-md bg-zinc-950 px-4 py-3 text-sm font-semibold text-white transition hover:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:ring-offset-2">
                Continuar con GitHub
            </a>
        </section>
    </main>
</x-layouts.app>
