<?php

namespace App\Action\Pdf;

use App\Domain\Pdf\Service\Pdf_DataInjector;
use App\Renderer\JsonRenderer;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class Pdf_DataInjectorAction
{

    private Pdf_DataInjector $Pdf_DataInjector;
    private JsonRenderer $renderer;

    public function __construct(Pdf_DataInjector $Pdf_DataInjector, JsonRenderer $renderer)
    {
        $this->Pdf_DataInjector = $Pdf_DataInjector;
        $this->renderer = $renderer;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        # Extract the form data from the request body
        $data = (array)$request->getParsedBody();
        $idDoc = (isset($args['idDoc'])) ? (int)$args['idDoc'] : 0;
        $data = (isset($args['data'])) ? (array)$args['data'] : [];

        # Invoke the Domain with inputs and retain the result
        $result = $this->Pdf_DataInjector->injectData($idDoc,$data);

        # Build the HTTP response @todo return as download link
        return $this->renderer
            ->json($response, ['output pdf' => $result])
            ->withStatus(StatusCodeInterface::STATUS_CREATED);
    }
}
