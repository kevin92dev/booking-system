<?php

namespace App\Infrastructure\UI\Controller;

use League\Tactician\CommandBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Application\Command\NightProfitCalculationCommand;

class StatsController
{
    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    #[Route(
        '/stats',
        name: 'stats',
        methods: ['POST']
    )]
    public function __invoke(Request $request): Response
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $dto = $this->commandBus->handle(
            new NightProfitCalculationCommand($data)
        );

        return new JsonResponse($dto);
    }
}