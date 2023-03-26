<?php
/**
 * @desc
 * @author     WenMing<st-m1ng@163.com>
 * @date       2023-03-27 4:07
 */
namespace Vanguard\Http\Controllers\Web;

use Vanguard\Http\Controllers\Controller;
use Vanguard\Http\Requests\Pdf\PdfRequest;
use Mpdf\Mpdf;

class PdfController extends Controller
{
    /**
     * @desc   view
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function index()
    {
        return view('pdf.index');
    }

    /**
     * @desc  Edit pdf file
     * @param PdfRequest $request
     */
    public function edit(PdfRequest $request)
    {
        $eanNumber = $request->input('ean_number');
        $url = "https://barcode.tec-it.com/barcode.ashx?data={$eanNumber}&translate-esc=on&code=Code128";
        $content = file_get_contents($url);
        $barcodePath = storage_path('app/pdf/barcode.png');

        if (!is_dir(storage_path('app/pdf/'))) {
            // dir doesn't exist, make it
            @mkdir(storage_path('app/pdf/'), 0755);
        }
        //Save image
        file_put_contents($barcodePath, $content);

        $beforeName = 'pdf_file.pdf';
        $afterPath = storage_path('app/pdf/edited.pdf');

        $mpdf = new Mpdf();
        $mpdf->autoLangToFont = true;
        $mpdf-> showImageErrors = true;
        $mpdf->showWatermarkText = true;
        $mpdf->AddPage();
        $mpdf->SetWatermarkText('WenMing',0.05);//参数一是文字，参数二是透明度
        $mpdf->setSourceFile(storage_path('app/pdf/' . $beforeName));
        $mpdf->useTemplate($mpdf->importPage(1));
        $mpdf->SetTextColor(0,0,0);
        $mpdf->SetFont('Arial', 'B', 12);
        $mpdf->SetXY(20, 156);
        $mpdf->Write(0, $request->input('product_id'), 0, '', 'ltr', 'L');
        $mpdf->SetXY(20, 156);
        $mpdf->Write(0, $request->input('quantity'), 0, '', 'ltr', 'C');
        $mpdf->SetXY(20, 156);
        $mpdf->Write(0, $request->input('stock_location'), 0, '', 'ltr', 'R');
        $mpdf->Image($barcodePath, 138, 208, 0, 20);
        $mpdf->Output($afterPath, 'F');

        print_r('Edit pdf file successfully!Please return to the previous page to download.');
    }

    /**
     * @desc   download current pdf file
     * @return string|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadCurrent()
    {
        $path = storage_path('app/pdf/pdf_file.pdf');
        if (!is_file($path)) {
            return 'No current file';
        }

        return response()->download($path);
    }

    /**
     * @desc   download edited pdf file
     * @return string|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadEdited()
    {
        $path = storage_path('app/pdf/edited.pdf');
        if (!is_file($path)) {
            return 'No edited';
        }

        return response()->download($path);
    }
}