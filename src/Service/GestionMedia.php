<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\AsciiSlugger;

class GestionMedia
{
    private $mediaFamille;
    private $mediaCandidat;

    public function __construct($familleDirectory, $candidatDirectory)
    {
        $this->mediaFamille = $familleDirectory;
        $this->mediaCandidat = $candidatDirectory;
    }

    /**
     * @param UploadedFile $file
     * @param $media
     * @return string
     */
    public function upload(UploadedFile $file, $media = null): string
    {
        // Initialisation du slug
        $slugify = new AsciiSlugger();

        $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $slugify->slug($originalFileName);
        $newFilename = $safeFilename.'-'.Time().'.'.$file->guessExtension();

        // Deplacement du fichier dans le repertoire dedié
        try {
            if ($media === 'famille') $file->move($this->mediaFamille, $newFilename);
            elseif ($media === 'candidat') $file->move($this->mediaCandidat, $newFilename);
            else $file->move($this->mediaFamille, $newFilename);
        }catch (FileException $e){

        }

        return $newFilename;
    }

    /**
     * Suppression de l'ancien media sur le server
     *
     * @param $ancienMedia
     * @param null $media
     * @return bool
     */
    public function removeUpload($ancienMedia, $media = null): bool
    {
        if ($media === 'famille') unlink($this->mediaFamille.'/'.$ancienMedia);
        elseif ($media === 'candidat') unlink($this->mediaCandidat.'/'.$ancienMedia);
        else return false;

        return true;
    }
}