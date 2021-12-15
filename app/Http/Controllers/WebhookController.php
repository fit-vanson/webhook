<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\SomethingHappenedNotification;
use Binance\API;


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
            ->with('status', 'You will be notified by webhook');
    }

    public function webhook(Request $request)
    {
        $source_ip_list = array(
            '52.89.214.238',
            '34.212.75.30',
            '54.218.53.128',
            '52.32.178.7',
            '127.0.0.1'
        );
        $source_ip = $_SERVER['REMOTE_ADDR'];

        $api_key = 'vmPUZE6mv9SD5VNHk4HlWFsOr6aKE2zvsw0MuIgwCIPy6utIco14y7Ju91duEh8A';
        $secret_key = 'NhqPtmdSJYdKjVHjA7PZj4Mge3R5YNiP1e3UZjInClVN65XAbvqqM6A7H5fATj0j';

        $api = new \Binance\API($api_key, $secret_key);
        if (in_array($source_ip, $source_ip_list)) {
            // こんな感じのJSONが送られてくる前提
            // {'ticker':'BTCUSDT', 'side':'buyかsell', 'price':'価格'}
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);

            error_log($json);

            $api = new \Binance\API($api_key, $secret_key);
            $quantity = 0.001;
            dd($data);


            if ($data['side'] == 'buy') {
                $order = $api->buy($data['ticker'], $quantity, 0, "MARKET");
                // $order = $api->buy('BTCUSDT', $quantity, 45000, "LIMIT");
                error_log(print_r($order, true));

            } else if ($data['side'] == 'sell') {
                $order = $api->sell($data['ticker'], $quantity, 0, "MARKET");
                // $order = $api->sell('BTCUSDT', $quantity, 55000, "LIMIT");
                error_log(print_r($order, true));

            }

        } else {
            // 指定されたIP以外は403を返す
            header('HTTP', 403);
        }
    }
}


