<?php
namespace App\Controller;

use App\Services\PokemonApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PokemonController extends AbstractController
{
    public function __construct(
    private PokemonApiService $pokemonApiService,
    ) {
    }
    #[Route('/')]
    public function index(): Response
    {
        return $this->render('pokemon_index.html.twig', [
            'pokemons' => $this->getPokemons() ?? [],
        ]);
    }

    #[Route('/pokemon/{id}')]
    public function pokemon(Request $request): Response
    {
        return $this->render('pokemon_id.html.twig', [
            'pokemon' => $this->pokemonApiService->getPokemonById($request->get('id')) ?? [],
        ]);
    }

    /**
     * @return array
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    private function getPokemons()
    {
        $pokemons = $this->pokemonApiService->getPokemonIndex();

        $pokemonArray = [];
        foreach ($pokemons['results'] as $pokemon) {
            $pokemonArray[] = [
                'id' => $this->pokemonApiService->getPokemonIdByUrl($pokemon['url']),
                'name' => $pokemon['name'],
                'url' => $pokemon['url']
            ];
        }

        return $pokemonArray;
    }
}