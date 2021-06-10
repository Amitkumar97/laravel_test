<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplates extends Model
{

    protected $table = "email_templates";
    protected $fillable = [
        'name',
        'slug',
        'subject',
        'message',
        'constants',

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
