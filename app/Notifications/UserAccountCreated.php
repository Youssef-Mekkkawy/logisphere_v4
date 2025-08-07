<?php

// File: app/Notifications/UserAccountCreated.php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Auth\User;

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
