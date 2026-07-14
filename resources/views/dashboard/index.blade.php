<x-layouts.app>
    <header class="border-b border-zinc-200 bg-white">
        <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wide text-emerald-700">Logistica</p>
                <h1 class="text-2xl font-semibold text-zinc-950">Dashboard de Pedidos</h1>
            </div>
            <div class="flex items-center gap-4">
                @if (auth()->user()?->avatar)
                    <img src="{{ auth()->user()->avatar }}" alt="" class="h-10 w-10 rounded-full">
                @endif
                <span class="text-sm font-medium text-zinc-700">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="rounded-md border border-zinc-300 px-3 py-2 text-sm font-semibold text-zinc-800 hover:bg-zinc-100 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:ring-offset-2">
                        Cerrar sesion
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl space-y-8 px-4 py-8 sm:px-6 lg:px-8">
        @foreach ($categorias as $key => $categoria)
            <section class="space-y-4" aria-labelledby="section-{{ $key }}">
                <div class="flex items-end justify-between gap-4">
                    <h2 id="section-{{ $key }}" class="text-xl font-semibold text-zinc-950">{{ $categoria['titulo'] }}</h2>
                    <span class="text-sm text-zinc-500">{{ $pedidos[$key]->total() }} pedidos</span>
                </div>

                <div class="overflow-hidden rounded-lg border border-zinc-200 bg-white">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-zinc-200 text-sm">
                            <thead class="bg-zinc-100 text-left text-xs font-semibold uppercase tracking-wide text-zinc-600">
                                <tr>
                                    <th class="px-4 py-3">ID</th>
                                    <th class="px-4 py-3">Cliente</th>
                                    <th class="px-4 py-3">Total</th>
                                    <th class="px-4 py-3">Entrega</th>
                                    <th class="px-4 py-3">Estado</th>
                                    <th class="px-4 py-3">Productos</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-100">
                                @forelse ($pedidos[$key] as $pedido)
                                    <tr>
                                        <td class="whitespace-nowrap px-4 py-3 font-medium text-zinc-950">#{{ $pedido->id }}</td>
                                        <td class="px-4 py-3">
                                            <div class="font-medium text-zinc-900">{{ $pedido->cliente->nombre }}</div>
                                            <div class="text-xs text-zinc-500">{{ $pedido->cliente->email }}</div>
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-3 font-semibold">${{ number_format((float) $pedido->total, 2) }}</td>
                                        <td class="whitespace-nowrap px-4 py-3">{{ $pedido->fecha_entrega->format('Y-m-d') }}</td>
                                        <td class="whitespace-nowrap px-4 py-3">
                                            @php($estado = $pedido->estado->value)
                                            <span @class([
                                                'rounded-full px-2.5 py-1 text-xs font-semibold',
                                                'bg-amber-100 text-amber-800' => $estado === 'pendiente',
                                                'bg-emerald-100 text-emerald-800' => $estado === 'entregado',
                                                'bg-rose-100 text-rose-800' => $estado === 'cancelado',
                                            ])>{{ ucfirst($estado) }}</span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex flex-wrap gap-1.5">
                                                @foreach ($pedido->productos as $producto)
                                                    <span class="rounded bg-zinc-100 px-2 py-1 text-xs text-zinc-700">{{ $producto->nombre }}</span>
                                                @endforeach
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-8 text-center text-zinc-500">No hay pedidos en esta categoria.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div>
                    {{ $pedidos[$key]->links() }}
                </div>
            </section>
        @endforeach
    </main>
</x-layouts.app>
