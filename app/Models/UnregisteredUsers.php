<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnregisteredUsers extends Model
{
    protected $fillable = [
        'email',
        'status',
        'is_active',
        /**
        * 1=>Activate
        * 2=>De-Activate
        */

        'is_deleted',
        /**
        * 1=>Deleted
        * 2=>Not Deleted
        */

    ];

}
