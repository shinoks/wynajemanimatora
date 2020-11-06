<?php
namespace App\Utils;

class FilesUtils
{
    public function getFileUrl(string $name): string
    {

        return $_SERVER['DOCUMENT_ROOT'].'/../public_html/attachement/' . $name;
    }
}
