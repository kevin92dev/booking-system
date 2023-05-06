<?php

namespace App\Infrastructure\UI\Controller;

use League\Tactician\CommandBus;
use App\Application\Command\MaximizeProfitCommand;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MaximizeController
{
    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    #[Route(
        '/maximize',
        name: 'maximize',
        methods: ['POST']
    )]
    public function __invoke(Request $request): Response
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $dto = $this->commandBus->handle(
            new MaximizeProfitCommand($data)
        );

        return new JsonResponse($dto);
    }
}