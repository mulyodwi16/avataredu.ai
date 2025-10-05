{{-- Purchase History Content --}}
<div class="bg-white rounded-lg shadow-sm">
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Purchase History</h1>
                <p class="text-gray-600 mt-1">View all your course purchases and transactions</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="text-right">
                    <div class="text-2xl font-bold text-primary">                    <div class="text-2xl font-bold text-primary">{{ is_array($transactions) ? count($transactions) : ($transactions->count() ?? 0) }}</div></div>
                    <div class="text-sm text-gray-600">Total Orders</div>
                </div>
            </div>
        </div>
    </div>

    <div class="p-6">

        @if($transactions && (is_array($transactions) ? count($transactions) > 0 : $transactions->count() > 0))
            <div class="space-y-4">
                @foreach($transactions as $transaction)
                    <div class="border border-gray-200 rounded-xl p-4">
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <h3 class="font-semibold text-gray-900">Invoice #{{ $transaction->invoice_number }}</h3>
                                <p class="text-sm text-gray-600">{{ $transaction->created_at->format('M j, Y H:i') }}</p>
                                @php
                                    $statusClasses = [
                                        'completed' => 'bg-emerald-100 text-emerald-800',
                                        'pending' => 'bg-amber-100 text-amber-800',
                                        'failed' => 'bg-rose-100 text-rose-800'
                                    ];
                                    $statusClass = $statusClasses[$transaction->payment_status] ?? $statusClasses['failed'];
                                @endphp
                                <span class="inline-block mt-1 px-2 py-1 text-xs rounded-full {{ $statusClass }}">
                                    {{ ucfirst($transaction->payment_status) }}
                                </span>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-gray-900">Rp
                                    {{ number_format($transaction->total_amount, 0, ',', '.') }}</p>
                                <p class="text-sm text-gray-600">
                                    {{ ucfirst(str_replace('_', ' ', $transaction->payment_method)) }}</p>
                            </div>
                        </div>

                        @if($transaction->items && $transaction->items->count() > 0)
                            <div class="border-t border-gray-200 pt-3">
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Course Items:</h4>
                                @foreach($transaction->items as $item)
                                    <div class="flex justify-between items-center py-1">
                                        <span class="text-sm text-gray-600">{{ $item->course->title ?? 'Course' }}</span>
                                        <span class="text-sm font-medium">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <div class="w-16 h-16 mx-auto mb-4 text-gray-300">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                            d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No purchases yet</h3>
                <p class="text-gray-500 mb-4">Your course purchase history will appear here</p>
                <button onclick="loadPage('courses')"
                    class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-primary/90">
                    Browse Courses
                </button>
            </div>
        @endif
    </div>
</div>