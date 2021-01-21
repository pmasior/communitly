<?php
require_once __DIR__ . '/../session/Session.php';

class AppController {
    private $request;

    public function __construct() {
        $this->request = $_SERVER['REQUEST_METHOD'];
    }

    protected function render(string $template = null, array $variables = []) {
        $templatePath = 'public/views/' . $template . '.php';
        $output = 'File not found';  // TODO: 404

        if (file_exists($templatePath)) {
            extract($variables);
            ob_start();
            include $templatePath;
            $output = ob_get_clean();
        }

        print $output;
    }

    protected function isPost(): bool {
        return $this->request == 'POST';
    }

    protected function isGet(): bool {
        return $this->request == 'GET';
    }
}

?>