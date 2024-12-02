<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\Product;
use App\Services\ProductScraperService;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('show-product-list {url}', function ($url) {

    $productScraperService = new ProductScraperService();
    $products = $productScraperService->scrapeProducts($url);
    echo json_encode($products, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
})->describe('Shows products from a given URL');

Artisan::command('save-product-list {url}', function ($url) {

    $productScraperService = new ProductScraperService();
    $products = $productScraperService->scrapeProducts($url);

    foreach ($products as $product) {
        Product::updateOrCreate(
            ['id' => $product['id']],
            $product
        );
    }

    echo "Products saved successfully" . PHP_EOL;
})->describe('Save products from a given URL');
