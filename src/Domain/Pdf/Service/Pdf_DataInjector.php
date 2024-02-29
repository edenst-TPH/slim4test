<?php

namespace App\Domain\Pdf\Service;

use mikehaertl\pdftk\Pdf;
use App\Domain\Pdf\Service\Pdf_PathFinder;
// use App\Domain\Customer\Data\Pdf_DocReaderResult;
// use App\Domain\Customer\Repository\CustomerRepository;

/**
 * Service.
 */
final class Pdf_DataInjector
{
    private Pdf_PathFinder $Pdf_PathFinder;
    public function __construct(Pdf_PathFinder $Pdf_PathFinder, int $idDoc=0, array $data=[])
    {
        $this->Pdf_PathFinder = $Pdf_PathFinder;
    }

    /**
     * foreach given record, fill given Pdf form & create output pdf
     *
     * @param int $idDoc 'our' pdf id
     * @param array $data data to inject into given pdf
     * @return string path/url to output pdf that is filled & flattened
     */
    public function injectData(int $idDoc=0, array $data=[]): string #@todo find (pdf) doc by given idDoc
    {
        // return $data; # !!test

        $aPaths = $this->Pdf_PathFinder->findPaths($idDoc);
        $uploadPath = $aPaths['uploadPath']; # where we expect the uploaded pdf to process
        $outPath = $aPaths['outPath']; # where we expect the uploaded pdf to process
        $docFile = $aPaths['docFile'];
        $outFile = str_replace('.pdf','_flat.pdf',$docFile); #@todo sequence nr if multiple records / output pdfs

        $data = [ #@todo from request!!
            'text_field' => 'text_field',
            'text_field_multi' => 'line1\nline2',
            'check_box' => 'Yes',
            'readio' => 'red',
            // 'edtBirthday' => '16.9.1961' #no value provided, so skip
        ];

        $pdf = new Pdf($uploadPath.$docFile); # need new php-pdftk instance foreach call
        $result = $pdf->fillForm($data)
        ->needAppearances()
        ->flatten()
        ->saveAs($outPath.$outFile);

        # Always check for errors
        if ($result === false) {
	        $error = $pdf->getError();
	        return 'error: <br/>'.print_r($error,1);
        }

        return $outFile;

    }
}
