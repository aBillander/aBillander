<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActionType extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $dates = ['deleted_at'];

    protected $fillable = [
                    'alias', 'name', 'description', 'active', 'position', 
             ];

    public static $rules = array(
        'name'         => 'required|min:2|max:64',
        'alias'        => 'required|min:2|max:16',
        );
    
}
