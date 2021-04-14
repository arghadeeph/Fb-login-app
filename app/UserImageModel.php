<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserImageModel extends Model
{
    //
    protected $table = 'user_images';

    public $timestamps = false;

    protected $fillable = [
        
            'user_id', 
            'image', 
            'is_default'
           
    ];

}
