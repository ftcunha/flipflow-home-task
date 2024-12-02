<?php

namespace App\Services;

use Symfony\Component\DomCrawler\Crawler;
use Exception;

class ProductScraperService
{
    public function scrapeProducts(string $url): array
    {
        $ch = curl_init();
        $zenRowApiKey = env('ZENROW_API_KEY');
        $zenRowUrl = env('ZENROW_API_URL');
        $zenRowApiVersion = env('ZENROW_API_VERSION');
        $sizeLimit = env('PRODUCT_SCRAPER_SIZE_LIMIT', 5);
        $encodedUrl = urlencode($url);

        $apiUrl = "{$zenRowUrl}/{$zenRowApiVersion}/?apikey={$zenRowApiKey}&url={$encodedUrl}";
        $products = [];
        $count = 0;

        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($httpCode !== 200) {
            $errorMessage = curl_error($ch);
            curl_close($ch);
            if ($httpCode === 401) {
                throw new Exception("Invalid ZENROW_API_KEY. Please check your API key.");
            } else {
                throw new Exception("Invalid URL. Please check the URL you provided. cURL Error: {$errorMessage}");
            }
        }

        try {
            $response = curl_exec($ch);
            $crawler = new Crawler($response);

            $crawler->filter('.product-card-list__item')->each(function ($node) use (&$products, &$count, $sizeLimit) {
                if ($count >= $sizeLimit) return;

                try {
                    $productName = $node->filter('.product-card__title-link')->text();
                    $productPrice = $node->filter('.product-card__price')->text();
                    $productPricePerUnit = $node->filter('.product-card__price-per-unit')->text();
                    $productImageUrl = $node->filter('.product-card__image')->attr('src');
                    $productUrl = $node->filter('.product-card__title-link')->attr('href');

                    // Extract product ID from URL
                    preg_match('/\/R-([A-Za-z0-9-]+)\/p/', $productUrl, $matches);
                    $productId = $matches[1] ?? null;

                    $products[] = [
                        'id' => $productId,
                        'name' => $productName,
                        'price' => $productPrice,
                        'price_per_unit' => $productPricePerUnit,
                        'image_url' => $productImageUrl,
                        'url' => $productUrl
                    ];

                    $count++;
                } catch (\Throwable $th) {
                    echo "Error: " . $th->getMessage() . PHP_EOL;
                }
            });
        } finally {
            curl_close($ch);
        }

        return $products;
    }
}
