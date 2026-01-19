<?php

declare(strict_types=1);

namespace App\Http\Controller;

use App\Twig\CsrfTokenExtension;
use Twig\Environment;
use Twig\Extra\Intl\IntlExtension;
use Twig\Loader\FilesystemLoader;

class Controller
{
    protected FileSystemLoader $fileSystemLoader;
    protected Environment $twig;
    protected function __construct() {
        $this->fileSystemLoader = new FilesystemLoader(dirname(__DIR__, 3) . '/templates');
        $this->twig = new Environment($this->fileSystemLoader);

        // Add the CSRF extension globally
        $this->twig->addExtension(new CsrfTokenExtension());
        $this->twig->addExtension(new IntlExtension());

    }

    public function twig() : Environment
    {
        return $this->twig;
    }
}
