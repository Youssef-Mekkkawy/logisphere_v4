@extends('layouts.app')

@section('title', $employee->name . ' - LogiFlow')
@section('page-title', 'Employee Details')

@section('content')
<div style="margin-bottom: 20px;">
    <a href="{{ route('employees.index') }}" class="btn btn-secondary">← Back to Employees</a>
    <a href="{{ route('employees.edit', $employee) }}" class="btn btn-primary">Edit Employee</a>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
    <!-- Main Employee Info -->
    <div>
        <div style="background: white; border-radius: 15px; padding: 30px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); margin-bottom: 30px;">
            <div style="display: flex; align-items: center; margin-bottom: 20px;">
                <div style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(45deg, #667eea, #764ba2); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 24px; margin-right: 20px;">
                    {{ strtoupper(substr($employee->name, 0, 1)) }}
                </div>
                <div>
                    <h3 style="color: #1e40af; margin: 0;">{{ $employee->name }}</h3>
                    <p style="color: #6b7280; margin: 5px 0 0 0;">{{ $employee->position ?: 'Position not specified' }}</p>
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                <div>
                    <label style="font-weight: 600; color: #374151; display: block; margin-bottom: 5px;">Employee ID</label>
                    <p style="margin: 0; font-family: monospace; font-size: 16px; color: #1e40af;">{{ $employee->employee_id }}</p>
                </div>
                
                <div>
                    <label style="font-weight: 600; color: #374151; display: block; margin-bottom: 5px;">Department</label>
                    <span class="status-badge" style="background: #dbeafe; color: #1e40af;">{{ $employee->department ?: 'N/A' }}</span>
                </div>
                
                <div>
                    <label style="font-weight: 600; color: #374151; display: block; margin-bottom: 5px;">Status</label>
                    <span class="status-badge status-{{ strtolower($employee->status) }}">{{ $employee->status }}</span>
                </div>
                
                <div>
                    <label style="font-weight: 600; color: #374151; display: block; margin-bottom: 5px;">Email</label>
                    <p style="margin: 0;">
                        @if($employee->email)
                            <a href="mailto:{{ $employee->email }}" style="color: #3b82f6;">{{ $employee->email }}</a>
                        @else
                            N/A
                        @endif
                    </p>
                </div>
                
                <div>
                    <label style="font-weight: 600; color: #374151; display: block; margin-bottom: 5px;">Phone</label>
                    <p style="margin: 0;">
                        @if($employee->phone)
                            <a href="tel:{{ $employee->phone }}" style="color: #3b82f6;">{{ $employee->phone }}</a>
                        @else
                            N/A
                        @endif
                    </p>
                </div>
                
                <div>
                    <label style="font-weight: 600; color: #374151; display: block; margin-bottom: 5px;">Hire Date</label>
                    <p style="margin: 0;">{{ $employee->hire_date ? $employee->hire_date->format('M d, Y') : 'N/A' }}</p>
                </div>
                
                @if($employee->salary)
                <div>
                    <label style="font-weight: 600; color: #374151; display: block; margin-bottom: 5px;">Monthly Salary</label>
                    <p style="margin: 0; font-weight: 600; color: #059669;">${{ number_format($employee->salary, 2) }}</p>
                </div>
                @endif
                
                @if($employee->hire_date)
                <div>
                    <label style="font-weight: 600; color: #374151; display: block; margin-bottom: 5px;">Employment Duration</label>
                    <p style="margin: 0;">{{ $employee->hire_date->diffForHumans() }}</p>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Employment History / Assignments -->
        <div style="background: white; border-radius: 15px; padding: 30px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);">
            <h4 style="color: #1e40af; margin-bottom: 20px;">Employment History & Assignments</h4>
            
            <div style="padding: 40px; text-align: center; color: #6b7280;">
                <p>Assignment tracking and employment history features will be implemented in future updates.</p>
                <p style="margin-top: 15px;">
                    <a href="{{ route('accounting.index') }}" class="btn btn-primary">View Accounting Module</a>
                </p>
            </div>
        </div>
    </div>
    
    <!-- Sidebar Info -->
    <div>
        <!-- Quick Stats -->
        @if($employee->salary)
        <div style="background: white; border-radius: 15px; padding: 20px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); margin-bottom: 20px;">
            <h4 style="color: #1e40af; margin-bottom: 15px;">Salary Information</h4>
            
            <div style="margin-bottom: 15px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span>Monthly Salary</span>
                    <strong style="color: #059669;">${{ number_format($employee->salary, 2) }}</strong>
                </div>
            </div>
            
            <div style="margin-bottom: 15px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span>Annual Salary</span>
                    <strong style="color: #1e40af;">${{ number_format($employee->salary * 12, 2) }}</strong>
                </div>
            </div>
            
            @if($employee->hire_date)
            <div style="margin-bottom: 15px; padding-top: 15px; border-top: 1px solid #e5e7eb;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 12px; color: #6b7280;">Total Earnings (Est.)</span>
                    <strong style="color: #6b7280;">${{ number_format($employee->salary * $employee->hire_date->diffInMonths(now()), 2) }}</strong>
                </div>
            </div>
            @endif
        </div>
        @endif
        
        <!-- Employee Details -->
        <div style="background: white; border-radius: 15px; padding: 20px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); margin-bottom: 20px;">
            <h4 style="color: #1e40af; margin-bottom: 15px;">Employee Details</h4>
            
            <div style="margin-bottom: 10px;">
                <small style="color: #6b7280;">Employee ID</small>
                <div style="font-family: monospace; font-weight: 600;">{{ $employee->employee_id }}</div>
            </div>
            
            @if($employee->hire_date)
            <div style="margin-bottom: 10px;">
                <small style="color: #6b7280;">Hire Date</small>
                <div>{{ $employee->hire_date->format('M d, Y') }}</div>
            </div>
            @endif
            
            <div style="margin-bottom: 10px;">
                <small style="color: #6b7280;">Record Created</small>
                <div>{{ $employee->created_at->format('M d, Y') }}</div>
            </div>
            
            <div style="margin-bottom: 10px;">
                <small style="color: #6b7280;">Last Updated</small>
                <div>{{ $employee->updated_at->format('M d, Y H:i') }}</div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div style="background: white; border-radius: 15px; padding: 20px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);">
            <h4 style="color: #1e40af; margin-bottom: 15px;">Quick Actions</h4>
            
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <a href="{{ route('employees.edit', $employee) }}" class="btn btn-primary" style="text-align: center;">
                    Edit Employee
                </a>
                
                @if($employee->email)
                <a href="mailto:{{ $employee->email }}" class="btn btn-secondary" style="text-align: center;">
                    Send Email
                </a>
                @endif
                
                @if($employee->phone)
                <a href="tel:{{ $employee->phone }}" class="btn btn-secondary" style="text-align: center;">
                    Call Employee
                </a>
                @endif
                
                <a href="{{ route('accounting.index') }}" class="btn btn-secondary" style="text-align: center;">
                    View Accounting
                </a>
                
                @if(auth()->user()->isAdmin())
                <form method="POST" action="{{ route('employees.destroy', $employee) }}" style="margin-top: 10px;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn" style="background: #dc2626; color: white; width: 100%;"
                            onclick="return confirm('Are you sure you want to delete this employee? This action cannot be undone.')">
                        Delete Employee
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection