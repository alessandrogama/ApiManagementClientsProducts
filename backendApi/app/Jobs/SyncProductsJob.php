<?php

namespace App\Jobs;

use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use GuzzleHttp\Client;
use App\Models\Product;

class SyncProductsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $http = new Client();
        $response = $http->get('https://fakestoreapi.com/products');

        if ($response->getStatusCode() === 200) {
            $productsData = json_decode($response->getBody(), true);

            foreach ($productsData as $productData) {
                Product::updateOrCreate(
                    ['id' => $productData['id']],
                    [
                        'title' => $productData['title'],
                        'image' => $productData['image'],
                        'price' => $productData['price'],
                        'rating' => $productData['rating']['rate'] ?? null,
                    ]
                );
            }
        }
        Log::info("Sync Complete! :airplane_arriving:".date('d/m/Y H:i:s'));
    }
}
