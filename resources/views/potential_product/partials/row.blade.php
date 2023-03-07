<tr>
    <td>
        <a href="{{ route('potential_product.show', $product->id) }}">{{$product->id}}
        </a>
    </td>
    <td style="width: 40px;">
            <img
                class=" img-responsive"
                width="40"
                src="{{ $product->thumbnail }}"
                alt="{{ $product->original_title }}">
    </td>

    <td class="align-middle">{{ $product->rank }}</td>
    <td class="align-middle"><a target="_blank" href="{{ $product->url }}">View Link</a></td>
    <td class="align-middle">{{ $product->original_title }}</td>
    <td class="align-middle">{{ $product->price }}</td>
    <td class="align-middle"></td>
    <td class="align-middle"></td>
    <td class="align-middle">{{ $product->created_at->format(config('app.date_format')) }}</td>
    <td class="align-middle">{{ $product->updated_at->format(config('app.date_format')) }}</td>

    <td class="text-center align-middle">


    </td>
</tr>