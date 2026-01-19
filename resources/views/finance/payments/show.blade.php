<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Payment Details</h2>
                <p class="text-sm text-gray-600 mt-1">Payment Information</p>
            </div>
            <div class="flex space-x-3">
                @if(!$payment->verified_by_user_id)
                    <form action="{{ route('finance.payments.verify', $payment) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                            Verify Payment
                        </button>
                    </form>
                @endif
                <a href="{{ route('finance.payments.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <dt class="text-sm font-medium text-gray-500">Amount</dt>
                <dd class="mt-1 text-2xl font-bold text-gray-900">RM {{ number_format($payment->amount, 0) }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Payment Date</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $payment->payment_date->format('M d, Y') }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Payment Method</dt>
                <dd class="mt-1 text-sm text-gray-900 capitalize">{{ $payment->payment_method }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Status</dt>
                <dd class="mt-1">
                    @if($payment->verified_by_user_id)
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Verified</span>
                    @else
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending Verification</span>
                    @endif
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Invoice</dt>
                <dd class="mt-1 text-sm text-gray-900">#{{ $payment->invoice->invoice_number }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Member</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $payment->invoice->member->name ?? 'N/A' }}</dd>
            </div>
            @if($payment->payment_reference)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Payment Reference</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $payment->payment_reference }}</dd>
                </div>
            @endif
            @if($payment->notes)
                <div class="md:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Notes</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $payment->notes }}</dd>
                </div>
            @endif
        </dl>
    </div>
</x-app-layout>

