<?php

namespace App\Action\Pdf;

use App\Domain\Pdf\Service\Pdf_DocReader;
use App\Renderer\JsonRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
// use Laminas\Config\Config;

final class Pdf_DocReaderAction
{
    private Pdf_DocReader $Pdf_DocReader;
    private JsonRenderer $renderer;

    public function __construct(Pdf_DocReader $Pdf_DocReader, JsonRenderer $jsonRenderer/*, Config $config*/)
    {
        $this->Pdf_DocReader = $Pdf_DocReader;
        $this->renderer = $jsonRenderer;
        // $uploadDirectory = (string)$config->get('upload_directory');
    }

    public function __invoke(
        // mixed $request, 
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        // var_dump ($request); return;
        # Fetch parameters from the request
        if(isset($args['idDoc'])) { $idDoc = (int)$args['idDoc']; } 
        else { $idDoc = 0; }

        # Invoke the domain and get the result
        $docInfo = $this->Pdf_DocReader->readDoc($idDoc); # returns an array
        // return $docInfo;
        // $upPath = $app->get('upload_directory');

        # Transform result and render to json
        return $this->renderer->json($response, $docInfo);
        # return [pdf dump]; @todo implement, wrap as json
    }

    /* # if our Pdf_DocReader returned an object and we would parse / extract it
    private function transform(Pdf_DocReaderResult $customer): array
    {
        return [
            'id' => $customer->id,
            'number' => $customer->number,
            'name' => $customer->name,
            'street' => $customer->street,
            'postal_code' => $customer->postalCode,
            'city' => $customer->city,
            'country' => $customer->country,
            'email' => $customer->email,
        ];
    } */
}
