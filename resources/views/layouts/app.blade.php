<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'logistics - Logistics Management System')</title>

    <!-- Use public directory CSS instead of Vite -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- 🔥 ADD: Additional Modal & Form Styles for User Management -->
    @stack('styles')
</head>

<body>
    <div class="container">
        @include('shared.sidebar')

        <main class="main-content">
            @include('shared.header')

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
        console.log('🚀 logistics User Management JavaScript Loading...');

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
                fetch(`/auth/users/${id}`, {
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
                fetch(`/auth/users/roles/${id}`, {
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
                fetch(`/auth/users/permissions/${id}`, {
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
                    submitForm(this, '/auth/users', 'User created successfully!');
                });
            }

            const roleForm = document.getElementById('createRoleForm');
            if (roleForm) {
                roleForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    submitForm(this, '/auth/users/roles', 'Role created successfully!');
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

                    fetch('/auth/users/permissions', {
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

        console.log('🎉 logistics User Management JavaScript Loaded Successfully!');
    </script>

    @stack('scripts')
    <script>
        // Preview selected user in forms
        document.addEventListener('DOMContentLoaded', function() {
            const userSelect = document.querySelector('select[name="assigned_to"]');
            const preview = document.getElementById('selected-user-preview');
            const previewAvatar = document.getElementById('preview-avatar');
            const previewName = document.getElementById('preview-name');
            const previewRole = document.getElementById('preview-role');

            if (userSelect) {
                userSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];

                    if (this.value) {
                        const avatarUrl = selectedOption.dataset.avatar;
                        const text = selectedOption.text;
                        const [name, position] = text.split(' - ');

                        if (avatarUrl) {
                            previewAvatar.innerHTML =
                                `<img src="${avatarUrl}" alt="${name}" class="rounded-full" style="width: 32px; height: 32px;">`;
                        } else {
                            previewAvatar.innerHTML =
                                `<div class="rounded-full bg-gray-300" style="width: 32px; height: 32px;"></div>`;
                        }

                        previewName.textContent = name;
                        previewRole.textContent = position;
                        preview.style.display = 'block';
                    } else {
                        preview.style.display = 'none';
                    }
                });
            }
        });

        // Avatar click handlers
        document.addEventListener('click', function(e) {
            if (e.target.closest('.avatar-clickable')) {
                const avatarContainer = e.target.closest('.avatar-clickable');
                // Add custom click logic here if needed
                console.log('Avatar clicked:', avatarContainer);
            }
        });
    </script>
</body>

</html>
