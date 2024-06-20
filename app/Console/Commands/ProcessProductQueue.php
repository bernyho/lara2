<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Services\RabbitService;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ProcessProductQueue extends Command
{
    protected $signature = 'process-product-queue';

    protected $description = 'Consume product queue.';

    public function __construct(
        private readonly RabbitService $rabbitService
    ) {
        parent::__construct();
    }

    public function handle(): void
    {
        $this->rabbitService->consume('product_queue', function ($message, $channel, $client) {
            $data = json_decode($message->content, true);
            $product = Product::find($data['id']);

            if ($product)
            {
                $product->hash = Str::random(40);
                $product->save();
            }
        });
    }
}
