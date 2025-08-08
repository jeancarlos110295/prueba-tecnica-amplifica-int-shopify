<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Productos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="px-4 sm:px-6 lg:px-8">
                        <div class="-mx-4 mt-8 sm:-mx-0">

                            <div class="bg-white">
                                <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
                                    <h2 class="text-2xl font-bold tracking-tight text-gray-900">Productos ({{ $paginated->total() }})</h2>

                                    <a href="{{ route('export.productos.xlsx') }}" class="btn ml-2">Descargar Excel</a>
                                    
                                    <hr>
                                    
                                    <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
                                        @forelse ($paginated as $p)
                                            <div class="group relative">        
                                                @if(!empty($p->img))
                                                    <img src="{{ $p->img }}" alt="Imagen de {{ $p->nombre }}" class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 lg:aspect-auto lg:h-80" loading="lazy"/>
                                                @else
                                                    <div class="h-12 w-12 rounded bg-gray-100 grid place-items-center text-gray-400 text-xs border">
                                                        N/A
                                                    </div>
                                                @endif
                                                
                                                <div class="mt-4 flex justify-between">
                                                    <div>
                                                        <h3 class="text-sm text-gray-700">
                                                            <a href="#">
                                                                <span aria-hidden="true" class="absolute inset-0"></span>
                                                                {{ $p->nombre }}
                                                            </a>
                                                        </h3>
                                                        <p class="mt-1 text-sm text-gray-500">
                                                            {{ $p->sku ?: '—' }}
                                                        </p>

                                                        <p class="mt-1 text-sm text-gray-500">
                                                            {{ $p->id ?: '—' }}
                                                        </p>
                                                    </div>
                                                    
                                                    <p class="text-sm font-medium text-gray-900">
                                                        @php
                                                            $price = $p->precio !== null ? (float) $p->precio : null;
                                                        @endphp
                                                        {{ $price !== null ? '$' . number_format($price, 0, ',', '.') : '—' }}
                                                    </p>
                                                </div>
                                            </div>
                                        @empty
                                            No hay productos para mostrar.
                                        @endforelse
                                    </div>
                                </div>
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
</x-app-layout>
