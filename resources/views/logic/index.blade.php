@extends('layouts.app')

@section('page-title', trans('app.product_list'))
@section('page-heading', isset($user) ? $user->present()->nameOrEmail : trans('app.logic_test'))

@section('breadcrumbs')
    @if (isset($user) && isset($adminView))
        <li class="breadcrumb-item">
            <a href="{{ route('logic.index') }}">@lang('app.logic_test')</a>
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
            <form action="" method="GET" id="edit-pdf-form" class="border-bottom-light mb-3">
                <div class="row justify-content-between mt-3 mb-4">
                    <div class="col-lg-5 col-md-6">
                        <div class="input-group custom-search-form">

                            <div style="display: flex;flex-wrap: wrap;flex-direction: column;">

                                <div>
                                    <label htmlFor="product_id">@lang('app.warehouse_location')</label>
                                    <input type="text" id="warehouse_location"
                                           class="form-control input-solid"
                                           name="warehouse_location"
                                           value="{{ Input::get('warehouse_location') }}"
                                          >

                                    <button id="test1" type="button" class="btn btn-primary btn-rounded float-right" style="margin-right: 5px;">
                                        <i class="fas mr-6"></i>
                                        @lang('app.check')
                                    </button>

                                    <div id="result"></div>
                                </div>

                                <div>
                                    <label htmlFor="quantity">@lang('app.match')</label>
                                    <input type="text" id="matchStr" class="form-control input-solid"
                                    name="matchStr"
                                    value="{{ Input::get('matchStr') }}"
                                    >

                                    <button id="test2" type="button" class="btn btn-primary btn-rounded float-right" style="margin-right: 5px;">
                                        <i class="fas mr-6"></i>
                                        @lang('app.check')
                                    </button>

                                    <div id="result_match"></div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </form>


        </div>
    </div>


@stop

@section('scripts')
    <script>
        $('#test1').click(function () {
            $.ajax({
                    type: "GET",
                    url: "checkMiss",
                    data:{checkNumber:$('#warehouse_location').val()},
                    success: function (result) {
                        $('#result').html(result);
                    }

                }

            )
        });

        $('#test2').click(function () {
            $.ajax({
                    type: "GET",
                    url: "checkMatch",
                    data:{checkMatch:$('#matchStr').val()},
                    success: function (result) {
                        $('#result_match').html(result);
                    }

                }

            )
        });
    </script>
@stop
