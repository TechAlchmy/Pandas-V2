<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;

class UnsubscribeController extends Controller
{
    public function __invoke(Subscriber $subscriber)
    {
        $subscriber->deleteOrFail();

        return response('Unsubscribed Successfully');
    }
}
