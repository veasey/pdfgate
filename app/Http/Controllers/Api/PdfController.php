<?php

namespace App\Http\Controllers\Api;

use App\Jobs\GeneratePdfJob;
use App\Models\PdfJob;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class PdfController extends Controller
{
    public function generate(Request $request): Response
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $user = $request->user();
        $user->increment('pdf_generated_count');
        $user->update(['last_generated_at' => now()]);

        $pdfJob = $user->pdfJobs()->create([
            'status' => PdfJob::STATUS_PENDING,
            'payload' => $validated,
        ]);

        GeneratePdfJob::dispatchSync($pdfJob);

        $pdfJob->refresh();
        $content = base64_decode($pdfJob->result ?? '');

        return response($content, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="document.pdf"');
    }

    public function show(Request $request, PdfJob $pdfJob): JsonResponse
    {
        $this->authorizeJobAccess($request->user()->id, $pdfJob);

        return response()->json([
            'id' => $pdfJob->id,
            'status' => $pdfJob->status,
            'payload' => $pdfJob->payload,
            'download_url' => route('api.pdf.download', $pdfJob),
            'created_at' => $pdfJob->created_at,
            'updated_at' => $pdfJob->updated_at,
        ]);
    }

    public function download(Request $request, PdfJob $pdfJob): Response|JsonResponse
    {
        $this->authorizeJobAccess($request->user()->id, $pdfJob);

        if ($pdfJob->status !== PdfJob::STATUS_COMPLETED || ! $pdfJob->result) {
            return response()->json([
                'message' => 'PDF is not ready yet.',
                'status' => $pdfJob->status,
            ], 202);
        }

        return response(base64_decode($pdfJob->result), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="document.pdf"');
    }

    protected function authorizeJobAccess(int $userId, PdfJob $pdfJob): void
    {
        if ($pdfJob->user_id !== $userId) {
            abort(403);
        }
    }
}
