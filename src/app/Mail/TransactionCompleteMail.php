<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Item;
use App\Models\User;

class TransactionCompleteMail extends Mailable
{
    use Queueable, SerializesModels;

    public $item;
    public $buyer;
    public $seller;

    /**
     * Create a new message instance.
     *
     * @param Item $item
     * @param User $buyer
     * @param User $seller
     */
    public function __construct(Item $item, User $buyer, User $seller)
    {
        $this->item = $item;
        $this->buyer = $buyer;
        $this->seller = $seller;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('取引完了のお知らせ')
                    ->markdown('emails.transaction_complete');
    }
}
