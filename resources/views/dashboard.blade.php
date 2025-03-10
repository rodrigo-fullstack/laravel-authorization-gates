<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>

    </x-slot>

    <div class="py-12">
        @if(session()->get('success'))
            <div class="bg-green-600">
                {{ session()->get('success') }}
            </div>
        @endif

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <table>
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Value</th>
                            <th>Operations</th>
                        </tr>
                        
                        @foreach ($products as $product)
                            <x-product-row :$product />
                        @endforeach
                    </table>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
