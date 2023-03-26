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

    @include('partials.messages')

    {!! Form::open(['route' => 'pdf.edit', 'files' => true, 'id' => 'pdf-form']) !!}

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="row">
                    <div class="col-md-15">
                        <div class="form-group">
                            <label for="pdf_file">Click Here To Upload PDF File</label>
                            <div class="btn btn-block btn-danger">
                                <div class="btn btn-upload text-white">
                                    <i class="fa fa-upload"></i>
                                    <input type="file" class="form-control" name="pdf_file" id="pdf_file">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="product_id">Product ID</label>
                            <input type="text" class="form-control" id="product_id"
                                   name="product_id" placeholder="Product ID">
                        </div>

                        <div class="form-group">
                            <label for="quantity">Quantity</label>
                            <input type="text" class="form-control" id="quantity"
                                   name="quantity" placeholder="Quantity">
                        </div>

                        <div class="form-group">
                            <label for="stock_location">Stock Location</label>
                            <input type="text" class="form-control" id="stock_location"
                                   name="stock_location" placeholder="Stock Location">
                        </div>

                        <div class="form-group">
                            <label for="ean_number">EAN Number</label>
                            <input type="text" class="form-control" id="ean_number"
                                   name="ean_number" placeholder="EAN Number">
                        </div>

                        <div class="form-group float-right">
                            <a target="_blank" href="{{ route('pdf.download.current') }}">
                                <span class="btn btn-danger">
                                    Download Current PDF
                                </span>
                            </a>
                            <button id="submit" type="submit" class="btn btn-warning">
                                Edit PDF
                            </button>
                            <a target="_blank" href="{{ route('pdf.download.edited') }}">
                                <span class="btn btn-primary">
                                    Download Edited PDF
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@stop

@section('scripts')
    <script>
        $("#submit").submit(function(e){
            // Block form submission
            e.preventDefault();
            let form_ele = document.getElementById('pdf-form');
            let form_data = new FormData(form_ele);

            // ajax submit
            $.ajax({
                url: "{{ route('pdf.edit') }}",
                method: 'post',
                data: form_data,
                dataType: 'json',
                contentType:false,
                processData:false,
                success: function (data) {
                    console.log(111);
                },
            });
        })

    </script>
    {!! JsValidator::formRequest('Vanguard\Http\Requests\Pdf\PdfRequest', '#pdf-form') !!}
@stop