<?php

namespace App\Models;

use App\Models\User;
use App\Models\PaymentPlan;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{

    protected $fillable = [
        'datetime',
        'price',
        'payment_method',
        'status',
        'user_id',
        'payment_plan_id'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function payment_plan() {
        return $this->belongsTo(PaymentPlan::class);
    }

    public function failed() {
        return in_array($this->status, ['rejected','cancelled', 'refunded', 'charged_back']);
    }

    public function is_pending() {
        return true;
    }
}
