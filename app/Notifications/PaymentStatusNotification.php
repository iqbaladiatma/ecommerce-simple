<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Order;

class PaymentStatusNotification extends Notification
{
  use Queueable;

  protected $order;
  protected $status;

  public function __construct(Order $order, string $status)
  {
    $this->order = $order;
    $this->status = $status;
  }

  public function via($notifiable)
  {
    return ['mail', 'database'];
  }

  public function toMail($notifiable)
  {
    $statusMessages = [
      'paid' => 'Your payment has been successfully processed.',
      'pending' => 'Your payment is pending. Please complete the payment process.',
      'failed' => 'Your payment has failed. Please try again.',
      'cancelled' => 'Your payment has been cancelled.',
      'expired' => 'Your payment has expired. Please try again.',
    ];

    return (new MailMessage)
      ->subject('Payment Status Update - Order #' . $this->order->order_number)
      ->greeting('Hello ' . $notifiable->name)
      ->line($statusMessages[$this->status] ?? 'Your payment status has been updated.')
      ->line('Order Number: ' . $this->order->order_number)
      ->line('Total Amount: Rp ' . number_format($this->order->total_amount, 0, ',', '.'))
      ->action('View Order', url('/orders/' . $this->order->id))
      ->line('Thank you for shopping with us!');
  }

  public function toArray($notifiable)
  {
    return [
      'order_id' => $this->order->id,
      'order_number' => $this->order->order_number,
      'status' => $this->status,
      'amount' => $this->order->total_amount,
    ];
  }
}
