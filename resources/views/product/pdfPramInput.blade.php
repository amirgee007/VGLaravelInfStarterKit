@extends('layouts.app')
@section('styles')
    <style>
    #button{
    display:block;
    margin:20px auto;
    padding:10px 30px;
    background-color:#eee;
    border:solid #ccc 1px;
    cursor: pointer;
    }
    #overlay{
    position: fixed;
    top: 0;
    z-index: 100;
    width: 100%;
    height:100%;
    display: none;
    background: rgba(0,0,0,0.6);
    }
    .cv-spinner {
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    }
    .spinner {
    width: 40px;
    height: 40px;
    border: 4px #ddd solid;
    border-top: 4px #2e93e6 solid;
    border-radius: 50%;
    animation: sp-anime 0.8s infinite linear;
    }
    @keyframes sp-anime {
    100% {
    transform: rotate(360deg);
    }
    }
    .is-hide{
    display:none;
    }
    </style>
@stop
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

@section('content')

    <div class="card">
    <div class="card-body">
        <form action="" method="POST" id="edit-pdf-form" class="border-bottom-light mb-3">
            <div class="row justify-content-between mt-3 mb-4">
                <div class="col-lg-5 col-md-6">
                    <div class="input-group custom-search-form">
                        <div style="color:orange;display: block">***The pdf provided is compressed, so replaced by a decompressed one.</div>

                        <div style="display: flex;flex-wrap: wrap;">

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
                                <a href="pdf/downloadCurrentPdf" target="_blank">
                                <button class="btn btn-danger" type="button" id="download_current_pdf">
                                    <i class="fas text-muted"></i>
                                    Download Current PDF
                                </button>
                                </a>
                                <button class="btn btn-warning" type="button" id="edit_pdf">
                                    <i class="fas text-muted"></i>
                                    Edit PDF
                                </button>
                                <a href="pdf/downloadEditedPdfNew" target="_blank">
                                <button class="btn btn-success" type="button" id="download_edited_pdf">
                                    <i class="fas text-muted"></i>
                                    Download Edited Pdf
                                </button>
                                </a>

                            </span>
                            <div id="overlay">
                                <div class="cv-spinner">
                                    <span class="spinner"></span>
                                </div>

                            </div>
                            <div id="info"></div>

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
            $("#overlay").fadeIn(300);
            // initProgress();
            // $('#edit-pdf-form').submit();
            // var progress = setInterval(function(){
            //     $.getJSON('pdf/progress', function(data) {
            //         // $('#progress').html(data[0]);
            //         $("#progress .progress-item").css('width', data[0] + "%");
            //         $("#datadd").html(data[0] + "%");
            //         if (data[0] == 100){
            //             $('#progress').hide();
            //         }
            //     });
            // }, 3);
            var flag = true;
            $.ajax({
                type: "POST",
                url: "pdf/editPdf",
                data: $('#edit-pdf-form').serialize(),

                success: function (result) {
                    flag = false
                    $("#info").html(result)
                    setTimeout(function(){
                        $("#overlay").fadeOut(300);
                    },500);
                    setTimeout(function(){
                        $("#info").html('');
                    },3000);
                    // finishProgress(progress);
                },
                complete:function () {
                    if (flag){
                        $('#edit-pdf-form').submit();
                        setTimeout(function(){
                            $("#overlay").fadeOut(300);
                        },500);

                    }

                   // finishProgress(progress);
                },
                error: function(xhr) { // if error occured
                    // finishProgress(progress);
                },
            });
        });

        function initProgress() {
            var data = 0;
            $('#progress').show();
            $("#progress .progress-item").css('width',  data+"%");
            $("#datadd").html(data+"%");

        }

        function finishProgress(progress) {
            clearInterval(progress);
            var data = 100;
            $("#progress .progress-item").css('width',  data+"%");
            $("#datadd").html(data+"%");
            $('#progress').hide();
        }


        

    </script>

@stop
