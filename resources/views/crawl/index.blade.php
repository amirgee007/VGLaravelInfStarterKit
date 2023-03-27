@extends('layouts.app')

@section('page-title', trans('app.crawl_products'))
@section('page-heading', isset($user) ? $user->present()->nameOrEmail : trans('app.crawl_products'))

@section('breadcrumbs')
    @if (isset($user) && isset($adminView))
        <li class="breadcrumb-item">
            <a href="{{ route('crawl.index') }}">@lang('app.crawl_products')</a>
        </li>
        <li class="breadcrumb-item active">
            {{ $user->present()->nameOrEmail }}
        </li>
    @else
        <li class="breadcrumb-item active">
            @lang('app.crawl_products')
        </li>
    @endif
@stop

@section('content')

    <div class="card">
    <div class="card-body">
        <form action="" method="GET" id="users-form" class="border-bottom-light mb-3">
            <div class="row justify-content-between mt-3 mb-4">
                <div class="col-lg-5 col-md-6">
                    <div class="input-group custom-search-form">
                        <input type="text"
                               class="form-control input-solid"
                               name="search"
                               value="{{ Input::get('search') }}"
                               placeholder="@lang('app.search_for_crawl')">

                        <span class="input-group-append">
                            @if (Input::has('search') && Input::get('search') != '')
                                <a href="{{ route('crawl.index') }}"
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
                <div class="float-right col-lg-6 col-md-6 form-control input-solid">
                    <a target="_blank" href="{{ route('crawl.download.products', ['search' => Input::get('search')]) }}">
                        <i class="fas fa-download mr-2 text-muted"></i>
                    </a>
                    <a target="_blank" href="{{ route('crawl.scrape') }}">
                        <i class="fas fa-download mr-2"></i>
                    </a>
                </div>
            </div>
        </form>


        <div class="float-right">{!! $products->render() !!}</div>

        <div class="table-responsive">
            <table class="table table-borderless table-striped">
                <thead>
                    <th>@lang('app.id')</th>
                    <th class="min-width-50">@lang('app.thumbnail')</th>
                    <th class="min-width-50">@lang('app.rank')</th>
                    <th class="min-width-150">@lang('app.url')</th>
                    <th class="min-width-150 text-center">@lang('app.original_title')</th>
                    <th class="min-width-80">@lang('app.price')</th>
                    <th>@lang('app.created_at')</th>
                    <th>@lang('app.updated_at')</th>
                    <th class="text-center min-width-150">@lang('app.action')</th>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td><a target="_blank" href="{{ $product->thumbnail }}"><img src="{{ $product->thumbnail }}" class="img-thumbnail img-responsive" style="width: 50px;" /></a></td>
                            <td>{{ $product->rank }}</td>
                            <td>
                                <a target="_blank" href="{{ $product->url }}">
                                    <span class="badge badge-lg badge-success">
                                        {{ trans("app.view_link") }}
                                    </span>
                                </a>
                            </td>
                            <td>{{ $product->original_title }}</td>
                            <td>{{ $product->price }}</td>
                            <td>{{ $product->created_at->format(config('app.date_time_format')) }}</td>
                            <td>{{ $product->updated_at->format(config('app.date_time_format')) }}</td>
                            <td class="text-center align-middle">
                                    <a target="_blank" href="{{ $product->url }}" class="text-gray-500">
                                        <i class="fas fa-eye mr-2"></i>
                                    </a>
                                    <a target="_blank" href="{{ route('crawl.download.images', ['product_id' => $product->id]) }}" class="text-gray-500">
                                        <i class="fas fa-download mr-2"></i>
                                    </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@stop