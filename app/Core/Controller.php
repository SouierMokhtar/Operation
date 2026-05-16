<?php

namespace App\Core;

class Controller
{
    protected function view(string $viewPath, array $data = [], string $layout = 'main'): void
    {
        extract($data);

        $contentFile = ROOT_PATH . '/app/Views/' . $viewPath . '.php';

        if (!file_exists($contentFile)) {
            die("Vue introuvable : $viewPath");
        }

        ob_start();
        require $contentFile;
        $content = ob_get_clean();

        $layoutFile = ROOT_PATH . '/app/Views/layouts/' . $layout . '.php';
        if (file_exists($layoutFile)) {
            require $layoutFile;
        } else {
            echo $content;
        }
    }

    protected function jsonResponse(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    protected function redirect(string $url): void
    {
        header('Location: ' . BASE_URL . $url);
        exit;
    }
}
