<?php

namespace Vanguard\Http\Controllers\Web;

use Auth;
use Mpdf\Mpdf;
use Vanguard\Http\Controllers\Controller;
use Vanguard\Http\Requests\Pdf\EditPdfRequest;

/**
 * Class PdfController
 * @package Vanguard\Http\Controllers
 */
class PdfController extends Controller
{

    /**
     * PdfController constructor.
     */
    public function __construct()
    {
    }

    /**
     * Displays the page with crawl data for admin role.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('pdf.index');
    }

    /**
     * Edit the pdf file.
     * The `pdftk` software must be installed on the machine.
     *
     * @param EditPdfRequest $request
     * @throws \Exception
     * @throws \Mpdf\FilterException
     * @throws \Mpdf\MpdfException
     * @throws \Mpdf\PdfParserException
     * @throws \Mpdf\PdfReaderException
     * @throws \setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException
     * @throws \setasign\Fpdi\PdfParser\Type\PdfTypeException
     */
    public function edit(EditPdfRequest $request)
    {
        $barcode_url = 'https://barcode.tec-it.com/barcode.ashx?data=' . $request->input('ean_number') . '&code=Code128&translate-esc=on';
        $barcode_path = storage_path('app/pdf/barcode-' . Auth::user()->id . '.png');
        file_put_contents($barcode_path, file_get_contents($barcode_url));

        $before_edit_pdf_name = 'pdf_file-' . Auth::user()->id . '.pdf';
        $edited_pdf_path = storage_path('app/pdf/edited-' . Auth::user()->id . '.pdf');
//        $request->file('pdf_file')->storeAs('pdf', $before_edit_pdf_name);

        $pdf = new Mpdf();
        $pdf->AddPage();
        try {
            $pdf->setSourceFile(storage_path('app/pdf/' . $before_edit_pdf_name));
        } catch (\Exception $e) {
            try {
                exec('pdftk ' . storage_path('app/pdf/' . $before_edit_pdf_name) . ' output ' . storage_path('app/pdf/expanded-' . $before_edit_pdf_name) . ' uncompress');
                $pdf->setSourceFile(storage_path('app/pdf/expanded-' . $before_edit_pdf_name));
            } catch (\Exception $e) {
                throw $e;
            }
        }
        $tplIdx = $pdf->importPage(1);
        $pdf->useTemplate($tplIdx);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetXY(20, 155);
        $pdf->Write(0, $request->input('product_id'), 0, '', 'ltr', 'L');
        $pdf->SetXY(20, 155);
        $pdf->Write(0, $request->input('quantity'), 0, '', 'ltr', 'C');
        $pdf->SetXY(20, 155);
        $pdf->Write(0, $request->input('stock_location'), 0, '', 'ltr', 'R');
        $pdf->Image($barcode_path, 140, 210, 0, 20);
        $pdf->Output($edited_pdf_path);
    }

    /**
     * Download the edited pdf file.
     */
    public function download()
    {
        $edited_pdf_path = storage_path('app/pdf/edited-' . Auth::user()->id . '.pdf');
        if (!is_file($edited_pdf_path)) {
            return 'Please click "Edit PDF" button first';
        }

        return response()->download($edited_pdf_path);
    }
}
