<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pedidos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="px-4 sm:px-6 lg:px-8">
                        <div class="-mx-4 mt-8 sm:-mx-0">

                            <div class="bg-white">
                                <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                                    <h2 class="text-2xl font-bold tracking-tight text-gray-900">
                                        Pedidos recientes ({{ $paginated->total() }})
                                    </h2>

                                    <div class="mt-6 overflow-hidden rounded-lg border">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Productos</th>
                                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200 bg-white">
                                                @forelse ($paginated as $o)
                                                    <tr class="hover:bg-gray-50">
                                                        <td class="px-4 py-3 text-sm text-gray-900">{{ $o->cliente }}</td>
                                                        <td class="px-4 py-3 text-sm text-gray-700">{{ $o->fecha }}</td>
                                                        <td class="px-4 py-3 text-sm text-gray-700">
                                                            @php
                                                                $nombres = collect($o->items)->map(fn($i) => $i->nombre . ' ×' . $i->cantidad)->implode(', ');
                                                            @endphp
                                                            {{ $nombres ?: '—' }}
                                                        </td>
                                                        <td class="px-4 py-3 text-sm text-gray-700">{{ $o->estado }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="px-4 py-6 text-center text-sm text-gray-500">No hay pedidos en los últimos 30 días.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="mt-6">
                                        {{ $paginated->appends(request()->except('page'))->links() }}
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
