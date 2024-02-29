<?php

namespace App\Domain\Pdf\Service;

use App\Domain\Pdf\Service\Pdf_PathFinder;
use mikehaertl\pdftk\Pdf;

/**
 * Service.
 */
final class Pdf_DocReader
{
    private Pdf_PathFinder $Pdf_PathFinder;
    
    public function __construct(Pdf_PathFinder $Pdf_PathFinder, int $idDoc = 0)
    {
        $this->Pdf_PathFinder = $Pdf_PathFinder;
    }

    /**
     * Read a pdf.
     *
     * @param int $idDoc 'our' pdf id
     * @return array pdf form fields (name => value), and/or general pdf info
     */
    public function readDoc(int $idDoc = 0): array #@todo find (pdf) doc by given idDoc
    {
        $aPaths = $this->Pdf_PathFinder->findPaths($idDoc);
        $uploadPath = $aPaths['uploadPath']; # where we expect the uploaded pdf to process
        $docFile = $aPaths['docFile'];

        # *** create & return general info + fields dump from pdf form
        # 1) dump general pdf info (CreateDate, ModDate, PageCount ..)
        $pdf = new Pdf($uploadPath.$docFile); # need new php-pdftk instance foreach call
        $dump =$pdf->getData();
        $aInfo = $dump->__toArray();
        # 2) dump form fields (type, name, value ..)
        $pdf = new Pdf($uploadPath.$docFile);
        $dump =$pdf->getDataFields(); # form fields
        $aFields = $dump->__toArray();
        # 3) merge fields into general info
        $aInfo['Fields'] = $aFields;
        
        return $aInfo;
    }
}
