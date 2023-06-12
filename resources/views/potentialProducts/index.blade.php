@extends('layouts.app')

@section('page-title', trans('app.potential_products'))
@section('page-heading', trans('app.potential_products'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('app.potential_products')
    </li>
@stop

@section('content')

    @include('partials.messages')

    <div class="card">
        <div class="card-body">
            <div class="col-md-12">
                <button onclick="syncOperation()" class="btn btn-primary float-right" id="sync-button">
                    <i class="fas fa-download "></i> @lang('app.sync_products')
                </button>
                <form action="" method="GET"  class="pb-2 mb-3 border-bottom-light">
                    <div class="row my-3 flex-md-row flex-column-reverse">
                        <div class="col-md-4 mt-md-0 mt-2">
                            <div class="input-group custom-search-form">
                                <input type="text"
                                       class="form-control input-solid"
                                       name="search"
                                       value="{{ Input::get('search') }}"
                                       placeholder="@lang('app.search_for_products')">
                                <span class="input-group-append">
                                    <button class="btn btn-light" type="submit" >
                                        <i class="fas fa-search text-muted"></i>
                                    </button>
                            </span>

                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="table-responsive" id="users-table-wrapper">
                <table class="table table-borderless table-striped">
                    <thead>
                    <tr>
                        <th>id</th>
                        <th class="min-width-30">@lang('app.pd_rank')</th>
                        <th class="min-width-50">@lang('app.pd_thumbnail')</th>
                        <th class="min-width-100">@lang('app.pd_url')</th>
                        <th class="min-width-100">@lang('app.pd_original_title')</th>
                        <th class="min-width-100">@lang('app.pd_original_description')</th>
                        <th class="text-center min-width-80">@lang('app.pd_price')</th>
                        <th class="text-center min-width-100">@lang('app.pd_english_title')</th>
                        <th class="text-center min-width-100">@lang('app.pd_english_description')</th>
                        <th class="text-center min-width-150">@lang('app.action')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if (count($potentialProducts))
                        @foreach ($potentialProducts as $value)
                            @include('potentialProducts.partials.row')
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
{!! $potentialProducts->render() !!}
@stop

<script>
    function syncOperation(){
        $("#sync-button").html("@lang('app.loading')").attr("disabled","true");
        $.ajax({
            url:"/api/potential_products_sync",
            data: {},
            type: "POST",
            dataType: "json",
            contentType:"application/json;charset=utf-8",
            success: function (res) {
                alert(res);
                $("#sync-button").html(" <i class='fas fa-download'></i> @lang('app.sync_products')").attr("disabled",false);
                location.reload();
            },
            error:function( err ){
                console.log( JSON.stringify( err ) );
            }
        })
    }
</script>




