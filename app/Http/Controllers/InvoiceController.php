<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Elibyy\TCPDF\Facades\TCPDF;

class InvoiceController extends Controller
{
    /**
     * A private helper method to generate the PDF object.
     * This avoids code duplication between print and download methods.
     */
    private function generatePdf(Order $order): \TCPDF
    {
        $order->load(['products', 'user']);
        
        $html = view('invoices.purchase', compact('order'))->render();
        
        $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8');
        
        // --- Key Settings for Single Page ---
        
        // 1. Set small margins to maximize space (left, top, right)
        $pdf->SetMargins(10, 15, 10);
        
        // 2. Prevent TCPDF from automatically creating a new page
        $pdf->SetAutoPageBreak(false, 0);
        
        // --- End of Key Settings ---
        
        // إعدادات RTL
        $pdf->setRTL(true);
        $pdf->SetCreator('Ajyad Maka');
        $pdf->SetAuthor('Ahmed Saad');
        $pdf->SetTitle('Invoice ' . $order->id);
        
        // إعدادات الخط - using a slightly smaller font to save space
        $pdf->SetFont('dejavusans', '', 11);
        
        // إزالة الهيدر والفوتر الافتراضي
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        
        // إضافة صفحة
        $pdf->AddPage();
        
        // كتابة HTML
        $pdf->writeHTML($html, true, false, true, false, '');
        
        return $pdf;
    }

    /**
     * Display the invoice in the browser.
     */
    public function print(Order $order)
    {
        $pdf = $this->generatePdf($order);
        
        // 'I' parameter sends the file to the browser inline
        return response($pdf->Output('invoice_'.$order->id.'.pdf', 'I'))
            ->header('Content-Type', 'application/pdf');
    }
    
    /**
     * Download the invoice file.
     */
    public function download(Order $order)
    {
        $pdf = $this->generatePdf($order);
        
        // 'D' parameter forces the file to be downloaded
        return response($pdf->Output('invoice_'.$order->id.'.pdf', 'D'))
            ->header('Content-Type', 'application/pdf');
    }
}