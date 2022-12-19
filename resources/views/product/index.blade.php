@extends('layouts.app')

@section('page-title', trans('app.product_list'))
@section('page-heading', isset($user) ? $user->present()->nameOrEmail : trans('app.product_list'))

@section('breadcrumbs')
    @if (isset($user) && isset($adminView))
        <li class="breadcrumb-item">
            <a href="{{ route('product.index') }}">@lang('app.product_list')</a>
        </li>
        <li class="breadcrumb-item active">
            {{ $user->present()->nameOrEmail }}
        </li>
    @else
        <li class="breadcrumb-item active">
            {{--@lang('app.product_list')--}}
        </li>
    @endif
@stop

@section('content')

    <div class="card">
    <div class="card-body">
        <form action="" method="GET" id="product-form" class="border-bottom-light mb-3">
            <div class="row justify-content-between mt-3 mb-4">
                <div class="col-lg-5 col-md-6">
                    <div class="input-group custom-search-form">
                        <input type="text"
                               class="form-control input-solid"
                               name="search"
                               value="{{ Input::get('search') }}"
                               placeholder="@lang('app.search_for_product')">

                        <span class="input-group-append">
                            @if (Input::has('search') && Input::get('search') != '')
                                <a href="{{ isset($adminView) ? route('product.index') : route('profile.activity') }}"
                                   class="btn btn-light d-flex align-items-center"
                                   role="button">
                                    <i class="fas fa-times text-muted"></i>
                                </a>
                            @endif
                            <button class="btn btn-light" type="submit" id="search-activities-btn">
                                <i class="fas fa-search text-muted"></i>
                            </button>

                        </span>
                    </div>
                </div>
                <div class="col-md-6">
                    <a href="/product" class="btn btn-primary btn-rounded float-right refresh" style="margin-right: 5px;">
                        <i class="fas fa-recycle mr-2"></i>
                        @lang('app.refresh')
                    </a>

                    <a href="/downloadExcel" class="btn btn-primary btn-rounded float-right downloadExcel">
                        <i class="fas fa-download mr-2" style="margin-right: 5px;"></i>
                        @lang('app.download')
                    </a>
                </div>



            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-borderless table-striped">
                <thead>

                    <th class="min-width-30">@lang('app.id')</th>
                    <th class="min-width-30">@lang('app.rank')</th>
                    <th class="min-width-30">@lang('app.image')</th>
                    <th class="min-width-30">@lang('app.url')</th>
                    <th class="min-width-30">@lang('app.original_title')</th>
                    <th class="max-width-30" style="max-width: 100px;">@lang('app.thumbnail')</th>
                    <th class="min-width-30">@lang('app.original_description')</th>
                    <th class="min-width-30">@lang('app.price')</th>
                    <th class="min-width-30">@lang('app.english_title')</th>
                    <th class="min-width-30">@lang('app.english_description')</th>
                    <th class="min-width-30">@lang('app.chinese_title')</th>
                    <th class="min-width-30">@lang('app.chinese_description')</th>
                    <th class="min-width-30">@lang('app.image')</th>
                    <th class="min-width-30">@lang('app.action')</th>


                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>

                            <td>{{ $product->id }}</td>
                            <td>{{ $product->rank }}</td>
                            <td><image src={{$product->thumbnail}} width="50px" /></td>
                            <td><a href={{$domain.$product->url}} target="_blank">
                                    <button class="btn btn-primary float-right" type="button" id="view-product-btn">
                                        <i class="fas mr-0"></i>
                                        View link
                                    </button>
                                </a>
                            </td>
                            <td>{{ $product->original_title }}</td>
                            <td>{{ $product->thumbnail }}</td>
                            <td>{{ str_limit($product->original_description, 100, '...') }}</td>
                            <td>{{ $product->price }}</td>
                            <td>{{ $product->english_title }}</td>
                            <td>{{ $product->english_description }}</td>
                            <td>{{ $product->chinese_title }}</td>
                            <td>{{ $product->chinese_description }}</td>
                            <td>{{ $product->image }}</td>
                            <th>
                                @if ($product->image)
                                    <a href={{$domain.$product->url}} target="_blank">
                                        <button class="btn btn-light" type="button" id="view-product-btn">
                                            <i class="fas fa-link text-muted"></i>
                                        </button>
                                    </a>
                                <a href="imageDownload?url={{$product->image}}" target="_blank">
                                    <button class="btn btn-light" type="button" id="view-product-btn">
                                        <i class="fas fa-download text-muted"></i>
                                    </button>
                                </a>
                                    @endif
                            </th>


                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{!! $products->render() !!}
@stop

@section('scripts')
    <script>

    </script>
@stop
