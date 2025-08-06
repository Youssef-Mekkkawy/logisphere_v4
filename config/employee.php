<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Employee Email Domain
    |--------------------------------------------------------------------------
    |
    | This domain will be used to auto-generate email addresses for employees
    | when creating user accounts. Format: firstname.lastname@domain.com
    |
    */
    'email_domain' => env('EMPLOYEE_EMAIL_DOMAIN', 'logistas.com'),

    /*
    |--------------------------------------------------------------------------
    | Default User Role
    |--------------------------------------------------------------------------
    |
    | The default role assigned to new employee user accounts when no specific
    | role is selected during creation.
    |
    */
    'default_role' => env('DEFAULT_EMPLOYEE_ROLE', 'user'),

    /*
    |--------------------------------------------------------------------------
    | Auto-Generate Email
    |--------------------------------------------------------------------------
    |
    | Whether to automatically generate company email addresses for employees
    | when creating user accounts if no email is provided.
    |
    */
    'auto_generate_email' => env('AUTO_GENERATE_EMPLOYEE_EMAIL', true),

    /*
    |--------------------------------------------------------------------------
    | Force Password Change
    |--------------------------------------------------------------------------
    |
    | Whether new employee user accounts should be forced to change their
    | password on first login for security.
    |
    */
    'force_password_change' => env('FORCE_PASSWORD_CHANGE_ON_CREATION', true),

    /*
    |--------------------------------------------------------------------------
    | Department Role Mapping
    |--------------------------------------------------------------------------
    |
    | Mapping of employee departments to default user roles.
    |
    */
    'department_role_mapping' => [
        'Management' => 'manager',
        'Finance & Accounting' => 'finance',
        'Human Resources' => 'manager',
        'IT & Technology' => 'user',
        'Operations' => 'user',
        'Customer Service' => 'user',
        'Sales' => 'user',
        'Customs Clearance' => 'user',
        'Warehousing' => 'user',
        'Transportation' => 'user',
        'Administration' => 'user'
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Generation Settings
    |--------------------------------------------------------------------------
    |
    | Settings for generating secure random passwords for new user accounts.
    |
    */
    'password' => [
        'length' => 12,
        'include_symbols' => true,
        'include_numbers' => true,
        'include_uppercase' => true,
        'include_lowercase' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | User Account Permissions
    |--------------------------------------------------------------------------
    |
    | Permissions required for various employee user account operations.
    |
    */
    'permissions' => [
        'create_user_accounts' => 'employees.create',
        'manage_user_accounts' => 'users.manage',
        'block_users' => 'users.manage',
        'force_password_reset' => 'users.manage',
    ]
];
