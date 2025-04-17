<?php

namespace App\Services;

use GuzzleHttp\Client;

class BookService
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }
    

    public function fetchCoverImage($isbn)
    {
        $url = str_replace('{isbn}', $isbn, env('OPEN_LIBRARY_API_URL'));
    
        $response = $this->client->get($url);
        $data = json_decode($response->getBody()->getContents(), true);
    
        if (isset($data["ISBN:{$isbn}"]) && isset($data["ISBN:{$isbn}"]["cover"])) {
            return $data["ISBN:{$isbn}"]["cover"]["medium"] ?? null;
        }
    
        return null;  
    }
}
