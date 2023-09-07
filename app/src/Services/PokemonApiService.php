<?php

namespace App\Services;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PokemonApiService extends AbstractController
{
    public function __construct(
        private HttpClientInterface $client,
    ) {
    }

    /**
     * @return array
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function getPokemonIndex()
    {
        return $this->callService('/pokemon/?limit=20');
    }

    /**
     * @param int $pokemonId
     * @return array
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function getPokemonById(int $pokemonId)
    {
        return $this->callService('/pokemon/'.$pokemonId);
    }

    /**
     * @param string $url
     * @return string
     */
    public function getPokemonIdByUrl(string $url)
    {
        return explode('/',parse_url($url, PHP_URL_PATH))['4'];
    }

    /**
     * @param string $url
     * @return array
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    private function callService(string $url)
    {
        try {
            $response = $this->client->request(
                'GET',
                $this->getParameter('app.pokemon_api_url').$url
            );

            if ($response->getStatusCode() !== 200) {
                throw $this->createNotFoundException('Pokemon API error: '.$response->getStatusCode());
            }

            return $response->toArray();
        }
        catch (\Exception $exception) {
            throw new Exception('Pokemon API error: '.$exception->getMessage());
        }
    }
}