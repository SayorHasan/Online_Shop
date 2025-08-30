<?php

namespace App\Services;

use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Support\Facades\Storage;

class InvoiceService
{
    public function __construct(private ViewFactory $view)
    {
    }

    /**
     * Generate an invoice PDF if Dompdf is available, otherwise return path to saved HTML.
     * @return array{path:string, mime:string}
     */
    public function generateForOrder(\App\Models\Order $order): array
    {
        $html = $this->view->make('pdf.invoice', compact('order'))->render();
        $dir = 'invoices';
        \Illuminate\Support\Facades\Storage::makeDirectory($dir);
        $basePath = $dir.'/invoice-'.$order->order_number;

        if (class_exists('Dompdf\\Dompdf')) {
            $options = new \Dompdf\Options();
            $options->set('isRemoteEnabled', false);
            $options->set('defaultFont', 'DejaVu Sans');
            $dompdf = new \Dompdf\Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4');
            $dompdf->render();
            // Stream once to ensure full render before saving
            $dompdf->getCanvas();
            $pdf = $dompdf->output();
            if (!is_string($pdf) || strlen($pdf) < 1000) {
                // Fallback to HTML if output suspiciously small
                $path = $basePath.'.html';
                Storage::disk('local')->put($path, $html);
                $abs = storage_path('app/'.$path);
                if (!is_file($abs)) {
                    @mkdir(dirname($abs), 0777, true);
                    file_put_contents($abs, $html);
                }
                return ['path' => $abs, 'mime' => 'text/html'];
            }
            $path = $basePath.'.pdf';
            // Write via Storage and ensure physical file exists
            Storage::disk('local')->put($path, $pdf);
            $abs = storage_path('app/'.$path);
            if (!is_file($abs)) {
                @mkdir(dirname($abs), 0777, true);
                file_put_contents($abs, $pdf);
            }
            return ['path' => $abs, 'mime' => 'application/pdf'];
        }

        if (class_exists('Barryvdh\\DomPDF\\Facade\\Pdf')) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html)->setPaper('a4');
            $raw = $pdf->output();
            $path = $basePath.'.pdf';
            Storage::disk('local')->put($path, $raw);
            $abs = storage_path('app/'.$path);
            if (!is_file($abs)) {
                @mkdir(dirname($abs), 0777, true);
                file_put_contents($abs, $raw);
            }
            return ['path' => $abs, 'mime' => 'application/pdf'];
        }

        $path = $basePath.'.html';
        Storage::disk('local')->put($path, $html);
        $abs = storage_path('app/'.$path);
        if (!is_file($abs)) {
            @mkdir(dirname($abs), 0777, true);
            file_put_contents($abs, $html);
        }
        return ['path' => $abs, 'mime' => 'text/html'];
    }
}


