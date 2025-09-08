<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TradeCompletedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $item;
    public $rater;

    public function __construct($item, $rater)
    {
        $this->item = $item;
        $this->rater = $rater;
    }

    public function build()
    {
        return $this->subject('取引が完了しました')
                    ->view('emails.trade_completed');
    }
}

