<?php

// Define app routes

use Slim\App;
use Slim\Routing\RouteCollectorProxy;

use Psr\Http\Message\ResponseInterface;

return function (App $app) {
    $app->get('/', \App\Action\Home\HomeAction::class)->setName('home');


    // $app->get('/test', function(ResponseInterface $response){
    //     return $this->get('upload_directory');
    // });
    
    // pdf
    $app->group(
        '/pdf',
        function (RouteCollectorProxy $app) {
            // these work on existing / uploaded Pdfs, @todo add Pdf posts 
            // (will pass a ServerRequest to class invoke)
            $app->get('/ReadDoc', \App\Action\Pdf\Pdf_DocReaderAction::class);
            $app->get('/ExtractFdf', \App\Action\Pdf\Pdf_FdfExtractorAction::class);
            $app->get('/InjectData', \App\Action\Pdf\Pdf_DataInjectorAction::class);
        }
    );

    // API
    $app->group(
        '/api',
        function (RouteCollectorProxy $app) {
            $app->get('/customers', \App\Action\Customer\CustomerFinderAction::class);
            $app->post('/customers', \App\Action\Customer\CustomerCreatorAction::class);
            $app->get('/customers/{customer_id}', \App\Action\Customer\CustomerReaderAction::class);
            $app->put('/customers/{customer_id}', \App\Action\Customer\CustomerUpdaterAction::class);
            $app->delete('/customers/{customer_id}', \App\Action\Customer\CustomerDeleterAction::class);
        }
    );
};
