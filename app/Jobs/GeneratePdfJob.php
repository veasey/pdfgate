<?php

namespace App\Jobs;

use App\Models\PdfJob;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class GeneratePdfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public PdfJob $pdfJob;

    public function __construct(PdfJob $pdfJob)
    {
        $this->pdfJob = $pdfJob;
    }

    public function handle(): void
    {
        $this->pdfJob->update(['status' => PdfJob::STATUS_PROCESSING]);

        $html = view('pdf.template', $this->pdfJob->payload)->render();
        $pdf = Pdf::loadHTML($html);

        $this->pdfJob->update([
            'status' => PdfJob::STATUS_COMPLETED,
            'result' => base64_encode($pdf->output()),
        ]);
    }

    public function failed(Throwable $exception): void
    {
        $this->pdfJob->update(['status' => PdfJob::STATUS_FAILED]);
    }
}
