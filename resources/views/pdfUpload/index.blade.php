@extends('layouts.app')

@section('page-title', trans('app.pdf_upload'))
@section('page-heading', trans('app.pdf_upload'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('app.pdf_upload')
    </li>
@stop

@section('content')

    @include('partials.messages')

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="first_name">@lang('app.upload_info')</label>
                                <form enctype="multipart/form-data" id="form_data" class="btn btn-danger">
                                    <input name="file" id="fileName1" type="file" onchange="uploadPDF()" style="position: absolute; opacity: 0; filter:Alpha(opacity=0);">
                                    <i class="fas fa-upload" ></i>
                                </form>
                                <span id="exist_pdf">@lang('app.no_pdf')</span>
                            </div>
                            <progress max="" value="" id="process_bar" style="display:none;"></progress>
                            {!! Form::open(['route' => 'pdfUpload.upload', 'files' => true, 'id' => 'upload-pdf']) !!}
                            <input type="hidden" id="uploaded_pdf" name="uploaded_pdf" value="">
                            <div class="form-group">
                                <label for="status">@lang('app.product_id')</label>
                                <input type="text" class="form-control" id="product_id"
                                       name="product_id" placeholder="@lang('app.product_id')" >
                            </div>
                            <div class="form-group">
                                <label for="status">@lang('app.quantity')</label>
                                <input type="text" class="form-control" id="quantity"
                                       name="quantity" placeholder="@lang('app.quantity')" >
                            </div>
                            <div class="form-group">
                                <label for="status">@lang('app.stock_location')</label>
                                <input type="text" class="form-control" id="stock_location"
                                       name="stock_location" placeholder="@lang('app.stock_location')" >
                            </div>
                            <div class="form-group">
                                <label for="status">@lang('app.EAN_Number')</label>
                                <input type="text" class="form-control" id="ean_number"
                                       name="ean_number" placeholder="@lang('app.EAN_Number')" >
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-warning" type="submit" onclick="editPDF()">@lang('app.edit_pdf')</button>
                    {!! Form::close() !!}

                    <form id="downloadForm" name=downloadCurrent action="api/download_current_pdf" method="post" style='display:none'></form>
                    <form id="downloadEditPDF" name=downloadEdited action="api/download_edited_pdf" method="post" style='display:none'></form>
                    <button class="btn btn-danger" onclick="downloadCurrentPDF()">@lang('app.download_current_pdf')</button>
                    <button class="btn btn-primary" onclick="downloadEditedPDF()">@lang('app.download_edited_pdf')</button>
                </div>
            </div>
        </div>
    </div>
@stop
<script>
    function editPDF(){
        document.getElementById("process_bar").setAttribute("style", "display: inline;");
    }
    function downloadCurrentPDF(){
        document.downloadCurrent.submit();
    }
    function downloadEditedPDF(){
        document.downloadEdited.submit();
    }
    function uploadPDF(){
        const formData = new FormData(document.getElementById("form_data"));
        $.ajax({
            url:"api/upload_pdf_file",
            data: formData,
            datatype: 'json',
            processData: false,
            contentType: false,
            type: 'POST',
            success: function (response) {
                document.getElementById('uploaded_pdf').value = response;
                document.getElementById('exist_pdf').innerHTML = 'Pdf Uploaded';
                console.log( JSON.stringify( response ) );
            },
            error: function (error) {
                console.log( JSON.stringify( error ) );
            },
        })
    }
</script>
@section('scripts')
    {!! JsValidator::formRequest('Vanguard\Http\Requests\UploadPdf\UploadPdfRequest', '#upload-pdf') !!}
@stop





