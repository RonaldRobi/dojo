<x-app-layout title="Edit Member">
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('owner.members.show', $member) }}" class="text-gray-600 hover:text-gray-900">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Member') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-gray-900">Edit Member Information</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('owner.members.update', $member) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div class="md:col-span-2">
                                <label for="name" class="form-label">Full Name <span class="text-red-500">*</span></label>
                                <input type="text" name="name" id="name" value="{{ old('name', $member->name) }}" required
                                       class="form-input @error('name') border-red-500 @enderror">
                                @error('name')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Birth Date -->
                            <div>
                                <label for="birth_date" class="form-label">Birth Date</label>
                                <input type="date" name="birth_date" id="birth_date" 
                                       value="{{ old('birth_date', $member->birth_date ? $member->birth_date->format('Y-m-d') : '') }}"
                                       class="form-input @error('birth_date') border-red-500 @enderror">
                                @error('birth_date')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Gender -->
                            <div>
                                <label for="gender" class="form-label">Gender</label>
                                <select name="gender" id="gender" class="form-input @error('gender') border-red-500 @enderror">
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('gender', $member->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender', $member->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender', $member->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('gender')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div>
                                <label for="phone" class="form-label">Phone</label>
                                <input type="tel" name="phone" id="phone" value="{{ old('phone', $member->phone) }}"
                                       class="form-input @error('phone') border-red-500 @enderror">
                                @error('phone')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-input @error('status') border-red-500 @enderror">
                                    <option value="active" {{ old('status', $member->status) == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="leave" {{ old('status', $member->status) == 'leave' ? 'selected' : '' }}>Leave</option>
                                    <option value="inactive" {{ old('status', $member->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Address -->
                            <div class="md:col-span-2">
                                <label for="address" class="form-label">Address</label>
                                <textarea name="address" id="address" rows="3"
                                          class="form-input @error('address') border-red-500 @enderror">{{ old('address', $member->address) }}</textarea>
                                @error('address')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Style -->
                            <div>
                                <label for="style" class="form-label">Style</label>
                                <input type="text" name="style" id="style" value="{{ old('style', $member->style) }}"
                                       placeholder="e.g., Karate, Taekwondo"
                                       class="form-input @error('style') border-red-500 @enderror">
                                @error('style')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Medical Notes -->
                            <div class="md:col-span-2">
                                <label for="medical_notes" class="form-label">Medical Notes</label>
                                <textarea name="medical_notes" id="medical_notes" rows="3"
                                          class="form-input @error('medical_notes') border-red-500 @enderror"
                                          placeholder="Any medical conditions or allergies...">{{ old('medical_notes', $member->medical_notes) }}</textarea>
                                @error('medical_notes')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="mt-8 flex justify-end space-x-3">
                            <a href="{{ route('owner.members.show', $member) }}" class="btn btn-outline">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                Update Member
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

