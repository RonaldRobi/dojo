<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment Status - Droplets Dojo</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <!-- Simple Header -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="h-10 w-10 rounded-xl flex items-center justify-center overflow-hidden bg-gradient-to-br from-purple-500 to-blue-600">
                        <span class="text-white font-bold text-lg">D</span>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">Droplets Dojo</h1>
                        <p class="text-xs text-gray-500">Payment Confirmation</p>
                    </div>
                </div>
                @auth
                <a href="{{ route('parent.dashboard') }}" class="text-sm text-blue-600 hover:text-blue-800">
                    Go to Dashboard →
                </a>
                @else
                <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:text-blue-800">
                    Login
                </a>
                @endauth
            </div>
        </div>
    </header>

    <div class="py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="p-8 text-center">
                    @if($invoice->status === 'paid')
                        <!-- Success State -->
                        <div class="mb-6">
                            <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-green-100 mb-4">
                                <svg class="h-12 w-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">Payment Successful!</h3>
                            <p class="text-gray-600">Thank you for your payment. The membership has been activated.</p>
                        </div>

                        <div class="bg-green-50 rounded-xl p-6 mb-6">
                            <div class="space-y-3 text-left">
                                <div class="flex justify-between">
                                    <span class="text-gray-700">Invoice Number:</span>
                                    <span class="font-semibold text-gray-900">{{ $invoice->invoice_number }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-700">Amount Paid:</span>
                                    <span class="font-semibold text-green-600">RM {{ number_format($invoice->total_amount, 0) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-700">Payment Date:</span>
                                    <span class="font-semibold text-gray-900">{{ $invoice->paid_at ? $invoice->paid_at->format('d M Y, h:i A') : '-' }}</span>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('parent.dashboard') }}" 
                           class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                            Go to Dashboard
                            <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>

                    @elseif($invoice->status === 'failed')
                        <!-- Failed State -->
                        <div class="mb-6">
                            <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-red-100 mb-4">
                                <svg class="h-12 w-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">Payment Failed</h3>
                            <p class="text-gray-600">Unfortunately, your payment could not be processed.</p>
                        </div>

                        <div class="bg-red-50 rounded-xl p-6 mb-6">
                            <p class="text-red-700">Please try again or contact support if the problem persists.</p>
                        </div>

                        <div class="flex gap-4 justify-center">
                            <a href="{{ route('parent.payment.registration', $invoice->member_id) }}" 
                               class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                                Try Again
                            </a>
                            <a href="{{ route('parent.dashboard') }}" 
                               class="inline-flex items-center px-8 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition-all duration-200">
                                Back to Dashboard
                            </a>
                        </div>

                    @else
                        <!-- Pending State -->
                        <div class="mb-6">
                            <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-yellow-100 mb-4">
                                <svg class="h-12 w-12 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">Payment Processing</h3>
                            <p class="text-gray-600">Your payment is being processed. This may take a few moments.</p>
                        </div>

                        <div class="bg-yellow-50 rounded-xl p-6 mb-6">
                            <p class="text-yellow-700">Please check your payment status in your dashboard shortly.</p>
                        </div>

                        <a href="{{ route('parent.dashboard') }}" 
                           class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                            Go to Dashboard
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Simple Footer -->
    <footer class="bg-white border-t mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <p class="text-center text-sm text-gray-500">
                © {{ date('Y') }} Droplets Dojo. All rights reserved.
            </p>
        </div>
    </footer>
</body>
</html>

