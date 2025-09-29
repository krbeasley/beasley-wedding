<?php

namespace App\Http;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Controller
{
    protected FileSystemLoader $fileSystemLoader;
    protected Environment $twig;
    protected function __construct() {
        $this->fileSystemLoader = new FilesystemLoader(dirname(__DIR__, 2) . '/templates');
        $this->twig = new Environment($this->fileSystemLoader);
    }

    public function twig() : Environment
    {
        return $this->twig;
    }
}