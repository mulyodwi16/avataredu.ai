<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    // Middleware akan ditangani di route level

    /**
     * Tampilkan detail transaction
     */
    public function show(Transaction $transaction)
    {
        // Pastikan user hanya bisa melihat transaction sendiri
        if ($transaction->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to transaction.');
        }

        $transaction->load(['items.course', 'user']);

        return view('purchase.show', compact('transaction'));
    }

    /**
     * Download invoice (dummy PDF)
     */
    public function downloadInvoice(Transaction $transaction)
    {
        // Pastikan user hanya bisa download invoice sendiri
        if ($transaction->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to invoice.');
        }

        // Untuk sekarang kita buat response dummy
        // Nanti bisa diintegrasikan dengan PDF generator
        return response()->json([
            'success' => true,
            'message' => 'Invoice download will be available soon.',
            'invoice_number' => $transaction->invoice_number,
            'total_amount' => $transaction->total_amount
        ]);
    }

    /**
     * Retry payment untuk transaction yang gagal
     */
    public function retryPayment(Transaction $transaction)
    {
        // Pastikan user hanya bisa retry payment sendiri
        if ($transaction->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to transaction.');
        }

        // Hanya bisa retry jika status failed
        if ($transaction->payment_status !== 'failed') {
            return response()->json([
                'success' => false,
                'message' => 'Payment can only be retried for failed transactions.'
            ], 400);
        }

        // Simulate payment retry (dummy success)
        $transaction->update([
            'payment_status' => 'paid',
            'payment_date' => now()
        ]);

        // Update enrollment status jika ada
        if ($transaction->items->isNotEmpty()) {
            foreach ($transaction->items as $item) {
                // Aktivasi enrollment yang terkait
                $enrollment = Auth::user()->enrollments()
                    ->where('course_id', $item->course_id)
                    ->where('transaction_id', $transaction->id)
                    ->first();

                if ($enrollment) {
                    $enrollment->update(['status' => 'active']);
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment successful! You can now access your courses.',
            'redirect_url' => route('dashboard')
        ]);
    }
}
