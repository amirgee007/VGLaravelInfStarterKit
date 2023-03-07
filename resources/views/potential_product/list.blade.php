@extends('layouts.app')

@section('page-title', trans('app.products'))
@section('page-heading', trans('app.products'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('app.products')
    </li>
@stop

@section('content')

@include('partials.messages')

<div class="card">
    <div class="card-body">

        <form action="" method="GET" id="products-form" class="pb-2 mb-3 border-bottom-light">
            <div class="row my-3 flex-md-row flex-column-reverse">
                <div class="col-md-4 mt-md-0 mt-2">
                    <div class="input-group custom-search-form">
                        <input type="text"
                               class="form-control input-solid"
                               name="search"
                               value="{{ Input::get('search') }}"
                               placeholder="@lang('app.search_for_products')">

                            <span class="input-group-append">
                                @if (Input::has('search') && Input::get('search') != '')
                                    <a href="{{ route('product.list') }}"
                                           class="btn btn-light d-flex align-items-center text-muted"
                                           role="button">
                                        <i class="fas fa-times"></i>
                                    </a>
                                @endif
                                <button class="btn btn-light" type="submit" id="search-products-btn">
                                    <i class="fas fa-search text-muted"></i>
                                </button>
                            </span>
                    </div>
                </div>


                <div class="col-md-6">
                    <a style="color: #fff;" id="btn-fetch" data-href="{{ route('potential_product.collect') }}" class="btn btn-primary btn-rounded float-right">
                        @lang('app.fetch_protential_product')
                    </a>
                </div>
            </div>
        </form>

        <div class="table-responsive" id="products-table-wrapper">
            <table class="table table-borderless table-striped">
                <thead>
                <tr>
                    <th>@lang('app.product_id')</th>
                    <th class="min-width-80">@lang('app.product_thumbnail')</th>
                    <th>@lang('app.product_rank')</th>
                    <th>@lang('app.product_url')</th>
                    <th>@lang('app.product_original_title')</th>
                    <th>@lang('app.product_price')</th>
                    <th>@lang('app.product_category')</th>
                    <th>@lang('app.product_our_model')</th>
                    <th>@lang('app.product_created_at')</th>
                    <th>@lang('app.product_updated_at')</th>
                    <th class="min-width-100">@lang('app.product_action')</th>
                </tr>
                </thead>
                <tbody>
                    @if (count($products))
                        @foreach ($products as $product)
                            @include('potential_product.partials.row')
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7"><em>@lang('app.no_records_found')</em></td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

{!! $products->render() !!}

@stop

@section('scripts')
    <script>
        $("#status").change(function () {
            $("#products-form").submit();
        });
        // fetch potential products
        $('#btn-fetch').on('click', function () {
            var status = $(this).data('status');
            if (status == 'fetch') return false;

            $(this).html('fetching...');
            $(this).data('status', 'fetch');
            var url = $(this).data('href');
            Common.ajax({url: url}, function (data) {
                if (data.queue_process_id) {
                    fetchProcess(data.queue_process_id)
                } 
            })
        })
        // process of fetching
        function fetchProcess(queue_process_id) {
            Common.ajax({url: "{{ route('potential_product.process') }}"+"?id="+queue_process_id}, function (data) {
                if (data.status != 0) {
                    if (data.status == 1) {
                        alert("fetch result: updated "+data.affect+" row!");
                    } else {
                        alert(data.message);
                    }
                    $('#btn-fetch').html('Fetch protential product');
                    $('#btn-fetch').data('status', 'off');
                } else {
                    setTimeout(function () {
                        fetchProcess(queue_process_id);
                    }, 3000)
                }
            })
        }
        
    </script>
@stop
