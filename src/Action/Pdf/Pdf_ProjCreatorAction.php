<?php

namespace App\Action\Pdf;

// validate posted user token / permissions / quota, on success create project
use App\Domain\Pdf\Service\Pdf_ProjCreator;
use App\Renderer\JsonRenderer;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class Pdf_ProjCreatorAction
{
    private JsonRenderer $renderer;

    private Pdf_ProjCreator $Pdf_ProjCreator;

    public function __construct(Pdf_ProjCreator $Pdf_ProjCreator, JsonRenderer $renderer)
    {
        $this->Pdf_ProjCreator = $Pdf_ProjCreator;
        $this->renderer = $renderer;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        // Extract the form data from the request body
        $data = (array)$request->getParsedBody();

        // Invoke the Domain with inputs and retain the result
        $idProj = $this->Pdf_ProjCreator->createProj($data);

        // Build the HTTP response
        return $this->renderer
            ->json($response, ['idProj' => $idProj])
            ->withStatus(StatusCodeInterface::STATUS_CREATED);
    }
}
