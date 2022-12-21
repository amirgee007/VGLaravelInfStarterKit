@extends('layouts.app')

@section('page-title', trans('app.pdf_transfer'))
@section('page-heading', isset($user) ? $user->present()->nameOrEmail : trans('app.pdf_transfer'))

@section('breadcrumbs')
    @if (isset($user) && isset($adminView))
        <li class="breadcrumb-item">
            <a href="{{ route('product.index') }}">@lang('app.pdf_transfer')</a>
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

@section('style')
    #progress {
    height: 10px;
    width: 300px;
    border: 1px solid white;
    position: relative;
    border-radius: 5px;
    }

    #progress .progress-item {
    height: 100%;
    position: absolute;
    left: 0;
    top: 0;
    background: #2468a9;
    border-radius: 5px;
    transition: width .3s linear;
    }
@stop

@section('content')

    <div class="card">
    <div class="card-body">
        <form action="" method="GET" id="edit-pdf-form" class="border-bottom-light mb-3">
            <div class="row justify-content-between mt-3 mb-4">
                <div class="col-lg-5 col-md-6">
                    <div class="input-group custom-search-form">
                        <div style="display: flex;flex-wrap: wrap;">
                            {{--<label htmlFor="product_id">@lang('app.pdf_template')</label>--}}

                            <label htmlFor="product_id">@lang('app.product_id')</label>
                            <input type="text" id="product_id"
                                   class="form-control input-solid"
                                   name="product_id"
                                   value="{{ Input::get('product_id') }}"
                                   placeholder="@lang('app.11degits')">
                            <label htmlFor="quantity">@lang('app.quantity')</label>
                            <input type="text" id="quantity"
                                   class="form-control input-solid"
                                   name="quantity"
                                   value="{{ Input::get('quantity') }}"
                                   >

                            <label htmlFor="stock_location">@lang('app.stock_location')</label>
                            <input type="text" id="stock_location"
                                   class="form-control input-solid"
                                   name="stock_location"
                                   value="{{ Input::get('stock_location') }}"
                                   placeholder="@lang('app.8degitsandcharacters')">

                            <label htmlFor="ean_number">@lang('app.ean_number')</label>
                            <input type="text" id="ean_number"
                                   class="form-control input-solid"
                                   name="ean_number"
                                   value="{{ Input::get('ean_number') }}"
                                   placeholder="@lang('app.14degits')">

                            <span class="input-group-append" style="gap: 20px; margin-top: 10px;">
                                <a href="/downloadCurrentPdf" target="_blank">
                                <button class="btn btn-danger" type="button" id="download_current_pdf">
                                    <i class="fas text-muted"></i>
                                    Download Current PDF
                                </button>
                                </a>
                                <button class="btn btn-warning" type="button" id="edit_pdf">
                                    <i class="fas text-muted"></i>
                                    Edit PDF
                                </button>
                                <a href="/downloadEditedPdf" target="_blank">
                                <button class="btn btn-success" type="button" id="download_edited_pdf">
                                    <i class="fas text-muted"></i>
                                    Download Edited Pdf
                                </button>
                                </a>

                            </span>




                            <div id="progress" style=" margin-top:30px;height: 10px; width: 300px;border: 1px solid white; position: relative;border-radius: 5px;">
                                <div class="progress-item" id="datadd" style="height: 100%; position: absolute;left: 0;top: 0;background: #2468a9; border-radius: 5px;transition: width .3s linear;"></div>
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
    <?php echo JsValidator::formRequest('Vanguard\Http\Requests\Product\EditPdfRequest', '#edit-pdf-form'); ?>
    <script>
        $('#edit_pdf').click(function () {
            var progress = setInterval(function(){
                $("#progress .progress-item").css('width', "0%");
                $("#datadd").html("0%");
                $.getJSON('/progress', function(data) {
                    // $('#progress').html(data[0]);
                    $("#progress .progress-item").css('width', data[0] + "%");
                    $("#datadd").html(data[0] + "%");

                });
            }, 5);

            $.ajax({
                type: "POST",
                url: "/editPdf",
                data: $('#edit-pdf-form').serialize(),
                success: function (result) {
                    $("#progress .progress-item").css('width', "100%");
                    $("#datadd").html(100 + "%");
                    clearInterval(progress);
                    setTimeout(function(){ $('#progress').hide(); }, 2000);
                },
                complete:function () {
                    $("#progress .progress-item").css('width', "100%");
                    $("#datadd").html(100 + "%");
                    clearInterval(progress);
                    setTimeout(function(){ $('#progress').hide(); }, 2000);
                }
            });
        });


        

    </script>

@stop
