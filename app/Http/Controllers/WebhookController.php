<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\SomethingHappenedNotification;


class WebhookController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        return view('home')
            ->withUser($request->user());
    }

    public function setEndPoint(Request $request)
    {

        $data = $this->validate($request, [
            'webhook_url' => 'string|required|url'
        ]);
//        dd($request->user());

        $request->user()->update($data);

        return redirect()
            ->back()
            ->with('status', 'Webhook URL has been set');
    }
    public function testWebhook(Request $request)
    {
        /** @var User $user */
        $user = $request->user();
        $mes = $request->text_test;
        $user->notify(new SomethingHappenedNotification($mes));
        return redirect()
            ->back()
            ->with('status','You will be notified by webhook');
    }
    public function webhook(Request $request){
        dd($request->all());
    }

}
