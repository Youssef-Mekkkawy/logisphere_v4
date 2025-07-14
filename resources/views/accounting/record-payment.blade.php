@extends('layouts.app')

@section('title', 'Record Payment - LogiFlow')
@section('page-title', 'Record Payment')

@section('content')
    <div class="record-payment">
        <form action="{{ route('accounting.payments.store') }}" method="POST" id="paymentForm">
            @csrf

            <!-- Payment Details -->
            <div class="payment-form">
                <div class="form-section">
                    <h3>Payment Information</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Invoice *</label>
                            <select name="invoice_id" class="form-input" id="invoiceSelect" required>
                                <option value="">Select Invoice</option>
                                @foreach ($unpaidInvoices as $invoice)
                                    <option value="{{ $invoice->id }}" data-amount="{{ $invoice->balance_due }}"
                                        data-company="{{ $invoice->company->name }}"
                                        {{ request('invoice') == $invoice->id ? 'selected' : '' }}>
                                        {{ $invoice->invoice_number }} - {{ $invoice->company->name }}
                                        (${{ number_format($invoice->balance_due, 2) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('invoice_id')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Payment Amount *</label>
                            <div class="amount-input">
                                <span class="currency-symbol">$</span>
                                <input type="number" name="amount" id="paymentAmount" class="form-input" min="0.01"
                                    step="0.01" placeholder="0.00" required>
                                <div class="amount-suggestions">
                                    <button type="button" class="amount-btn" id="fullAmountBtn" style="display: none;">Pay
                                        Full Amount</button>
                                </div>
                            </div>
                            @error('amount')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Payment Method *</label>
                            <select name="method" class="form-input" required>
                                <option value="">Select Method</option>
                                <option value="cash">Cash</option>
                                <option value="check">Check</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="credit_card">Credit Card</option>
                                <option value="other">Other</option>
                            </select>
                            @error('method')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Payment Date *</label>
                            <input type="date" name="payment_date" class="form-input" value="{{ date('Y-m-d') }}"
                                required>
                            @error('payment_date')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Reference Number</label>
                            <input type="text" name="reference_number" class="form-input"
                                placeholder="Check #, Transaction ID, etc.">
                        </div>

                        <div class="form-group full-width">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-input" rows="3" placeholder="Payment notes..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- Payment Summary -->
                <div class="payment-summary" id="paymentSummary" style="display: none;">
                    <h4>Payment Summary</h4>
                    <div class="summary-details">
                        <div class="summary-row">
                            <span>Invoice:</span>
                            <span id="summaryInvoice">-</span>
                        </div>
                        <div class="summary-row">
                            <span>Company:</span>
                            <span id="summaryCompany">-</span>
                        </div>
                        <div class="summary-row">
                            <span>Outstanding Balance:</span>
                            <span id="summaryBalance">$0.00</span>
                        </div>
                        <div class="summary-row">
                            <span>Payment Amount:</span>
                            <span id="summaryAmount">$0.00</span>
                        </div>
                        <div class="summary-row total">
                            <span>Remaining Balance:</span>
                            <span id="summaryRemaining">$0.00</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="{{ route('accounting.payments') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <span class="btn-icon">💾</span>
                    Record Payment
                </button>
            </div>
        </form>
    </div>

    <style>
        .record-payment {
            max-width: 800px;
            margin: 0 auto;
        }

        .payment-form {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .amount-suggestions {
            margin-top: 8px;
        }

        .amount-btn {
            background: #3b82f6;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.8em;
            cursor: pointer;
        }

        .payment-summary {
            background: #f8fafc;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
            border-left: 4px solid #3b82f6;
        }

        .payment-summary h4 {
            margin-bottom: 15px;
            color: #1f2937;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .summary-row.total {
            font-weight: bold;
            border-top: 2px solid #3b82f6;
            margin-top: 10px;
            padding-top: 15px;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const invoiceSelect = document.getElementById('invoiceSelect');
            const paymentAmount = document.getElementById('paymentAmount');
            const fullAmountBtn = document.getElementById('fullAmountBtn');
            const paymentSummary = document.getElementById('paymentSummary');

            let currentInvoiceBalance = 0;

            invoiceSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption.value) {
                    const balance = parseFloat(selectedOption.dataset.amount);
                    const company = selectedOption.dataset.company;
                    const invoiceNumber = selectedOption.text.split(' - ')[0];

                    currentInvoiceBalance = balance;
                    fullAmountBtn.style.display = 'inline-block';
                    paymentSummary.style.display = 'block';

                    document.getElementById('summaryInvoice').textContent = invoiceNumber;
                    document.getElementById('summaryCompany').textContent = company;
                    document.getElementById('summaryBalance').textContent = '$' + balance.toFixed(2);

                    updateSummary();
                } else {
                    fullAmountBtn.style.display = 'none';
                    paymentSummary.style.display = 'none';
                    currentInvoiceBalance = 0;
                }
            });

            fullAmountBtn.addEventListener('click', function() {
                paymentAmount.value = currentInvoiceBalance.toFixed(2);
                updateSummary();
            });

            paymentAmount.addEventListener('input', updateSummary);

            function updateSummary() {
                const amount = parseFloat(paymentAmount.value) || 0;
                const remaining = currentInvoiceBalance - amount;

                document.getElementById('summaryAmount').textContent = '$' + amount.toFixed(2);
                document.getElementById('summaryRemaining').textContent = '$' + remaining.toFixed(2);

                // Color coding for remaining balance
                const remainingElement = document.getElementById('summaryRemaining');
                remainingElement.style.color = remaining <= 0 ? '#16a34a' : '#dc2626';
            }
        });
    </script>
@endsection
