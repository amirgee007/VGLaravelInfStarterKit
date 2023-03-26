@extends('layouts.app')

@section('page-title', 'Potential Products')
@section('page-heading', isset($user) ? $user->present()->nameOrEmail : trans('app.activity_log'))

@section('breadcrumbs')
    @if (isset($user) && isset($adminView))
        <li class="breadcrumb-item">
            <a href="{{ route('activity.index') }}">@lang('app.activity_log')</a>
        </li>
        <li class="breadcrumb-item active">
            {{ $user->present()->nameOrEmail }}
        </li>
    @else
        <li class="breadcrumb-item active">
            @lang('app.activity_log')
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
                                   id="search"
                                   value="{{ Input::get('search') }}"
                                   placeholder="Search by id,url,title">

                            <span class="input-group-append">
                            @if (Input::has('search') && Input::get('search') != '')
                                    <a href="{{ route('potential-products.index') }}"
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
                    <div>
                        <a href="{{ route('potential-products.excel', array('search' => Input::get('search'))) }}"
                           class="btn btn-icon"
                           title="Down Load Excel"
                           data-toggle="tooltip" data-placement="top" >
                            <i class="fas fa-download fa-2x"></i>
                        </a>
                        <a href="#" class="btn btn-primary fresh-crawler">Fetch Potential products</a>
                    </div>

                </div>
            </form>
            <div class="alert alert-primary" role="alert" id="reminder" style="display: none">
                The queue has been submitted, please check the list later
            </div>
            <div class="table-responsive">
                {!! $products->render() !!}
                <table class="table table-borderless table-striped">
                    <thead>
                    <th>ID</th>
                    <th class="min-width-200">Thumbnail</th>
                    <th class="min-width-200">Rank</th>
                    <th class="min-width-200">Url</th>
                    <th class="min-width-200">Original Title</th>
                    <th class="min-width-200">Price</th>
                    <th class="min-width-200">Created At</th>
                    <th class="min-width-200">Action</th>
                    </thead>
                    <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td style="width: 40px;">
                                <a href="#" class="thumbnail" data-toggle="modal" data-target="#imgModal">
                                    <input class="hid-img" type="hidden" value="{{ $product->image }}">
                                    <img
                                            class="rounded-circle img-responsive"
                                            width="40"
                                            src="{{ $product->thumbnail }}" />
                                </a>
                            </td>
                            <td>{{ $product->rank }}</td>
                            <td>
                                <button class="btn btn-primary link" data-link="{{ $product->url }}">
                                    View Link
                                </button>
                            </td>
                            <td>{{ $product->original_title }}</td>
                            <td>{{ $product->price }}</td>
                            <td>{{ $product->created_at }}</td>
                            <td class="min-width-200">
                                <a href="{{ $product->url }}" target="_blank"
                                   class="btn btn-icon"
                                   title="View Link"
                                   data-toggle="tooltip" data-placement="top">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ $product->thumbnail }}" target="_blank"
                                   class="btn btn-icon"
                                   title="Down Load Image"
                                   data-toggle="tooltip" data-placement="top">
                                    <i class="fas fa-download"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="imgModal" tabindex="-1" aria-labelledby="imgModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="background-color:Transparent;border:0">
                <div class="modal-body" id="z-index">
                    <img src="" alt="">
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script type="text/javascript" src="{{ url('assets/js/as/potential-products.js') }}"></script>
    <script>
        $('.fresh-crawler').click(function(){
            var uid = {!! $uid !!};
            $.post("/api/crawler",{uid:uid},function(response){
                if(response.code === 200){
                    $('#reminder').show();
                    setTimeout(function(){
                        $('#reminder').hide();
                    },3000)
                }
            })
        })
    </script>
@stop