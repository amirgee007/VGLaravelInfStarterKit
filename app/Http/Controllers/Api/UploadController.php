<?php
namespace Vanguard\Http\Controllers\Api;

use Illuminate\Http\Request;
use Storage;
use Carbon\Carbon;
use setasign\Fpdi\Fpdi;

class UploadController extends ApiController
{
    protected $fileDir = '';
    static $checkLetterArr = ['.pdf', '.txt'];
    static $onlyPdf = ['.pdf'];
    const CHECKLETTER = -4;
    const TIMECHECK = 10;
    const EDITEDPDF = 'edited.pdf';
    const PDFBARCODE = 'pdfReadyBarcode.png';

    public function __construct(){
        $this->fileDir = storage_path('app')."/";
    }

    public function editPDF( $request ){
        try{
            $productId = $request->get( 'product_id' );
            $stockLocation = $request->get( 'stock_location' );
            $quantity = $request->get( 'quantity' );
            $ean = $request->get( 'ean_number' );
            if( file_exists( $this->fileDir.static::PDFBARCODE ) ) unlink( $this->fileDir.static::PDFBARCODE );
            if( file_exists( $this->fileDir.static::EDITEDPDF ) ) unlink( $this->fileDir.static::EDITEDPDF );

            $name = $this->_getCurrentOnlyPDF( static::$onlyPdf );

            //transfer pdf version into 1.4 from higher version
            shell_exec( "gswin64c -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dNOPAUSE -dQUIET -dBATCH -sOutputFile=".$this->fileDir.static::EDITEDPDF." ".$this->fileDir.$name."");

            //generate barcode png
            $generatorPNG = new \Picqer\Barcode\BarcodeGeneratorPNG();
            $barcode = $generatorPNG->getBarcode($ean, $generatorPNG::TYPE_CODE_128,3,80);
            file_put_contents($this->fileDir.static::PDFBARCODE, $barcode);

            $pdf = new FPDI();
            $pdf->AddPage();
            $pdf->setSourceFile($this->fileDir.static::EDITEDPDF);
            $tplIdx = $pdf->importPage(1);
            $pdf->useTemplate($tplIdx);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->image($this->fileDir.static::PDFBARCODE,150,210,30,15);
            $pdf->SetFont('Helvetica', '', 16);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(35, 160);
            $pdf->Write(0, $productId);
            $pdf->SetXY(95, 160);
            $pdf->Write(0, $quantity);
            $pdf->SetXY(140, 160);
            $pdf->Write(0, $stockLocation);
            $pdf->SetFont('Helvetica', '',9);
            $pdf->SetXY(157, 228);
            $pdf->Write(0, $ean);
            $pdf->Output( $this->fileDir.static::EDITEDPDF, 'F');

        }catch(\Exception $exception){
            \Log::error( 'edit-pdf-error.'.$exception->getMessage());
        }
    }

    private function _getCurrentOnlyPDF( $filterArray ){
        $dirList = scandir(storage_path( 'app' ));
        $checkArr = [];
        if( !empty( $dirList )){
            foreach( $dirList as $value ){
                $checkLetter = substr( $value, static::CHECKLETTER );
                if( in_array( $checkLetter, $filterArray ) ){
                    $checkArr[substr( $value, 0,static::TIMECHECK )] = $value;
                }
            }
            ksort( $checkArr );
            return array_last( $checkArr );
        }
        return false;
    }

    public function downloadEditPDF(){
        return $this->_downloadOperation( static::EDITEDPDF );
    }
    public function downloadCurrentPDF(){
        return $this->_downloadOperation();
    }

    private function _downloadOperation( $name = null ){
        if( empty( $name ) ) $name = $this->_getCurrentOnlyPDF( static::$checkLetterArr );
        $file = $this->fileDir.$name;
        if( !file_exists( $file ) ) return redirect()->to('pdfUpload');
        $headers = [
            'Content-Type: application/octet-stream',
            'Accept-Ranges:bytes',
            'Accept-Length: '.filesize($this->fileDir . $name),
            'Content-Disposition: attachment; filename='.$name
        ];
        return response()->download($file, $name, $headers);
    }

    public function uploadPDFFile(Request $request) {
        try{
            $validator = \Validator::make($request->all(), [
                'file' => 'max:5120',
            ]);
            if( $validator->fails() ) {
                return $this->respondWithError( $validator->errors()->first() );
            }
            $type = $request->get('file');
            $file = $request->file();
            $key = array_keys($file)[0];
            $extension = $request->file($key)->getClientOriginalExtension();

            $fileName = Carbon::now()->timestamp.date('Ymd').str_random().'.'.$extension;
            $response = $request->file($key)->storeAs($type, $fileName);
            $url = Storage::url($response);
            return response()->json( $url );
        }catch(\Exception $exception){
            \Log::error( 'upload-error:'.$exception->getMessage());
            return response()->json($exception->getMessage());
        }
    }
}