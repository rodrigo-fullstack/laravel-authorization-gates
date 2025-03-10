<form method="POST" class="bg-white p-6 rounded-lg shadow-md w-full max-w-md" action='{{ route('product.create.submit') }}'>
    @csrf
    <div class="mb-4">
        <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name</label>
        <input type="text" name="name" id="name" class="shadow-sm border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Enter name">
    </div>
    
    <div class="mb-6">
        <label for="value" class="block text-gray-700 text-sm font-bold mb-2">Value</label>
        <input type="text" name="value" id="value" class="shadow-sm border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Enter value">
    </div>
    
    <div class="flex items-center justify-center justify-end">
        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
            Submit
        </button>
    </div>
    
    
        @if($errors->any())
            <div class="block items-center justify-center bg-red-600 mt-3">
                @foreach($errors->all() as $error)
                    <div class="flex text-white">{{ $error }}</div>
                @endforeach
            </div>
        @endif
    
</form>