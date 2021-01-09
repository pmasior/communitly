<?php
class AppController {
    private $request;

    public function __construct() {
        $this->request = $_SERVER['REQUEST_METHOD'];
        // session_start();  // TODO: delete after write session
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

    protected function isPost() {
        return $this->request == 'POST';
    }

    protected function isGet() {
        return $this->request == 'GET';
    }
}

?>