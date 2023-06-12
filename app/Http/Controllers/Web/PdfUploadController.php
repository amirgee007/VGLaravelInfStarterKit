<?php

namespace Vanguard\Http\Controllers\Web;

use Storage;
use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use Vanguard\Http\Requests\uploadPdf\UploadPdfRequest;
use Vanguard\Http\Controllers\Api\UploadController as EditPDF;

class PdfUploadController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');

    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        return view('pdfUpload.index', compact(''));
    }

    public function upload(UploadPdfRequest $request){
        try{
            $editModel = new EditPDF();
            $editModel->editPDF( $request );
            return view('pdfUpload.index', compact(''));
        }catch(\Exception $exception){
            \Log::error( $exception->getMessage());
        }
    }
}
