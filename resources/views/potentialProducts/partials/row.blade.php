<tr>
    <td></td>
    <td style="width: 40px;">
        {{ $value->rank}}

    </td>
    <td class="align-middle">
        <a href="{{ route('user.show', $value->id) }}">
            <img
                    class="rounded-circle img-responsive"
                    width="40"
                    src="{{ $value->thumbnail }}"
                    >
        </a>
    </td>
    <td class="align-middle"><a class="btn btn-primary" href="{{ $value->url }}" target="_blank">@lang('app.view_link')</a></td>
    <td class="align-middle box"  data-title="{{ $value->original_title }}">{{ \Illuminate\Support\Str::limit( $value->original_title , 40, $end='...') }}</td>
    <td class="align-middle box" data-title="{{ $value->original_description }}">{{ \Illuminate\Support\Str::limit( $value->original_description , 140, $end='...') }}</td>
    <td class="align-middle">{{ $value->price }}</td>
    <td class="align-middle box" data-title="{{ $value->english_title }}">{{ \Illuminate\Support\Str::limit( $value->english_title , 40, $end='...') }}</td>
    <td class="align-middle box" data-title="{{ $value->english_description }}">{{ \Illuminate\Support\Str::limit( $value->english_description , 140, $end='...') }}</td>

    <td class="text-center align-middle">
        <a tabindex="0" role="button" class="btn btn-icon"
           href ="{{ $value->url }}"
           target="_blank">
            <i class="fas fa-eye mr-2"></i>
        </a>
        <button tabindex="0" role="button" class="btn btn-icon download" onclick="display('{{ $value->thumbnail }}')"><i class="fas fa-download "></i></button>
    </td>
</tr>

<script>
    function display(url) {
        window.open(url, "_blank");
    }
</script>
<style>
    .box{
        position: relative;
    }
    .box:hover:after {
        position:absolute;
        top:20px;
        left:1px;
        content:attr(data-title);
        color: #f9f9f9;
        border:5px solid #000000;
        border-radius:5px ;
        background-color:#000000;
    }
</style>
