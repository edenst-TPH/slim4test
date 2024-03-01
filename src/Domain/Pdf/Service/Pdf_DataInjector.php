<?php

namespace App\Domain\Pdf\Service;

use mikehaertl\pdftk\Pdf;
use App\Domain\Pdf\Service\Pdf_PathFinder;
// use App\Domain\Customer\Data\Pdf_DocReaderResult;
// use App\Domain\Customer\Repository\CustomerRepository;

/**
 * Service.
 * @todo:
    * eval given token for pdf form
    * handle posted data: validate, extract reords
    * multiple records in data: tell, iterate, number output pdfs and collect in a zip
    * create dload link / dload handler for user 
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
        $uploadPath = $aPaths['uploadPath']; # where we expect the uploaded pdf-form to process
        $outPath = $aPaths['outPath']; # where we store the output pdfs (filled & flattened)
        $docFile = $aPaths['docFile'];
        // $outFile = str_replace('.pdf','_flat.pdf',$docFile); #@todo sequence nr if multiple records / output pdfs

        #@todo data from restful PUT request!
        $data = 
        [
            [
                'text_field' => 'r1-text_field',
                'text_field_multi' => 'r1-line1\nline2',
                'check_box' => 'Yes',  # checked
                'readio' => 'green', # @todo does not work 
            ],
            [
                'text_field' => 'r2-text_field',
                'text_field_multi' => 'r2-line1\nline2',
                'check_box' => 'Off', # unchecked
                'readio' => 'red',
            ]
        ]
        ;

        if(!$this->isIndexed($data)) { $data = [$data]; } # wrap single (assoc) array in indexed array

        foreach($data as $k => $v) {
            $pdf = new Pdf($uploadPath.$docFile); # need new php-pdftk instance foreach call
            $outFile = str_replace('.pdf', '_flat_'.$k.'.pdf', $docFile); # ..flat_0, flat_1 ..
            $result = $pdf->fillForm($v)
            ->needAppearances()
            ->flatten()
            ->saveAs($outPath.$outFile); #@todo store multiple output pdfs in a zip
            # Always check for errors
            if ($result === false) {
                $error = $pdf->getError();
                return 'error: <br/>'.print_r($error,1);
            }
        }
        return $outFile; #@todo zip-arch if multiple
    }
    /*
    tell if given array is indexed (NOT associative)
    */
    private function isIndexed(array $aa): bool {
        $keys = array_keys($aa);
        return ($keys === range(0, count($aa)-1));
    }
}
