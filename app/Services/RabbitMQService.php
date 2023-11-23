<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQService
{
    /**
     * @param string $message
     * @return void
     * @throws \Exception
     */
    public function publish(string $message)
    {
        try {
            $connection = new AMQPStreamConnection(
                config('services.rabbitmq.host'),
                config('services.rabbitmq.port'),
                config('services.rabbitmq.user'),
                config('services.rabbitmq.pass'),
                config('services.rabbitmq.vhost'),
            );
            $channel = $connection->channel();
            $channel->exchange_declare('note_exchange', 'direct', false, true, false);
            $channel->queue_declare('note_queue', false, true, false, false);
            $channel->queue_bind('note_queue', 'note_exchange', '');

            $msg = new AMQPMessage($message);
            $channel->basic_publish($msg, 'note_exchange', '');
            $channel->close();
            $connection->close();
        } catch (\Exception $exception) {
            Log::error('Rabbit MQ error: ' . $exception->getMessage());
        }

    }


    public function consume()
    {
        try {
            $connection = new AMQPStreamConnection(
                config('services.rabbitmq.host'),
                config('services.rabbitmq.port'),
                config('services.rabbitmq.user'),
                config('services.rabbitmq.pass'),
                config('services.rabbitmq.vhost'),
            );
            $channel = $connection->channel();
            $callback = function ($msg) {
//                $data = json_decode($msg, true);
                echo ' [x] Received ', $msg->body, "\n";
            };
            $channel->queue_declare('note_queue', false, true, false, false);
            $channel->basic_consume('note_queue', '', false, true, false, false, $callback);
            echo 'Waiting for new message on note_queue', " \n";
            while ($channel->is_consuming()) {
                $channel->wait();
            }
            $channel->close();
            $connection->close();
        } catch (\Exception $exception) {
            Log::error('Rabbit MQ error: ' . $exception->getMessage());
        }

    }
}
