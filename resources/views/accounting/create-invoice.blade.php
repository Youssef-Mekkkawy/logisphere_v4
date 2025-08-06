@extends('layouts.app')

@section('title', 'Create Invoice - logisphere')
@section('page-title', 'Create New Invoice')

@section('content')
    <div class="create-invoice">
        <form action="{{ route('accounting.invoices.store') }}" method="POST" id="invoiceForm">
            @csrf

            <!-- Invoice Header -->
            <div class="invoice-header">
                <div class="form-section">
                    <h3>Invoice Details</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Company *</label>
                            <select name="company_id" class="form-input" required>
                                <option value="">Select Company</option>
                                @foreach ($companies as $company)
                                    <option value="{{ $company->id }}">{{ $company->name }} ({{ $company->type }})</option>
                                @endforeach
                            </select>
                            @error('company_id')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Related Shipment</label>
                            <select name="shipment_id" class="form-input">
                                <option value="">No specific shipment</option>
                                @foreach ($shipments as $shipment)
                                    <option value="{{ $shipment->id }}">{{ $shipment->shipment_id }} -
                                        {{ $shipment->company->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Invoice Type *</label>
                            <select name="type" class="form-input" required>
                                <option value="receivable">Receivable (Money we collect)</option>
                                <option value="payable">Payable (Money we pay)</option>
                            </select>
                            @error('type')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Invoice Date *</label>
                            <input type="date" name="invoice_date" class="form-input" value="{{ date('Y-m-d') }}"
                                required>
                            @error('invoice_date')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Due Date *</label>
                            <input type="date" name="due_date" class="form-input"
                                value="{{ date('Y-m-d', strtotime('+30 days')) }}" required>
                            @error('due_date')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Line Items -->
            <div class="invoice-items">
                <div class="items-header">
                    <h3>Invoice Items</h3>
                    <button type="button" class="btn btn-secondary" id="addItemBtn">
                        <span class="btn-icon">➕</span>
                        Add Item
                    </button>
                </div>

                <div class="items-table">
                    <table class="line-items-table">
                        <thead>
                            <tr>
                                <th>Description</th>
                                <th>Quantity</th>
                                <th>Rate</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="lineItemsBody">
                            <tr class="line-item">
                                <td>
                                    <input type="text" name="line_items[0][description]" class="form-input"
                                        placeholder="Service description" required>
                                </td>
                                <td>
                                    <input type="number" name="line_items[0][quantity]" class="form-input quantity"
                                        value="1" min="0.01" step="0.01" required>
                                </td>
                                <td>
                                    <input type="number" name="line_items[0][rate]" class="form-input rate" min="0"
                                        step="0.01" placeholder="0.00" required>
                                </td>
                                <td>
                                    <span class="item-amount">$0.00</span>
                                </td>
                                <td>
                                    <button type="button" class="btn-remove" onclick="removeLineItem(this)"
                                        disabled>🗑️</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Invoice Totals -->
                <div class="invoice-totals">
                    <div class="totals-grid">
                        <div class="total-row">
                            <span>Subtotal:</span>
                            <span id="subtotal">$0.00</span>
                        </div>
                        <div class="total-row">
                            <span>Tax (14%):</span>
                            <span id="taxAmount">$0.00</span>
                        </div>
                        <div class="total-row total-final">
                            <span>Total Amount:</span>
                            <span id="totalAmount">$0.00</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Notes -->
            <div class="invoice-notes">
                <div class="form-group">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-input" rows="3" placeholder="Additional notes or payment terms..."></textarea>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="{{ route('accounting.invoices') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <span class="btn-icon">💾</span>
                    Create Invoice
                </button>
            </div>
        </form>
    </div>

    <style>
        .create-invoice {
            max-width: 1000px;
            margin: 0 auto;
        }

        .invoice-header,
        .invoice-items,
        .invoice-notes {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .form-section h3,
        .items-header h3 {
            margin-bottom: 20px;
            color: #1f2937;
            border-bottom: 2px solid #f3f4f6;
            padding-bottom: 10px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }

        .form-input {
            padding: 12px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .error-message {
            color: #dc2626;
            font-size: 0.9em;
            margin-top: 5px;
        }

        .items-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .line-items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .line-items-table th {
            background: #f8fafc;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            color: #374151;
            border: 1px solid #e5e7eb;
        }

        .line-items-table td {
            padding: 12px;
            border: 1px solid #e5e7eb;
            vertical-align: middle;
        }

        .line-item .form-input {
            margin: 0;
            border: 1px solid #d1d5db;
        }

        .item-amount {
            font-weight: 600;
            color: #1f2937;
            text-align: right;
            display: block;
        }

        .btn-remove {
            background: #fecaca;
            color: #dc2626;
            border: none;
            padding: 8px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-remove:hover:not(:disabled) {
            background: #dc2626;
            color: white;
        }

        .btn-remove:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .invoice-totals {
            border-top: 2px solid #f3f4f6;
            padding-top: 20px;
        }

        .totals-grid {
            max-width: 300px;
            margin-left: auto;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .total-final {
            font-weight: bold;
            font-size: 1.2em;
            color: #1f2937;
            border-bottom: 2px solid #3b82f6;
            margin-top: 10px;
            padding-top: 15px;
        }

        .form-actions {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            margin-top: 20px;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: #3b82f6;
            color: white;
        }

        .btn-primary:hover {
            background: #2563eb;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: #f3f4f6;
            color: #6b7280;
        }

        .btn-secondary:hover {
            background: #e5e7eb;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let itemIndex = 1;

            // Add new line item
            document.getElementById('addItemBtn').addEventListener('click', function() {
                const tbody = document.getElementById('lineItemsBody');
                const newRow = createLineItemRow(itemIndex);
                tbody.appendChild(newRow);
                itemIndex++;
                updateRemoveButtons();
                calculateTotals();
            });

            // Calculate totals when inputs change
            document.addEventListener('input', function(e) {
                if (e.target.classList.contains('quantity') || e.target.classList.contains('rate')) {
                    updateLineItemAmount(e.target.closest('tr'));
                    calculateTotals();
                }
            });

            function createLineItemRow(index) {
                const row = document.createElement('tr');
                row.className = 'line-item';
                row.innerHTML = `
            <td>
                <input type="text" name="line_items[${index}][description]" class="form-input" placeholder="Service description" required>
            </td>
            <td>
                <input type="number" name="line_items[${index}][quantity]" class="form-input quantity" value="1" min="0.01" step="0.01" required>
            </td>
            <td>
                <input type="number" name="line_items[${index}][rate]" class="form-input rate" min="0" step="0.01" placeholder="0.00" required>
            </td>
            <td>
                <span class="item-amount">$0.00</span>
            </td>
            <td>
                <button type="button" class="btn-remove" onclick="removeLineItem(this)">🗑️</button>
            </td>
        `;
                return row;
            }

            function updateLineItemAmount(row) {
                const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
                const rate = parseFloat(row.querySelector('.rate').value) || 0;
                const amount = quantity * rate;
                row.querySelector('.item-amount').textContent = formatCurrency(amount);
            }

            function calculateTotals() {
                let subtotal = 0;
                document.querySelectorAll('.line-item').forEach(row => {
                    const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
                    const rate = parseFloat(row.querySelector('.rate').value) || 0;
                    subtotal += quantity * rate;
                });

                const taxRate = 0.14; // 14% tax
                const taxAmount = subtotal * taxRate;
                const totalAmount = subtotal + taxAmount;

                document.getElementById('subtotal').textContent = formatCurrency(subtotal);
                document.getElementById('taxAmount').textContent = formatCurrency(taxAmount);
                document.getElementById('totalAmount').textContent = formatCurrency(totalAmount);
            }

            function updateRemoveButtons() {
                const removeButtons = document.querySelectorAll('.btn-remove');
                removeButtons.forEach((btn, index) => {
                    btn.disabled = removeButtons.length === 1;
                });
            }

            function formatCurrency(amount) {
                return new Intl.NumberFormat('en-US', {
                    style: 'currency',
                    currency: 'USD'
                }).format(amount);
            }

            // Initialize calculations
            calculateTotals();
            updateRemoveButtons();
        });

        function removeLineItem(button) {
            const row = button.closest('tr');
            row.remove();

            // Update remove buttons state
            const removeButtons = document.querySelectorAll('.btn-remove');
            removeButtons.forEach((btn, index) => {
                btn.disabled = removeButtons.length === 1;
            });

            // Recalculate totals
            const event = new Event('input', {
                bubbles: true
            });
            document.dispatchEvent(event);
        }
    </script>
@endsection
