<?php

declare(strict_types=1);

namespace Core;

abstract class Controller
{
    protected function view(string $template, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        $viewFile = __DIR__ . '/../app/Views/' . $template . '.php';
        if (!is_file($viewFile)) {
            throw new \RuntimeException('View not found: ' . $template);
        }
        require __DIR__ . '/../app/Views/layouts/header.php';
        require $viewFile;
        require __DIR__ . '/../app/Views/layouts/footer.php';
    }
}
