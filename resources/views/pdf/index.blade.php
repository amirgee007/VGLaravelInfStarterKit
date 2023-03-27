@extends('layouts.app')

@section('page-title', trans('app.pdf_editor'))
@section('page-heading', trans('app.pdf_editor'))

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('pdf.index') }}">@lang('app.pdf_editor')</a>
    </li>
@stop

@section('content')

    @include('partials.messages')

    {!! Form::open(['route' => 'pdf.edit', 'files' => true, 'id' => 'pdf-form']) !!}
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <h5 class="card-title">
                        @lang('app.pdf_details')
                    </h5>
                    <p class="text-muted font-weight-light">
                        PDF Editor information.
                    </p>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-12">
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
                                       name="product_id" placeholder="Product ID" value="">
                            </div>
                            <div class="form-group">
                                <label for="quantity">Quantity</label>
                                <input type="text" class="form-control" id="quantity"
                                       name="quantity" placeholder="Quantity" value="">
                            </div>
                            <div class="form-group">
                                <label for="stock_location">Stock Location</label>
                                <input type="text" class="form-control" id="stock_location"
                                       name="stock_location" placeholder="Stock Location" value="">
                            </div>
                            <div class="form-group">
                                <label for="ean_number">EAN Number</label>
                                <input type="text" class="form-control" id="ean_number"
                                       name="ean_number" placeholder="EAN Number" value="">
                            </div>
                            <div class="form-group float-right">
                                <a target="_blank" href="{{ route('pdf.download.current') }}">
                                    <span class="btn btn-danger">
                                        Download Current PDF
                                    </span>
                                </a>
                                <button type="submit" class="btn btn-warning">
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
                <div class="col-md-3"></div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}

    <br>
@stop

@section('scripts')
    {!! HTML::script('assets/js/as/profile.js') !!}
    {!! JsValidator::formRequest('Vanguard\Http\Requests\Pdf\EditPdfRequest', '#pdf-form') !!}
@stop