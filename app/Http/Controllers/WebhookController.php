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
        $source_ip_list = array(
            '52.89.214.238',
            '34.212.75.30',
            '54.218.53.128',
            '52.32.178.7',
            '127.0.0.1'
        );

        $source_ip = $_SERVER['REMOTE_ADDR'];
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        dd($json, $data);

    }

}
