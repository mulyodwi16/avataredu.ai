@extends('layouts.user-dashboard')

@section('title', 'Purchase History')
@section('page-title', 'Purchase History')

@section('content')
    <div class="space-y-6">
        {{-- Purchase History Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Purchase History</h2>
                <p class="text-gray-500">Track all your course purchases and transactions</p>
            </div>
            <div class="flex items-center gap-3">
                <select
                    class="px-4 py-2 rounded-lg border border-gray-200 bg-white focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    <option>Last 30 days</option>
                    <option>Last 3 months</option>
                    <option>Last 6 months</option>
                    <option>Last year</option>
                    <option>All time</option>
                </select>
                <button class="px-4 py-2 rounded-lg border border-gray-200 hover:bg-gray-50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Purchase Stats --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @php
                $stats = [
                    ['label' => 'Total Spent', 'value' => 'Rp 1.250.000', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['label' => 'Courses Bought', 'value' => '8', 'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
                    ['label' => 'Active Courses', 'value' => '5', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                    ['label' => 'Completed', 'value' => '3', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                ];
            @endphp

            @foreach($stats as $stat)
                <div class="bg-white p-4 rounded-xl shadow">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-primary/10 grid place-items-center">
                            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat['icon'] }}" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-xl font-bold text-gray-800">{{ $stat['value'] }}</div>
                            <div class="text-sm text-gray-500">{{ $stat['label'] }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Recent Transactions --}}
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="p-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800">Recent Transactions</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 text-left">
                            <th class="px-4 py-3 text-sm font-medium text-gray-600">Course</th>
                            <th class="px-4 py-3 text-sm font-medium text-gray-600">Date</th>
                            <th class="px-4 py-3 text-sm font-medium text-gray-600">Amount</th>
                            <th class="px-4 py-3 text-sm font-medium text-gray-600">Status</th>
                            <th class="px-4 py-3 text-sm font-medium text-gray-600">Invoice</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @php
                            $transactions = [
                                [
                                    'course' => 'Advanced Web Development',
                                    'date' => '2025-09-27',
                                    'amount' => 450000,
                                    'status' => 'Completed',
                                    'invoice' => 'INV-2025-001'
                                ],
                                [
                                    'course' => 'UI/UX Design Fundamentals',
                                    'date' => '2025-09-25',
                                    'amount' => 350000,
                                    'status' => 'Completed',
                                    'invoice' => 'INV-2025-002'
                                ],
                                [
                                    'course' => 'Python for Data Science',
                                    'date' => '2025-09-20',
                                    'amount' => 450000,
                                    'status' => 'Completed',
                                    'invoice' => 'INV-2025-003'
                                ],
                                [
                                    'course' => 'Digital Marketing Strategy',
                                    'date' => '2025-09-15',
                                    'amount' => 350000,
                                    'status' => 'Refunded',
                                    'invoice' => 'INV-2025-004'
                                ],
                                [
                                    'course' => 'Mobile App Development',
                                    'date' => '2025-09-10',
                                    'amount' => 450000,
                                    'status' => 'Completed',
                                    'invoice' => 'INV-2025-005'
                                ]
                            ];
                        @endphp

                        @foreach($transactions as $transaction)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded bg-primary/10 grid place-items-center">
                                            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-800">{{ $transaction['course'] }}</div>
                                            <div class="text-sm text-gray-500">Course Purchase</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-gray-500">
                                    {{ \Carbon\Carbon::parse($transaction['date'])->format('d M Y') }}
                                </td>
                                <td
                                    class="px-4 py-3 font-medium 
                                                            {{ $transaction['status'] === 'Refunded' ? 'text-red-600' : 'text-gray-800' }}">
                                    Rp {{ number_format($transaction['amount'], 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3">
                                    <span
                                        class="inline-block px-2 py-0.5 rounded-full text-xs font-medium
                                                                {{ $transaction['status'] === 'Completed' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $transaction['status'] }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <a href="#" class="text-primary hover:text-primaryDark font-medium">
                                        {{ $transaction['invoice'] }}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="flex items-center justify-between border-t border-gray-100 px-4 py-3">
                <div class="text-sm text-gray-500">
                    Showing 1 to 5 of 12 entries
                </div>
                <div class="flex items-center gap-1">
                    <button
                        class="w-8 h-8 grid place-items-center rounded border border-gray-200 hover:bg-gray-50 disabled:opacity-50"
                        disabled>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <button
                        class="w-8 h-8 grid place-items-center rounded border border-primary bg-primary text-white">1</button>
                    <button
                        class="w-8 h-8 grid place-items-center rounded border border-gray-200 hover:bg-gray-50">2</button>
                    <button
                        class="w-8 h-8 grid place-items-center rounded border border-gray-200 hover:bg-gray-50">3</button>
                    <button class="w-8 h-8 grid place-items-center rounded border border-gray-200 hover:bg-gray-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
@endsection