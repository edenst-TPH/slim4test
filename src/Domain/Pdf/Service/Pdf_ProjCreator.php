<?php

namespace App\Domain\Pdf\Service;

// use App\Domain\Pdf\Repository\PdfRepository;
use Psr\Log\LoggerInterface;

final class Pdf_ProjCreator
{
    // private PdfRepository $repository;

    // private PdfValidator $PdfValidator; @todo implement

    private LoggerInterface $logger;

    public function __construct(
        // PdfRepository $repository,
        // PdfValidator $PdfValidator,
        LoggerInterface $logger
    ) {
        // $this->repository = $repository;
        // $this->PdfValidator = $PdfValidator;
        $this->logger = $logger;
    }

    public function createProj(array $data): int
    {
        // Input validation
        // $this->PdfValidator->validatePdf($data); @todo implement

        // Insert Pdf and get new Pdf ID
        // $idPdf = $this->repository->insertPdf($data);

        // @todo call pdftk, collect fields-dump
        $idProj = 0; 

        // Logging
        $this->logger->info(sprintf('Pdf Project created, id %i', $idProj));

        return $idProj;
    }
}
