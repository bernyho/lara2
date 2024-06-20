<?php

namespace App\Services;

use Bunny\Channel;
use Bunny\Client;
use Bunny\Message;

class RabbitService
{
    protected Client $client;

    protected Channel $channel;

    public function __construct()
    {
        $this->client = new Client([
            'vhost' => '/',
            'host' => env('RABBITMQ_HOST', 'localhost'),
            'port' => env('RABBITMQ_PORT', 5672),
            'user' => env('RABBITMQ_USER', 'guest'),
            'password' => env('RABBITMQ_PASSWORD', 'guest'),
        ]);

        $this->client->connect();
        $this->channel = $this->client->channel();
    }

    public function sendMessage(
        string $exchange,
        string $queue,
        string $routingKey,
        string $message,
        bool $durable = true
    ): void
    {
        $this->channel->exchangeDeclare($exchange, durable: $durable);
        $this->channel->queueDeclare($queue, durable: $durable);
        $this->channel->queueBind($queue, $exchange, $routingKey);
        $this->channel->publish($message, exchange: 'product_exchange', routingKey: 'product_key');
   }

    public function consume(string $queue, callable $callback): void
    {
        $this->channel->queueDeclare($queue, durable: true);

        $this->channel->consume(function (Message $message, Channel $channel, Client $client) use ($callback) {
            $callback($message, $channel, $client);
            $channel->ack($message);
        }, $queue);
        $this->client->run();
    }

   public function __destruct()
   {
       $this->client->disconnect();
   }
}
