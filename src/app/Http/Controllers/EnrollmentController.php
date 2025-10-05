<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EnrollmentController extends Controller
{
    // Middleware akan ditangani di route level



    /**
     * Proses enrollment dengan dummy payment
     */
    public function processEnrollment(Request $request, Course $course)
    {
        $request->validate([
            'payment_method' => 'required|in:free,dummy_card,dummy_wallet,dummy_transfer',
            'invoice_number' => 'nullable|string|max:255',
        ]);

        // Cek apakah user sudah terdaftar
        if (Auth::user()->enrollments()->where('course_id', $course->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah terdaftar di course ini.'
            ], 400);
        }

        DB::beginTransaction();

        try {
            $transaction = null;

            // Handle free course enrollment
            if ($request->payment_method === 'free' || $course->price == 0) {
                // For free courses, create transaction record with price 0
                $invoiceNumber = $request->invoice_number ?? Transaction::generateInvoiceNumber();

                $transaction = Transaction::create([
                    'user_id' => Auth::id(),
                    'invoice_number' => $invoiceNumber,
                    'total_amount' => 0,
                    'payment_status' => 'completed',
                    'payment_method' => 'free',
                    'paid_at' => now()
                ]);

                // Create transaction item for the free course
                $transaction->items()->create([
                    'course_id' => $course->id,
                    'price' => 0,
                    'quantity' => 1,
                    'subtotal' => 0,
                    'discount' => 0
                ]);

                $enrollment = Enrollment::create([
                    'user_id' => Auth::id(),
                    'course_id' => $course->id,
                    'transaction_id' => $transaction->id
                ]);
            } else {
                // For paid courses, create transaction and process payment
                $invoiceNumber = $request->invoice_number ?? Transaction::generateInvoiceNumber();

                $transaction = Transaction::create([
                    'user_id' => Auth::id(),
                    'invoice_number' => $invoiceNumber,
                    'total_amount' => $course->price,
                    'payment_status' => 'pending',
                    'payment_method' => $request->payment_method,
                    'paid_at' => null
                ]);

                // Buat transaction item
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'course_id' => $course->id,
                    'price' => $course->price,
                    'quantity' => 1,
                    'subtotal' => $course->price,
                    'discount' => 0
                ]);

                // Simulate dummy payment processing (always success for now)
                $transaction->update([
                    'payment_status' => 'completed',
                    'paid_at' => now()
                ]);

                // Buat enrollment record
                $enrollment = Enrollment::create([
                    'user_id' => Auth::id(),
                    'course_id' => $course->id,
                    'transaction_id' => $transaction->id
                ]);
            }

            // Increment enrolled_count di course
            $course->increment('enrolled_count');

            DB::commit();

            $responseData = [
                'success' => true,
                'message' => 'Enrollment berhasil! Selamat belajar.',
                'redirect_url' => route('dashboard'),
                'course' => [
                    'id' => $course->id,
                    'title' => $course->title,
                    'price' => $course->price
                ]
            ];

            // Add transaction details if payment was processed
            if ($transaction) {
                $responseData['transaction'] = [
                    'id' => $transaction->id,
                    'invoice_number' => $transaction->invoice_number,
                    'amount' => $transaction->total_amount,
                    'payment_method' => $transaction->payment_method,
                    'paid_at' => $transaction->paid_at->toISOString()
                ];
            }

            return response()->json($responseData);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses enrollment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark a course as complete for the current user
     */
    public function markCourseComplete(Course $course)
    {
        try {
            $enrollment = Auth::user()->enrollments()->where('course_id', $course->id)->first();

            if (!$enrollment) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not enrolled in this course.'
                ], 404);
            }

            $enrollment->update([
                'completed_at' => now(),
                'progress_percentage' => 100.00
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Course marked as complete!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating course progress: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark a course as incomplete for the current user
     */
    public function markCourseIncomplete(Course $course)
    {
        try {
            $enrollment = Auth::user()->enrollments()->where('course_id', $course->id)->first();

            if (!$enrollment) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not enrolled in this course.'
                ], 404);
            }

            $enrollment->update([
                'completed_at' => null,
                'progress_percentage' => 0.00
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Course marked as incomplete!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating course progress: ' . $e->getMessage()
            ], 500);
        }
    }

}
