<?php

namespace App\Channels;

use App\Exceptions\WebHookFailedException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use Illuminate\Log\Logger;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class WebhookChannel
{
    /**
     * @var Client
     */
    private $client;
    /**
     * @var Logger
     */
    private $logger;

    public function __construct(Client $client, Logger $logger)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    /**
     * @param Notifiable $notifiable
     * @param Notification $notification
     * @throws WebHookFailedException
     */
//    public function send($notifiable, Notification $notification)
//    {
//
//        if (! $url = $notifiable->routeNotificationFor('webhook')) {
//            return;
//        }
//
//        $webhookData = $notification->toWebhook($notifiable)->toArray();
//
//        $response = $this->client->post($url, [
//            'query' => Arr::get($webhookData, 'query'),
//            'body' => json_encode(Arr::get($webhookData, 'data')),
//            'verify' => Arr::get($webhookData, 'verify'),
//            'headers' => Arr::get($webhookData, 'headers'),
//        ]);
//
//        if ($response->getStatusCode() >= 300 || $response->getStatusCode() < 200) {
//            dd($response);
//            throw CouldNotSendNotification::serviceRespondedWithAnError($response);
//        }
//
//        return $response;
//    }

    public function send($notifiable, Notification $notification)
    {
        if (method_exists($notification, 'toWebhook')) {
            $body = (array) $notification->toWebhook($notifiable);
        } else {
            $body = $notification->toArray($notifiable);
        }
        $timestamp = now()->timestamp;
        $token = Str::random(16);

        $headers = [
            'timestamp' => $timestamp,
            'token' => $token,
            'signature' => hash_hmac(
                'sha256',
                $token . $timestamp,
                $notifiable->getSigningKey()
            ),
        ];

        $request = new Request('POST', $notifiable->getWebhookUrl(), $headers, json_encode($body));

        try {
            $response = $this->client->send($request);
            if ($response->getStatusCode() !== 200) {
                throw new WebHookFailedException('Webhook received a non 200 response');
            }

            $this->logger->debug('Webhook successfully posted to '. $notifiable->getWebhookUrl());

            return;

        } catch (ClientException $exception) {
            if ($exception->getResponse()->getStatusCode() !== 410) {
                throw new WebHookFailedException($exception->getMessage(), $exception->getCode(), $exception);
            }
        } catch (GuzzleException $exception) {
            throw new WebHookFailedException($exception->getMessage(), $exception->getCode(), $exception);
        }

        $this->logger->error('Webhook failed in posting to '. $notifiable->getWebhookUrl());


    }
}
