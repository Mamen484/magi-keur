<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Filesystem;

class FileUploader
{
    private string $targetDirectory;

    public function __construct(string $uploadsPath)
    {
        $this->targetDirectory = $uploadsPath;
    }

    public function upload(UploadedFile $file): string
    {
        $filename = uniqid().'.'.$file->guessExtension();
        $file->move($this->targetDirectory, $filename);

        return '/uploads/receipts/' . $filename;
    }
}
