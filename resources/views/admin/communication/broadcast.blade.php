<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Broadcast</h2>
            <p class="text-sm text-gray-500 mt-1">Send broadcast messages to all branches</p>
        </div>
    </x-slot>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-white">
            <h3 class="text-lg font-semibold text-gray-900">Send Broadcast</h3>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('admin.communication.broadcast.send') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                    <input type="text" name="subject" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Message *</label>
                    <textarea name="message" rows="6" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"></textarea>
                </div>
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="send_to_all" value="1" checked class="rounded border-gray-300 text-purple-600">
                        <span class="ml-2 text-sm text-gray-700">Send to all dojos</span>
                    </label>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Or select specific dojos</label>
                    <div class="grid grid-cols-3 gap-2 max-h-40 overflow-y-auto border border-gray-200 rounded-lg p-2">
                        @foreach($dojos as $dojo)
                            <label class="flex items-center">
                                <input type="checkbox" name="dojo_ids[]" value="{{ $dojo->id }}" class="rounded border-gray-300 text-purple-600">
                                <span class="ml-2 text-sm text-gray-700">{{ $dojo->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">Send Broadcast</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

