<?php
namespace App\Mail;


use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use PDF;

class OrderMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $orders;

    /**
     * Create a new message instance.
     */
    public function __construct($orders)
    {
        $this->orders = $orders;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $email = $this->view('front.emails.order');
        $email->from('sale@schoengebraucht.ch');

        $pdf = PDF::loadView('front.emails.order', ['orders' => $this->orders]);
        $name = time() . '.pdf';
        $pdf->save(storage_path().'\app\email\\'. $name);

        $email->attach(storage_path().'\app\email\\'. $name);

        $email->with([
            'orders' => $this->orders,
        ]);

        return $email;
    }
}