<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderShipped extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($arrParams)
    {
        $this->arrParams = $arrParams;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //return view('emails.orders.shipped', ['forgottk' => $this->arrParams["forgottk"]]);
        
        return $this->markdown('emails.orders.shipped')
                    ->with([
                        'url' => $this->arrParams["url"],
                    ]);
    }
}
