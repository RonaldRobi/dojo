<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-bold text-xl sm:text-2xl text-gray-900">
                Registration Payment
            </h2>
            <p class="text-xs sm:text-sm text-gray-600 mt-1">Complete payment to activate membership</p>
        </div>
    </x-slot>

    <div class="py-3 sm:py-6">
        <div class="max-w-3xl mx-auto px-3 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-3 rounded-lg" role="alert">
                    <div class="flex">
                        <svg class="h-5 w-5 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-sm font-semibold">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg overflow-hidden">
                <!-- Compact Header -->
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-4 py-5 sm:px-6 sm:py-6">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 sm:p-3 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm flex-shrink-0">
                            <svg class="h-6 w-6 sm:h-8 sm:w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg sm:text-xl font-bold text-white">Payment Required</h3>
                            <p class="text-xs sm:text-sm text-blue-100 mt-0.5 truncate">{{ $member->name }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-4 sm:p-6">
                    <!-- Compact Member Details -->
                    <div class="mb-4">
                        <h4 class="text-sm font-bold text-gray-900 mb-2 uppercase tracking-wide">Member Information</h4>
                        <div class="bg-gray-50 rounded-lg p-3 space-y-2 text-sm">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Name:</span>
                                <span class="font-semibold text-gray-900 truncate ml-2">{{ $member->name }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Email:</span>
                                <span class="font-medium text-gray-900 truncate ml-2 text-xs sm:text-sm">{{ $member->user->email }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Compact Payment Amount -->
                    <div class="mb-4">
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg p-4 border-2 border-blue-200">
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-xs text-gray-600">Invoice:</span>
                                <span class="font-mono text-xs font-semibold text-gray-900">{{ $invoice->invoice_number }}</span>
                            </div>
                            <div class="text-center py-3 border-t-2 border-blue-200">
                                <p class="text-xs text-gray-600 mb-1">Registration Fee</p>
                                <p class="text-3xl sm:text-4xl font-bold text-indigo-600">RM {{ number_format($invoice->total_amount, 0) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Channel Selection -->
                    <form method="POST" action="{{ route('parent.payment.create', $invoice->id) }}" x-data="{ selectedChannel: '1' }">
                        @csrf
                        
                        <div class="mb-4">
                            <h4 class="text-sm font-bold text-gray-900 mb-3 uppercase tracking-wide">Select Payment Method</h4>
                            <div class="space-y-2">
                                <!-- FPX (Online Banking) -->
                                <label class="relative flex items-center p-3 bg-white border-2 rounded-lg cursor-pointer transition-all hover:border-blue-400"
                                       :class="selectedChannel === '1' ? 'border-blue-500 bg-blue-50' : 'border-gray-200'">
                                    <input type="radio" name="payment_channel" value="1" x-model="selectedChannel" class="sr-only">
                                    <div class="flex items-center flex-1">
                                        <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                        <div class="ml-3 flex-1">
                                            <p class="text-sm font-bold text-gray-900">FPX Online Banking</p>
                                            <p class="text-xs text-gray-500 mt-0.5">Maybank, CIMB, Public Bank, etc.</p>
                                        </div>
                                        <div class="ml-2 flex-shrink-0">
                                            <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition-all"
                                                 :class="selectedChannel === '1' ? 'border-blue-500 bg-blue-500' : 'border-gray-300'">
                                                <svg x-show="selectedChannel === '1'" class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </label>

                                <!-- Direct Debit -->
                                <label class="relative flex items-center p-3 bg-white border-2 rounded-lg cursor-pointer transition-all hover:border-blue-400"
                                       :class="selectedChannel === '3' ? 'border-blue-500 bg-blue-50' : 'border-gray-200'">
                                    <input type="radio" name="payment_channel" value="3" x-model="selectedChannel" class="sr-only">
                                    <div class="flex items-center flex-1">
                                        <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                            </svg>
                                        </div>
                                        <div class="ml-3 flex-1">
                                            <p class="text-sm font-bold text-gray-900">Direct Debit</p>
                                            <p class="text-xs text-gray-500 mt-0.5">Auto debit from your bank account</p>
                                        </div>
                                        <div class="ml-2 flex-shrink-0">
                                            <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition-all"
                                                 :class="selectedChannel === '3' ? 'border-blue-500 bg-blue-500' : 'border-gray-300'">
                                                <svg x-show="selectedChannel === '3'" class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </label>

                                <!-- DuitNow QR -->
                                <label class="relative flex items-center p-3 bg-white border-2 rounded-lg cursor-pointer transition-all hover:border-blue-400"
                                       :class="selectedChannel === '6' ? 'border-blue-500 bg-blue-50' : 'border-gray-200'">
                                    <input type="radio" name="payment_channel" value="6" x-model="selectedChannel" class="sr-only">
                                    <div class="flex items-center flex-1">
                                        <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                                            </svg>
                                        </div>
                                        <div class="ml-3 flex-1">
                                            <p class="text-sm font-bold text-gray-900">DuitNow QR</p>
                                            <p class="text-xs text-gray-500 mt-0.5">Scan QR with any banking app</p>
                                        </div>
                                        <div class="ml-2 flex-shrink-0">
                                            <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition-all"
                                                 :class="selectedChannel === '6' ? 'border-blue-500 bg-blue-500' : 'border-gray-300'">
                                                <svg x-show="selectedChannel === '6'" class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Pay Now Button -->
                        <button type="submit" 
                                class="w-full inline-flex items-center justify-center px-6 py-3.5 sm:py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-lg sm:rounded-xl shadow-lg hover:shadow-xl active:scale-98 transform transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-sm sm:text-base">Proceed to Payment</span>
                        </button>
                    </form>

                    <!-- Compact Payment Info -->
                    <div class="mt-4 p-3 bg-blue-50 border-l-4 border-blue-400 rounded-lg">
                        <div class="flex">
                            <svg class="h-4 w-4 text-blue-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            <div class="text-xs text-blue-700">
                                <p class="font-semibold mb-1">Secure Payment</p>
                                <p>Powered by Bayar.cash. Your membership will be activated immediately after payment.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

