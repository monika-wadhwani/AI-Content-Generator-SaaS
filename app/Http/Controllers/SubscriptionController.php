<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function checkout(Request $request){
        return $request->user()->newSubscription('default', config('services.stripe.price_id'))
        ->checkout([
            'success_url' => route('brand-profiles.index') . '?upgraded=1',
            'cancel_url' => route('brand-profiles.index')
        ]);
    }
}
