<?php

namespace App\Services\Shopify;

use Illuminate\Support\Facades\Http;

class ShopifyOAuthService
{
    private string $apiKey;
    private string $apiSecret;
    private string $scopes;
    private string $redirectUri;

    public function __construct()
    {
        $this->apiKey = config('services.shopify.key');
        $this->apiSecret = config('services.shopify.secret');
        $this->scopes = config('services.shopify.scopes');
        $this->redirectUri =  route('shopify.callback', [] , true);
    }

    public function buildAuthUrl(string $shop): string
    {
        return "https://{$shop}/admin/oauth/authorize?" . http_build_query([
            'client_id' => $this->apiKey,
            'scope' => $this->scopes,
            'redirect_uri' => $this->redirectUri,
        ]);
    }

    public function requestAccessToken(string $shop, string $code): string
    {
        $response = Http::asForm()->post("https://{$shop}/admin/oauth/access_token", [
            'client_id' => $this->apiKey,
            'client_secret' => $this->apiSecret,
            'code' => $code,
        ]);

        return $response->json('access_token');
    }
}
