<?php

namespace App\Domain\Pdf\Service;

/**
 * find paths to: pdf form uploads, processed pdfs, temp exports like fdf
 * (also pdf filenames by idDoc if given?) 
*/

/**
 * Service
 */
final class Pdf_PathFinder
{
    public function __construct(int $idDoc = 0)
    {
    }

    /**
     * Read a pdf.
     *
     * @param int $idDoc 'our' pdf id
     * @return array: Paths to app root, pdf uploads, temp / exports
     */
    public function findPaths(int $idDoc = 0): array #@todo find (pdf) doc by given idDoc
    {
        $rootPath = getcwd(); # path to 'index.php' that includes me
        $uploadPath = dirname($rootPath). '/uploads/'; # where we expect the uploaded pdf to process
        $outPath = $uploadPath.'out/'; # where to save output (dumps, filled pdfs, ..)

        # @todo sel pdf file by given idDoc
        $docFile = '01acro_filled_img_chk_radio.pdf';

        return array(
            'rootPath' => $rootPath,
            'uploadPath' => $uploadPath,
            'outPath' => $outPath,
            'docFile' => $docFile, # @todo sel pdf file by given idDoc
        );
    }
}
