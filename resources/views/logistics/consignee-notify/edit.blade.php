@extends('layouts.app')

@section('title', 'Edit Consignee/Notify Party')

@section('content')
    <div style="margin-bottom: 2rem;">
        <h1 style="font-size: 1.875rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">Edit Consignee/Notify Party
        </h1>
        <p style="color: #64748b;">Update party information - {{ $consigneeNotify->party_name }}</p>
    </div>

    <form action="{{ route('logistics.consignee-notify.update', $consigneeNotify) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Basic Information -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3>📋 Basic Information</h3>
            </div>
            <div class="card-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Party Code *</label>
                        <input type="text" name="party_code" class="form-input" required
                            value="{{ old('party_code', $consigneeNotify->party_code) }}" placeholder="e.g., CNS001">
                        @error('party_code')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Party Name *</label>
                        <input type="text" name="party_name" class="form-input" required
                            value="{{ old('party_name', $consigneeNotify->party_name) }}"
                            placeholder="Company or individual name">
                        @error('party_name')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Party Type *</label>
                        <select name="party_type" class="form-input" required>
                            <option value="">Select Party Type</option>
                            <option value="Consignee"
                                {{ old('party_type', $consigneeNotify->party_type) == 'Consignee' ? 'selected' : '' }}>
                                Consignee</option>
                            <option value="Notify Party"
                                {{ old('party_type', $consigneeNotify->party_type) == 'Notify Party' ? 'selected' : '' }}>
                                Notify Party</option>
                            <option value="Both"
                                {{ old('party_type', $consigneeNotify->party_type) == 'Both' ? 'selected' : '' }}>Both
                            </option>
                        </select>
                        @error('party_type')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Company Registration</label>
                        <input type="text" name="company_registration" class="form-input"
                            value="{{ old('company_registration', $consigneeNotify->company_registration) }}"
                            placeholder="Registration number">
                        @error('company_registration')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tax ID</label>
                        <input type="text" name="tax_id" class="form-input"
                            value="{{ old('tax_id', $consigneeNotify->tax_id) }}" placeholder="Tax identification number">
                        @error('tax_id')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Status *</label>
                        <select name="status" class="form-input" required>
                            <option value="Active"
                                {{ old('status', $consigneeNotify->status) == 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="Inactive"
                                {{ old('status', $consigneeNotify->status) == 'Inactive' ? 'selected' : '' }}>Inactive
                            </option>
                        </select>
                        @error('status')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3>📞 Contact Information</h3>
            </div>
            <div class="card-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Contact Person *</label>
                        <input type="text" name="contact_person" class="form-input" required
                            value="{{ old('contact_person', $consigneeNotify->contact_person) }}" placeholder="Full name">
                        @error('contact_person')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" class="form-input" required
                            value="{{ old('email', $consigneeNotify->email) }}" placeholder="email@example.com">
                        @error('email')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Phone *</label>
                        <input type="text" name="phone" class="form-input" required
                            value="{{ old('phone', $consigneeNotify->phone) }}" placeholder="+1 234 567 8900">
                        @error('phone')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Fax</label>
                        <input type="text" name="fax" class="form-input"
                            value="{{ old('fax', $consigneeNotify->fax) }}" placeholder="+1 234 567 8901">
                        @error('fax')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Preferred Language *</label>
                        <select name="preferred_language" class="form-input" required>
                            <option value="en"
                                {{ old('preferred_language', $consigneeNotify->preferred_language) == 'en' ? 'selected' : '' }}>
                                English</option>
                            <option value="ar"
                                {{ old('preferred_language', $consigneeNotify->preferred_language) == 'ar' ? 'selected' : '' }}>
                                Arabic</option>
                            <option value="fr"
                                {{ old('preferred_language', $consigneeNotify->preferred_language) == 'fr' ? 'selected' : '' }}>
                                French</option>
                            <option value="es"
                                {{ old('preferred_language', $consigneeNotify->preferred_language) == 'es' ? 'selected' : '' }}>
                                Spanish</option>
                        </select>
                        @error('preferred_language')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group" style="margin-top: 1.5rem;">
                    <label class="form-label">Notification Preferences</label>
                    <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                        @php
                            $currentPrefs = old(
                                'notification_preferences',
                                $consigneeNotify->notification_preferences ?? [],
                            );
                        @endphp
                        <label style="display: flex; align-items: center; gap: 0.5rem;">
                            <input type="checkbox" name="notification_preferences[]" value="email"
                                {{ in_array('email', $currentPrefs) ? 'checked' : '' }}>
                            Email Notifications
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.5rem;">
                            <input type="checkbox" name="notification_preferences[]" value="sms"
                                {{ in_array('sms', $currentPrefs) ? 'checked' : '' }}>
                            SMS Notifications
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.5rem;">
                            <input type="checkbox" name="notification_preferences[]" value="phone"
                                {{ in_array('phone', $currentPrefs) ? 'checked' : '' }}>
                            Phone Notifications
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Address Information -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3>📍 Address Information</h3>
            </div>
            <div class="card-body">
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="form-label">Address *</label>
                    <textarea name="address" class="form-input" required rows="3"
                        placeholder="Street address, building number, etc.">{{ old('address', $consigneeNotify->address) }}</textarea>
                    @error('address')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">City *</label>
                        <input type="text" name="city" class="form-input" required
                            value="{{ old('city', $consigneeNotify->city) }}" placeholder="City name">
                        @error('city')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">State/Province</label>
                        <input type="text" name="state_province" class="form-input"
                            value="{{ old('state_province', $consigneeNotify->state_province) }}"
                            placeholder="State or province">
                        @error('state_province')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Country *</label>
                        <select name="country_id" class="form-input" required>
                            <option value="">Select Country</option>
                            @foreach ($countries as $country)
                                <option value="{{ $country->id }}"
                                    {{ old('country_id', $consigneeNotify->country_id) == $country->id ? 'selected' : '' }}>
                                    {{ $country->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('country_id')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Postal Code</label>
                        <input type="text" name="postal_code" class="form-input"
                            value="{{ old('postal_code', $consigneeNotify->postal_code) }}"
                            placeholder="Postal/ZIP code">
                        @error('postal_code')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                </div>
            </div>
        </div>

        <!-- Business Settings -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3>💼 Business Settings</h3>
            </div>
            <div class="card-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Credit Rating</label>
                        <select name="credit_rating" class="form-input">
                            <option value="">Select Rating</option>
                            <option value="A"
                                {{ old('credit_rating', $consigneeNotify->credit_rating) == 'A' ? 'selected' : '' }}>A -
                                Excellent</option>
                            <option value="B"
                                {{ old('credit_rating', $consigneeNotify->credit_rating) == 'B' ? 'selected' : '' }}>B -
                                Good</option>
                            <option value="C"
                                {{ old('credit_rating', $consigneeNotify->credit_rating) == 'C' ? 'selected' : '' }}>C -
                                Fair</option>
                            <option value="D"
                                {{ old('credit_rating', $consigneeNotify->credit_rating) == 'D' ? 'selected' : '' }}>D -
                                Poor</option>
                        </select>
                        @error('credit_rating')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Credit Limit</label>
                        <input type="number" name="credit_limit" class="form-input" step="0.01" min="0"
                            value="{{ old('credit_limit', $consigneeNotify->credit_limit) }}" placeholder="0.00">
                        @error('credit_limit')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Payment Terms</label>
                        <input type="text" name="payment_terms" class="form-input"
                            value="{{ old('payment_terms', $consigneeNotify->payment_terms) }}"
                            placeholder="e.g., Net 30 days">
                        @error('payment_terms')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div
                    style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-top: 1.5rem;">
                    <div class="form-group">
                        <label style="display: flex; align-items: center; gap: 0.5rem;">
                            <input type="checkbox" name="requires_original_docs" value="1"
                                {{ old('requires_original_docs', $consigneeNotify->requires_original_docs) ? 'checked' : '' }}>
                            Requires Original Documents
                        </label>
                    </div>

                    <div class="form-group">
                        <label style="display: flex; align-items: center; gap: 0.5rem;">
                            <input type="checkbox" name="is_freight_forwarder" value="1"
                                {{ old('is_freight_forwarder', $consigneeNotify->is_freight_forwarder) ? 'checked' : '' }}>
                            Is Freight Forwarder
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Special Instructions -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3>📝 Special Instructions & Notes</h3>
            </div>
            <div class="card-body">
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="form-label">Delivery Instructions</label>
                    <textarea name="delivery_instructions" class="form-input" rows="3"
                        placeholder="Special delivery instructions...">{{ old('delivery_instructions', $consigneeNotify->delivery_instructions) }}</textarea>
                    @error('delivery_instructions')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="form-label">Special Requirements</label>
                    <textarea name="special_requirements" class="form-input" rows="3"
                        placeholder="Any special handling requirements...">{{ old('special_requirements', $consigneeNotify->special_requirements) }}</textarea>
                    @error('special_requirements')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-input" rows="3" placeholder="Additional notes...">{{ old('notes', $consigneeNotify->notes) }}</textarea>
                    @error('notes')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div style="display: flex; gap: 1rem; justify-content: flex-end;">
            <a href="{{ route('logistics.consignee-notify.show', $consigneeNotify) }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Update Party</button>
        </div>
    </form>
@endsection
