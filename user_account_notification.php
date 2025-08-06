<?php

// File: app/Notifications/UserAccountCreated.php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;

class UserAccountCreated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;
    protected $password;
    protected $employee;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user, string $password, $employee = null)
    {
        $this->user = $user;
        $this->password = $password;
        $this->employee = $employee;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $companyName = config('app.name', 'logisphere');
        $loginUrl = url('/login');

        return (new MailMessage)
            ->subject("Welcome to {$companyName} - Your Account Has Been Created")
            ->greeting("Welcome to {$companyName}!")
            ->line("Your user account has been created successfully. Below are your login credentials:")
            ->line("**Login Details:**")
            ->line("• **Email:** {$this->user->email}")
            ->line("• **Username:** {$this->user->username}")
            ->line("• **Temporary Password:** `{$this->password}`")
            ->line("**Important Security Information:**")
            ->line("• You **must change your password** on first login")
            ->line("• Your account is active and ready to use")
            ->line("• Keep your login credentials secure")
            ->line("• Contact your administrator if you have any issues")
            ->action('Login to Your Account', $loginUrl)
            ->line("If you have any questions or need assistance, please contact your system administrator.")
            ->line("Thank you for joining our team!")
            ->salutation("Best regards,\n{$companyName} Team");
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'user_id' => $this->user->id,
            'user_email' => $this->user->email,
            'employee_id' => $this->employee?->employee_id,
            'created_at' => now()
        ];
    }
}

// File: app/Http/Controllers/Management/EmployeeController.php - Add this to the createUserAccount method

// After creating the user successfully, send email notification (optional)
if (config('mail.default') && $employee->email) {
    try {
        $user->notify(new \App\Notifications\UserAccountCreated($user, $password, $employee));
        Log::info('User account creation email sent', [
            'user_id' => $user->id,
            'employee_id' => $employee->id
        ]);
    } catch (\Exception $e) {
        Log::warning('Failed to send user account creation email', [
            'user_id' => $user->id,
            'error' => $e->getMessage()
        ]);
        // Don't fail the user creation if email fails
    }
}

// File: app/Mail/EmployeeWelcome.php (Alternative simpler approach)

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class EmployeeWelcome extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $password;
    public $employee;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, string $password, $employee = null)
    {
        $this->user = $user;
        $this->password = $password;
        $this->employee = $employee;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to ' . config('app.name') . ' - Your Account Credentials',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.employee-welcome',
            with: [
                'user' => $this->user,
                'password' => $this->password,
                'employee' => $this->employee,
                'loginUrl' => url('/login'),
                'companyName' => config('app.name', 'logisphere')
            ]
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}

// File: resources/views/emails/employee-welcome.blade.php

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome to {{ $companyName }}</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #1e40af; color: white; padding: 20px; border-radius: 8px 8px 0 0; }
        .content { background: #f8fafc; padding: 30px; border-radius: 0 0 8px 8px; }
        .credentials { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #1e40af; }
        .button { display: inline-block; background: #1e40af; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; margin: 20px 0; }
        .warning { background: #fef3c7; border: 1px solid #fbbf24; padding: 15px; border-radius: 6px; color: #92400e; }
        .footer { text-align: center; margin-top: 30px; color: #6b7280; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin: 0;">Welcome to {{ $companyName }}!</h1>
            <p style="margin: 10px 0 0 0; opacity: 0.9;">Your user account has been created</p>
        </div>
        
        <div class="content">
            <p>Hello <strong>{{ $user->name }}</strong>,</p>
            
            <p>Your user account for {{ $companyName }} has been successfully created. You can now access our logistics management system with the credentials below:</p>
            
            <div class="credentials">
                <h3 style="color: #1e40af; margin-top: 0;">Your Login Credentials</h3>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Username:</strong> {{ $user->username }}</p>
                <p><strong>Temporary Password:</strong> <code style="background: #f3f4f6; padding: 4px 8px; border-radius: 4px; font-weight: bold;">{{ $password }}</code></p>
                
                @if($employee)
                <p><strong>Employee ID:</strong> {{ $employee->employee_id }}</p>
                <p><strong>Department:</strong> {{ $employee->department }}</p>
                @endif
            </div>
            
            <div class="warning">
                <h4 style="margin-top: 0; color: #92400e;">🔒 Important Security Information</h4>
                <ul style="margin: 10px 0;">
                    <li>You <strong>must change your password</strong> on first login</li>
                    <li>Keep your login credentials secure and confidential</li>
                    <li>Never share your password with anyone</li>
                    <li>Contact your administrator if you suspect any security issues</li>
                </ul>
            </div>
            
            <div style="text-align: center;">
                <a href="{{ $loginUrl }}" class="button">Login to Your Account</a>
            </div>
            
            <p>If you have any questions or need assistance accessing your account, please contact your system administrator.</p>
            
            <p>Welcome to the team!</p>
        </div>
        
        <div class="footer">
            <p>This email was sent automatically by {{ $companyName }} system.<br>
            Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>