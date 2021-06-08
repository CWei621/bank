<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detail extends Model
{
    use HasFactory;

    protected $fillable = [ 'before_balance', 'amount', 'balance', 'user_id' ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
