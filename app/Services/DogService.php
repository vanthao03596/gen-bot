<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Client;

class DogService
{
    const RANDOM_ENDPOINT = 'https://dog.ceo/api/breeds/image/random';
    const BREED_ENDPOINT = 'https://dog.ceo/api/breed/%s/images/random';
    const SUB_BREED_ENDPOINT = 'https://dog.ceo/api/breed/%s/%s/images/random';

    /**
     * Guzzle client.
     *
     * @var GuzzleHttp\Client
     */
    protected $client;

    /**
     * DogService constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->client = new Client;
    }

    /**
     * Fetch and return a random image from all breeds.
     *
     * @return string
     */
    public function random()
    {
        try {
            // Decode the json response.
            $response = json_decode(
                // Make an API call an return the response body.
                $this->client->get(self::RANDOM_ENDPOINT)->getBody()
            );

            // Return the image URL.
            return $response->message;
        } catch (Exception $e) {
            \logger($e);
            // If anything goes wrong, we will be sending the user this error message.
            return 'An unexpected error occurred. Please try again later.';
        }
    }


    public function byBreed($breed)
    {
        try {
            // We replace %s    in our endpoint with the given breed name.
            $endpoint = sprintf(self::BREED_ENDPOINT, $breed);

            $response = json_decode(
                $this->client->get($endpoint)->getBody()
            );

            return $response->message;
        } catch (Exception $e) {
            return "Sorry I couldn\"t get you any photos from $breed. Please try with a different breed.";
        }
    }

    public function bySubBreed($breed, $subBreed)
    {
        try {
            $endpoint = sprintf(self::SUB_BREED_ENDPOINT, $breed, $subBreed);

            $response = json_decode(
                $this->client->get($endpoint)->getBody()
            );

            return $response->message;
        } catch (Exception $e) {
            return "Sorry I couldn\"t get you any photos from $breed. Please try with a different breed.";
        }
    }
}
