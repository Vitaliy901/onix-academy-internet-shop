<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderInvoice extends Notification implements ShouldQueue
{
    use Queueable;

    public $tries = 3;

    public $backoff = 2;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(protected Order $order)
    {
        $this->afterCommit();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Order: ' . $this->order->id)
            ->markdown('mail.invoice.order', [
                'order' => $this->order,
                'name' => $notifiable->name,
                'url' => 'http://spa.test?order_url=' . url('api/orders/' . $this->order->id),
            ]);
    }
}
