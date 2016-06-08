<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

/*
  Attendize.com   - Event Management & Ticketing
 */

/**
 * Description of DiscountCode.
 *
 * @author Dave
 */
class DiscountCode extends MyBaseModel
{
    use SoftDeletes;

    protected $rules = [
        'amount'	 => ['required', 'numeric', 'min:0'],
	'code'		 => ['required', 'max:32'],
	'exp_at'    	 => ['date'],
	'max_times_used' => ['integer', 'min:1']
    ];

    protected $messages = [
        'amount.required'	=> 'Please input your discount amount.',
	'code.required'		=> 'Please type what your discount code will be.',
	'max_times_used.min'	=> 'Maximum should be greater than 0 (leave blank for no max).'
    ];

    public function event() {
        return $this->belongsTo('\App\Models\Event');
    }

    public function isExpired() {
        if (is_null($this->exp_at)) {
	   return false;
	}

	return Carbon::now()->lt($this->exp_at);
    }

    public function isMaxedOut() {
        if (is_null($this->max_times_used)) {
	   return false;
	}

	return $this->times_used < $this->max_times_used;
    }

    public function getDiscountedAmount($order_total) {
    	if ($this->type === "flat") {
	    return $this->amount;
	}
	else if ($this->type === "percent") {
	    return ($this->amount / 100) * $order_total;
	}
	else {
	    return 0.00;
	}
    }
}
