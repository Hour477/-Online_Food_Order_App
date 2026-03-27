<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\DisplayImageHelper;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;
    //
    protected $appends = ['display_image'];
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'address',
        'city'
    ];
    public function getDisplayImageAttribute()
    {
        if ($this->user) {
            return $this->user->display_image;
        }
        return DisplayImageHelper::get(null, 'assets/img/placeholder.png');
    }

    public function orders(){
        return $this->hasMany(Order::class);
    }

    public function user(){
        return $this->belongsTo(User::class)->withTrashed();
    }
}
