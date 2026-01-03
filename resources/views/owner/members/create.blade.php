<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Add New Member') }}
            </h2>
            <a href="{{ route('owner.members.index') }}" class="btn btn-outline">
                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto">
            <x-card>
                <form method="POST" action="{{ route('owner.members.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div>
                            <label for="name" class="form-label">Full Name *</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required class="form-input @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="form-input @error('phone') border-red-500 @enderror">
                            @error('phone')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email (via user_id) -->
                        <div>
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-input @error('email') border-red-500 @enderror">
                            <p class="mt-1 text-sm text-gray-500">If provided, a user account will be created</p>
                            @error('email')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Birth Date -->
                        <div>
                            <label for="birth_date" class="form-label">Birth Date</label>
                            <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date') }}" class="form-input @error('birth_date') border-red-500 @enderror">
                            @error('birth_date')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Gender -->
                        <div>
                            <label for="gender" class="form-label">Gender</label>
                            <select name="gender" id="gender" class="form-input @error('gender') border-red-500 @enderror">
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('gender')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="form-label">Status *</label>
                            <select name="status" id="status" required class="form-input @error('status') border-red-500 @enderror">
                                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="leave" {{ old('status') == 'leave' ? 'selected' : '' }}>Leave</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Join Date -->
                        <div>
                            <label for="join_date" class="form-label">Join Date *</label>
                            <input type="date" name="join_date" id="join_date" value="{{ old('join_date', date('Y-m-d')) }}" required class="form-input @error('join_date') border-red-500 @enderror">
                            @error('join_date')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Style -->
                        <div>
                            <label for="style" class="form-label">Style</label>
                            <input type="text" name="style" id="style" value="{{ old('style') }}" class="form-input @error('style') border-red-500 @enderror">
                            @error('style')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div class="md:col-span-2">
                            <label for="address" class="form-label">Address</label>
                            <textarea name="address" id="address" rows="3" class="form-input @error('address') border-red-500 @enderror">{{ old('address') }}</textarea>
                            @error('address')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Medical Notes -->
                        <div class="md:col-span-2">
                            <label for="medical_notes" class="form-label">Medical Notes</label>
                            <textarea name="medical_notes" id="medical_notes" rows="3" class="form-input @error('medical_notes') border-red-500 @enderror" placeholder="Any medical conditions or allergies...">{{ old('medical_notes') }}</textarea>
                            @error('medical_notes')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <a href="{{ route('owner.members.index') }}" class="btn btn-outline">Cancel</a>
                        <button type="submit" class="btn btn-primary">Create Member</button>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
</x-app-layout>

