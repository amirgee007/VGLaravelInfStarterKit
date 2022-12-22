<?php

namespace Vanguard\Http\Controllers\Web\Product;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Vanguard\Http\Controllers\Controller;
use Vanguard\Http\Requests\Product\EditPdfRequest;
use Vanguard\Repositories\Product\ProductRepository;
use Vanguard\Repositories\Product\EloquentProduct;
use Illuminate\Http\Request;
use Encore\Admin\Grid\Exporters\AbstractExporter;
use setasign\Fpdi\Fpdi;


/**
 * Class FetchDataController
 * @package Vanguard\Http\Controllers
 */
class PdfTransferController extends Controller
{
    /**
     * @var EloquentProduct
     */
    private $products;



    private $fpdi;

    /**
     * DownloadDataController constructor.
     * @param ProductRepository $products
     */
    public function __construct( ProductRepository $products)
    {
        $this->middleware('auth');
        $this->products = $products;
        $this->fpdi = new FPDI();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){
        return view('product.pdfPramInput');
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Picqer\Barcode\Exceptions\BarcodeException
     * @throws \setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException
     * @throws \setasign\Fpdi\PdfParser\Filter\FilterException
     * @throws \setasign\Fpdi\PdfParser\PdfParserException
     * @throws \setasign\Fpdi\PdfParser\Type\PdfTypeException
     * @throws \setasign\Fpdi\PdfReader\PdfReaderException
     */
    public function editPdf(EditPdfRequest $request){
        $re = $this->validate($request, $request->rules());
//        var_dump($re);
        Session::put('progress', 0);
        Session::save(); // Remember to call save()
        // download sample file.
//        unlink the previous file
//        if (file_exists(Session::get('pdf-name'))){
//            unlink(Session::get('pdf-name'));
//        }
        $outputFile = Storage::disk('local')->path('output-'.time().'.pdf');
//        echo $outputFile;
        Session::put('pdf-name', $outputFile);
        Session::save(); // Remember to call save()
        $product_id = $request->post('product_id');
        $quantity = $request->post('quantity');
        $stock_location = $request->post('stock_location');
        $ean_number = $request->post('ean_number');
        // fill data
        $this->fillPDF(public_path().('/template/original.pdf'), $outputFile, $product_id, $quantity, $stock_location, $ean_number);



        return \Response::json('Edit pdf complete.');
    }

    /**return progress
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProgress() {
        Session::put('progress', Session::get('progress') <= 90 ? Session::get('progress') + 10 : 100);
        Session::save();
        return Response()->json(array(Session::get('progress')));
    }

    /**
     * fill data to exist pdf
     * @param $file
     * @param $outputFile
     * @param $product_id
     * @param $quantity
     * @param $stock_location
     * @param $ean_number
     * @return string
     * @throws \Picqer\Barcode\Exceptions\BarcodeException
     * @throws \setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException
     * @throws \setasign\Fpdi\PdfParser\Filter\FilterException
     * @throws \setasign\Fpdi\PdfParser\PdfParserException
     * @throws \setasign\Fpdi\PdfParser\Type\PdfTypeException
     * @throws \setasign\Fpdi\PdfReader\PdfReaderException
     */
    public function fillPDF($file, $outputFile, $product_id, $quantity, $stock_location, $ean_number)
    {
        $this->fpdi = new FPDI();
        // merger operations
        $count = $this->fpdi->setSourceFile($file);
        for ($i=1; $i<=$count; $i++) {
            $template   = $this->fpdi->importPage($i);
            $size       = $this->fpdi->getTemplateSize($template);
            $this->fpdi->AddPage($size['orientation'], array($size['width'], $size['height']));
            $this->fpdi->useTemplate($template);
            $left = 20;
            $top = 100;

            $this->fpdi->SetFont("helvetica", "", 15);
            $this->fpdi->SetTextColor(153,0,153);
            //draw product id
            $this->fpdi->Text($left,$top, strval($product_id));
            $left = 100;
            //draw $quantity
            $this->fpdi->Text($left,$top,strval($quantity));
            $left  = 160;
            //draw stock location
            $this->fpdi->Text($left,$top,strval($stock_location));
            //draw ean number
            //generate barcode
            $file_dir = 'uploads/barcode/'.date('Y-m-d');
            if (!file_exists($file_dir)) {
                mkdir($file_dir,0755,true);
            }
            $name = rand(0,99999).time();
            $imgUrl = $file_dir.'/'.$name.'.jpg';
            $generator = new \Picqer\Barcode\BarcodeGeneratorJPG();
            file_put_contents($imgUrl, $generator->getBarcode($ean_number, $generator::TYPE_CODE_128, 5, 140));
            // Place the graphics
            $this->fpdi->image($imgUrl, 130, 143, 70);
            $this->fpdi->SetTextColor(0,0,0);
            $this->fpdi->Text(145,168,strval($ean_number));

        }
//        echo $outputFile;
        return $this->fpdi->Output($outputFile, 'F');
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadCurrentPdf(Request $request)
    {
        return response()->download(public_path().('/template/original.pdf'));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadEditedPdfNew(Request $request){
        $fileName = Session::get('pdf-name');
        return response()->download($fileName);
    }
}
