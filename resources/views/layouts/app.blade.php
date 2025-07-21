<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LogiFlow - Logistics Management System')</title>

    <!-- Use public directory CSS instead of Vite -->
    <link href="{{ asset('css/logiflow.css') }}" rel="stylesheet">

    <!-- 🔥 ADD: Additional Modal & Form Styles for User Management -->
    <style>
        /* Modal Styles for User Management */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            border-radius: 15px;
            width: 90%;
            max-width: 600px;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 30px;
            border-bottom: 1px solid #e5e7eb;
        }

        .modal-header h4 {
            margin: 0;
            color: #1e40af;
        }

        .close {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #6b7280;
        }

        .close:hover {
            color: #dc2626;
        }

        .modal-body {
            padding: 30px;
        }

        .modal-footer {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            padding: 20px 30px;
            border-top: 1px solid #e5e7eb;
        }

        /* Tab Styles */
        .tabs {
            display: flex;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 20px;
        }

        .tab {
            padding: 15px 30px;
            cursor: pointer;
            background: #f8fafc;
            border-right: 1px solid #e2e8f0;
            transition: all 0.3s ease;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .tab:hover {
            background: #f1f5f9;
        }

        .tab.active {
            background: var(--primary-color, #3b82f6);
            color: white;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* Form Styles */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--text-color, #1f2937);
        }

        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid var(--border-color, #e5e7eb);
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary-color, #3b82f6);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .error-message {
            color: var(--danger-color, #ef4444);
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: block;
        }

        /* Button Styles - Ensure compatibility */
        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            border: none;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color, #3b82f6), var(--primary-dark, #1e40af));
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
            color: white;
        }

        .btn-secondary {
            background: var(--secondary-color, #64748b);
            color: white;
        }

        .btn-secondary:hover {
            background: #475569;
            color: white;
        }

        .btn-danger {
            background: var(--danger-color, #ef4444);
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
            color: white;
        }

        /* Status Badge */
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-block;
        }

        .status-active {
            background: #d1fae5;
            color: #065f46;
        }

        .status-inactive {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Data Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th {
            background: var(--background-color, #f8fafc);
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: var(--text-color, #1f2937);
            border-bottom: 2px solid var(--border-color, #e5e7eb);
        }

        .data-table td {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color, #e5e7eb);
            vertical-align: middle;
        }

        .data-table tr:hover {
            background: #f8fafc;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .tabs {
                flex-direction: column;
            }

            .tab {
                border-right: none;
                border-bottom: 1px solid #e2e8f0;
            }

            .modal-content {
                width: 95%;
                margin: 20px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    @stack('styles')
</head>

<body>
    <div class="container">
        @include('components.sidebar')

        <main class="main-content">
            @include('components.header')

            <div class="content-area">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-error">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <!-- Use public directory JS instead of Vite -->
    <script src="{{ asset('js/logiflow.js') }}"></script>

    <!-- 🔥 ADD: Global JavaScript Functions for User Management -->
    <script>
        console.log('🚀 LogiFlow User Management JavaScript Loading...');

        // ===== GLOBAL MODAL FUNCTIONS =====
        window.openModal = function(modalId) {
            console.log('🔷 Opening modal:', modalId);
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = 'flex';
                console.log('✅ Modal opened:', modalId);
            } else {
                console.error('❌ Modal not found:', modalId);
            }
        };

        window.closeModal = function(modalId) {
            console.log('🔷 Closing modal:', modalId);
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = 'none';
                console.log('✅ Modal closed:', modalId);

                // Clear form if it exists
                const form = modal.querySelector('form');
                if (form) form.reset();

                // Hide error messages
                const errorDiv = modal.querySelector('[id*="error"]');
                if (errorDiv) errorDiv.style.display = 'none';

                // Reset new group field if exists
                const newGroupField = modal.querySelector('#newGroupField');
                if (newGroupField) newGroupField.style.display = 'none';
            }
        };

        // ===== PERMISSION SPECIFIC FUNCTIONS =====
        window.openPermissionModal = function() {
            console.log('🔑 Opening permission modal');
            openModal('createPermissionModal');
        };

        // ===== DEBUG FUNCTIONS =====
        window.debugModal = function() {
            console.log('=== 🔍 DEBUG INFO ===');
            console.log('Modal element:', document.getElementById('createPermissionModal'));
            console.log('Permission button:', document.querySelector('button[onclick*="openPermissionModal"]'));
            console.log('CSRF token:', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'));
            console.log('Available functions:', {
                openModal: typeof window.openModal,
                closeModal: typeof window.closeModal,
                openPermissionModal: typeof window.openPermissionModal
            });

            alert('🔍 Debug info logged to console. Check F12 Developer Tools.');
        };

        window.testJavaScript = function() {
            console.log('✅ JavaScript is working correctly!');
            alert('✅ JavaScript test successful!');
        };

        // ===== FORM SUBMISSION FUNCTION =====
        window.submitForm = function(form, url, successMessage) {
            const formData = new FormData(form);
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;

            // Show loading state
            submitBtn.textContent = 'Processing...';
            submitBtn.disabled = true;

            fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Reset button
                    submitBtn.textContent = originalText;
                    submitBtn.disabled = false;

                    if (data.success) {
                        alert(successMessage);
                        location.reload();
                    } else {
                        alert('Error: ' + (data.message || 'Something went wrong'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    submitBtn.textContent = originalText;
                    submitBtn.disabled = false;
                    alert('An error occurred while processing your request');
                });
        };

        // ===== USER ACTIONS =====
        window.deleteUser = function(id) {
            if (confirm('Are you sure you want to delete this user?')) {
                fetch(`/users/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content'),
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('User deleted successfully!');
                            location.reload();
                        } else {
                            alert('Error: ' + (data.message || 'Something went wrong'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while deleting the user');
                    });
            }
        };

        // ===== ROLE ACTIONS =====
        window.deleteRole = function(id) {
            if (confirm('Are you sure you want to delete this role?')) {
                fetch(`/users/roles/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content'),
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Role deleted successfully!');
                            location.reload();
                        } else {
                            alert('Error: ' + (data.message || 'Something went wrong'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while deleting the role');
                    });
            }
        };

        window.viewRole = function(id) {
            alert('Role view functionality - ID: ' + id);
        };

        // ===== PERMISSION ACTIONS =====
        window.deletePermission = function(id) {
            if (confirm('Are you sure you want to delete this permission?')) {
                fetch(`/users/permissions/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content'),
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Permission deleted successfully!');
                            location.reload();
                        } else {
                            alert('Error: ' + (data.message || 'Something went wrong'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while deleting the permission');
                    });
            }
        };

        window.viewPermission = function(id) {
            alert('Permission view functionality - ID: ' + id);
        };

        // ===== DOM READY SETUP =====
        document.addEventListener('DOMContentLoaded', function() {
            console.log('📄 DOM loaded, setting up user management functionality...');

            // ===== TAB FUNCTIONALITY =====
            const tabs = document.querySelectorAll('.tab');
            const tabContents = document.querySelectorAll('.tab-content');

            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    tabs.forEach(t => t.classList.remove('active'));
                    tabContents.forEach(content => content.classList.remove('active'));
                    this.classList.add('active');
                    const targetContent = document.getElementById(this.dataset.tab + '-tab');
                    if (targetContent) {
                        targetContent.classList.add('active');
                    }
                });
            });

            // ===== GROUP FIELD TOGGLE =====
            const groupSelect = document.getElementById('permissionGroupSelect');
            const newGroupField = document.getElementById('newGroupField');

            if (groupSelect && newGroupField) {
                groupSelect.addEventListener('change', function() {
                    console.log('📝 Group changed to:', this.value);
                    if (this.value === 'new') {
                        newGroupField.style.display = 'block';
                        newGroupField.querySelector('input').required = true;
                    } else {
                        newGroupField.style.display = 'none';
                        newGroupField.querySelector('input').required = false;
                    }
                });
            }

            // ===== FORM SUBMISSIONS =====
            const userForm = document.getElementById('createUserForm');
            if (userForm) {
                userForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    submitForm(this, '/users', 'User created successfully!');
                });
            }

            const roleForm = document.getElementById('createRoleForm');
            if (roleForm) {
                roleForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    submitForm(this, '/users/roles', 'Role created successfully!');
                });
            }

            const permissionForm = document.getElementById('createPermissionForm');
            if (permissionForm) {
                permissionForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    console.log('📝 Permission form submitted');

                    const formData = new FormData(this);
                    const errorDiv = document.getElementById('permission-form-errors');
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.textContent;

                    // Clear previous errors
                    if (errorDiv) errorDiv.style.display = 'none';

                    // Show loading state
                    submitBtn.textContent = 'Creating...';
                    submitBtn.disabled = true;

                    fetch('/users/permissions', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'),
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            // Reset button
                            submitBtn.textContent = originalText;
                            submitBtn.disabled = false;

                            if (data.success) {
                                alert('✅ Permission created successfully!');
                                closeModal('createPermissionModal');
                                location.reload();
                            } else {
                                // Show error
                                if (errorDiv) {
                                    errorDiv.innerHTML = '<strong>Error:</strong> ' + (data.message ||
                                        'Something went wrong');
                                    errorDiv.style.display = 'block';
                                } else {
                                    alert('Error: ' + (data.message || 'Something went wrong'));
                                }
                            }
                        })
                        .catch(error => {
                            console.error('❌ Error:', error);

                            // Reset button
                            submitBtn.textContent = originalText;
                            submitBtn.disabled = false;

                            // Show error
                            const message = 'An error occurred while creating the permission';
                            if (errorDiv) {
                                errorDiv.innerHTML = '<strong>Error:</strong> ' + message;
                                errorDiv.style.display = 'block';
                            } else {
                                alert('Error: ' + message);
                            }
                        });
                });
            }

            // ===== CLICK OUTSIDE TO CLOSE MODALS =====
            window.addEventListener('click', function(event) {
                const modals = document.querySelectorAll('.modal');
                modals.forEach(modal => {
                    if (event.target === modal) {
                        modal.style.display = 'none';
                    }
                });
            });

            console.log('🎉 User Management setup complete!');
            console.log('Available functions:', Object.keys(window).filter(key =>
                typeof window[key] === 'function' &&
                (key.includes('Modal') || key.includes('User') || key.includes('Role') || key.includes(
                    'Permission'))
            ));
        });

        // ===== GLOBAL UTILITY FUNCTIONS =====
        window.confirmDelete = function(message) {
            return confirm(message || 'Are you sure you want to delete this item?');
        };

        // CSRF Token setup for AJAX requests
        window.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        console.log('🎉 LogiFlow User Management JavaScript Loaded Successfully!');
    </script>

    @stack('scripts')
</body>

</html>
