<tr>
    <td>{{ $product['id'] }}</td>
    <td>{{ $product['name'] }}</td>
    <td>{{ $product['value'] }}</td>
    <td>
        @can('user_admin')
            <a href="{{ route('product.update', ['data' => http_build_query($product)]) }}">UPDATE</a>
        @endcan

        @can('user_admin')
            <form action="{{ route('product.delete.submit') }}" method="POST">
                @csrf
                @method('DELETE')
                <input type="hidden" name="id" id="id" value="{{ $product['id'] }}">
                <button type="submit">DELETE</button>

            </form>
            
        @endcan
    </td>
</tr>