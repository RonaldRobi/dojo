<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('owner.invoices.index') }}" class="text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Invoice Details</h2>
                <p class="text-sm text-gray-600 mt-1">{{ $invoice->invoice_number }}</p>
            </div>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Invoice Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Header -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $invoice->invoice_number }}</h3>
                        <p class="text-sm text-gray-500 mt-1">Created: {{ \Carbon\Carbon::parse($invoice->created_at)->format('d M Y, H:i') }}</p>
                    </div>
                    <div>
                        @if($invoice->status === 'paid')
                            <span class="px-4 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-800">Paid</span>
                        @elseif($invoice->status === 'pending')
                            <span class="px-4 py-2 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800">Pending</span>
                        @elseif($invoice->status === 'overdue')
                            <span class="px-4 py-2 rounded-full text-sm font-semibold bg-red-100 text-red-800">Overdue</span>
                        @else
                            <span class="px-4 py-2 rounded-full text-sm font-semibold bg-gray-100 text-gray-800">{{ ucfirst($invoice->status) }}</span>
                        @endif
                    </div>
                </div>

                <!-- Member Info -->
                <div class="border-t border-gray-200 pt-6">
                    <h4 class="text-sm font-semibold text-gray-500 uppercase mb-3">Bill To</h4>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                            <span class="text-purple-600 font-semibold">{{ substr($invoice->member->name ?? 'N', 0, 2) }}</span>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">{{ $invoice->member->name ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-500">{{ $invoice->member->user->email ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-500">{{ $invoice->member->phone ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Invoice Items -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">Invoice Details</h4>
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-3 border-b border-gray-200">
                        <div>
                            <p class="font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $invoice->type)) }}</p>
                            <p class="text-sm text-gray-500 mt-1">{{ $invoice->notes ?? 'No description' }}</p>
                        </div>
                        <p class="text-xl font-bold text-gray-900">RM {{ number_format($invoice->amount, 0) }}</p>
                    </div>
                </div>

                <!-- Total -->
                <div class="mt-6 pt-6 border-t-2 border-gray-300">
                    <div class="flex justify-between items-center">
                        <p class="text-lg font-semibold text-gray-900">Total Amount</p>
                        <p class="text-2xl font-bold text-purple-600">RM {{ number_format($invoice->amount, 0) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Payment Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">Payment Information</h4>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-gray-500">Amount</p>
                        <p class="font-semibold text-gray-900">RM {{ number_format($invoice->amount, 0) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Due Date</p>
                        <p class="font-semibold text-gray-900">{{ $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') : 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Status</p>
                        <p class="font-semibold text-gray-900">{{ ucfirst($invoice->status) }}</p>
                    </div>
                    @if($invoice->paid_at)
                    <div>
                        <p class="text-xs text-gray-500">Paid At</p>
                        <p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($invoice->paid_at)->format('d M Y, H:i') }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">Actions</h4>
                <div class="space-y-3">
                    @if($invoice->status === 'pending')
                        <a href="{{ route('owner.invoices.edit', $invoice) }}" class="block w-full px-4 py-2 bg-blue-600 text-white text-center rounded-lg hover:bg-blue-700 transition-colors">
                            Edit Invoice
                        </a>
                        <form action="{{ route('owner.invoices.destroy', $invoice) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this invoice?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                Delete Invoice
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('owner.invoices.index') }}" class="block w-full px-4 py-2 bg-gray-200 text-gray-700 text-center rounded-lg hover:bg-gray-300 transition-colors">
                        Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

