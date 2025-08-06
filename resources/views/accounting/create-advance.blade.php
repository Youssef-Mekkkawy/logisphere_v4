@extends('layouts.app')

@section('title', 'Issue Advance - logisphere')
@section('page-title', 'Issue Employee Advance')

@section('content')
    <div class="create-advance">
        <form action="{{ route('accounting.advances.store') }}" method="POST" id="advanceForm">
            @csrf

            <!-- Advance Details -->
            <div class="advance-form">
                <div class="form-section">
                    <h3>Advance Information</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Employee *</label>
                            <select name="employee_id" class="form-input" id="employeeSelect" required>
                                <option value="">Select Employee</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}" data-salary="{{ $employee->salary }}"
                                        data-department="{{ $employee->department }}">
                                        {{ $employee->name }} - {{ $employee->department }} ({{ $employee->position }})
                                    </option>
                                @endforeach
                            </select>
                            @error('employee_id')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Advance Type *</label>
                            <select name="type" class="form-input" id="advanceType" required>
                                <option value="">Select Type</option>
                                <option value="salary">Salary Advance</option>
                                <option value="travel">Travel Advance</option>
                                <option value="emergency">Emergency Advance</option>
                                <option value="project">Project Advance</option>
                                <option value="other">Other</option>
                            </select>
                            @error('type')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Amount *</label>
                            <div class="amount-input">
                                <span class="currency-symbol">$</span>
                                <input type="number" name="amount" id="advanceAmount" class="form-input" min="0.01"
                                    step="0.01" placeholder="0.00" required>
                                <div class="amount-suggestions" id="amountSuggestions" style="display: none;">
                                    <button type="button" class="amount-btn" id="oneMonthSalary">1 Month Salary</button>
                                    <button type="button" class="amount-btn" id="halfMonthSalary">Half Month
                                        Salary</button>
                                </div>
                            </div>
                            @error('amount')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Due Date (Optional)</label>
                            <input type="date" name="due_date" class="form-input" min="{{ date('Y-m-d') }}">
                            <small class="form-hint">When should this advance be repaid?</small>
                        </div>

                        <div class="form-group full-width">
                            <label class="form-label">Reason for Advance *</label>
                            <textarea name="reason" class="form-input" rows="3" placeholder="Explain why this advance is needed..." required></textarea>
                            @error('reason')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group full-width">
                            <label class="form-label">Additional Notes</label>
                            <textarea name="notes" class="form-input" rows="2" placeholder="Any additional information..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- Advance Summary -->
                <div class="advance-summary" id="advanceSummary" style="display: none;">
                    <h4>Advance Summary</h4>
                    <div class="summary-details">
                        <div class="summary-row">
                            <span>Employee:</span>
                            <span id="summaryEmployee">-</span>
                        </div>
                        <div class="summary-row">
                            <span>Department:</span>
                            <span id="summaryDepartment">-</span>
                        </div>
                        <div class="summary-row">
                            <span>Monthly Salary:</span>
                            <span id="summarySalary">$0.00</span>
                        </div>
                        <div class="summary-row">
                            <span>Advance Amount:</span>
                            <span id="summaryAmount">$0.00</span>
                        </div>
                        <div class="summary-row">
                            <span>Percentage of Salary:</span>
                            <span id="summaryPercentage">0%</span>
                        </div>
                    </div>

                    <div class="advance-warning" id="advanceWarning" style="display: none;">
                        <p>⚠️ This advance exceeds 50% of monthly salary. Please ensure proper approval.</p>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="{{ route('accounting.advances') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <span class="btn-icon">💰</span>
                    Issue Advance
                </button>
            </div>
        </form>
    </div>

    <style>
        .create-advance {
            max-width: 800px;
            margin: 0 auto;
        }

        .advance-form {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .form-hint {
            color: #6b7280;
            font-size: 0.8em;
            margin-top: 4px;
        }

        .amount-suggestions {
            margin-top: 8px;
            display: flex;
            gap: 8px;
        }

        .amount-btn {
            background: #e5e7eb;
            color: #374151;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.8em;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .amount-btn:hover {
            background: #3b82f6;
            color: white;
        }

        .advance-summary {
            background: #f8fafc;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
            border-left: 4px solid #8b5cf6;
        }

        .advance-warning {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 6px;
            padding: 12px;
            margin-top: 15px;
        }

        .advance-warning p {
            margin: 0;
            color: #92400e;
            font-size: 0.9em;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const employeeSelect = document.getElementById('employeeSelect');
            const advanceType = document.getElementById('advanceType');
            const advanceAmount = document.getElementById('advanceAmount');
            const amountSuggestions = document.getElementById('amountSuggestions');
            const oneMonthBtn = document.getElementById('oneMonthSalary');
            const halfMonthBtn = document.getElementById('halfMonthSalary');
            const advanceSummary = document.getElementById('advanceSummary');
            const advanceWarning = document.getElementById('advanceWarning');

            let currentEmployeeSalary = 0;

            employeeSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption.value) {
                    const salary = parseFloat(selectedOption.dataset.salary);
                    const department = selectedOption.dataset.department;
                    const employeeName = selectedOption.text.split(' - ')[0];

                    currentEmployeeSalary = salary;
                    advanceSummary.style.display = 'block';

                    document.getElementById('summaryEmployee').textContent = employeeName;
                    document.getElementById('summaryDepartment').textContent = department;
                    document.getElementById('summarySalary').textContent = '$' + salary.toFixed(2);

                    updateAmountSuggestions();
                    updateSummary();
                } else {
                    advanceSummary.style.display = 'none';
                    amountSuggestions.style.display = 'none';
                    currentEmployeeSalary = 0;
                }
            });

            advanceType.addEventListener('change', function() {
                if (this.value === 'salary' && currentEmployeeSalary > 0) {
                    amountSuggestions.style.display = 'flex';
                } else {
                    amountSuggestions.style.display = 'none';
                }
            });

            oneMonthBtn.addEventListener('click', function() {
                advanceAmount.value = currentEmployeeSalary.toFixed(2);
                updateSummary();
            });

            halfMonthBtn.addEventListener('click', function() {
                advanceAmount.value = (currentEmployeeSalary / 2).toFixed(2);
                updateSummary();
            });

            advanceAmount.addEventListener('input', updateSummary);

            function updateAmountSuggestions() {
                oneMonthBtn.textContent = `1 Month ($${currentEmployeeSalary.toFixed(2)})`;
                halfMonthBtn.textContent = `Half Month ($${(currentEmployeeSalary / 2).toFixed(2)})`;
            }

            function updateSummary() {
                const amount = parseFloat(advanceAmount.value) || 0;
                const percentage = currentEmployeeSalary > 0 ? (amount / currentEmployeeSalary * 100) : 0;

                document.getElementById('summaryAmount').textContent = '$' + amount.toFixed(2);
                document.getElementById('summaryPercentage').textContent = percentage.toFixed(1) + '%';

                // Show warning if advance exceeds 50% of salary
                advanceWarning.style.display = percentage > 50 ? 'block' : 'none';
            }
        });
    </script>
@endsection
