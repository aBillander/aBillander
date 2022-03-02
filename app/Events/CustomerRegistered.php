<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

use App\User;
use App\Customer;
use App\CustomerUser;

class CustomerRegistered
{
    use SerializesModels;

    /**
     * The authenticated user.
     *
     * @var \Illuminate\Contracts\Auth\Authenticatable
     */
    public $user;

    /**
     * Create a new event instance.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return void
     */
    public function __construct(Customer $user)
    {
        $this->user = $user;
    }
}
