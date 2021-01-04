<?php
require_once 'AppController.php';
require_once __DIR__ . '/../models/Statement.php';
require_once __DIR__ . '/../repository/StatementRepository.php';

class StatementController extends AppController {
    const MAX_FILE_SIZE = 1024 * 1024;
    const SUPPORTED_FILE_TYPES = [
        'application/pdf',  //pdf
        'application/vnd.oasis.opendocument.text',  //odt
        'application/vnd.oasis.opendocument.spreadsheet',  //ods
        'application/zip',  //zip
        'image/png',  //png
        'image/jpg',  //jpg
        'image/jpeg',  //jpeg
        'text/x-csrc',  //c (1)
        'text/x-c',  //c (2, 3)ok
        'text/x-c++',  //c++
        'text/x-c++src',  //c++
        'text/x-python',  //py
        'text/plain',  //txt
        //https://svn.apache.org/repos/asf/httpd/httpd/trunk/docs/conf/mime.types
        //https://developer.mozilla.org/en-US/docs/Web/HTTP/Basics_of_HTTP/MIME_types/Complete_list_of_MIME_types
        'application/msword',  //doc
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',  //docx
        'application/vnd.ms-powerpoint', //ppt
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',  //pptx
        'application/vnd.ms-excel',  //xls
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',  //xlsx
        'application/vnd.oasis.opendocument.presentation',  //odp
        'application/x-sh',  //sh
        'text/x-shellscript',  //sh
        // 'application/x-tar',  //tar
        'application/x-rar-compressed',  //rar
        'application/x-7z-compressed',  //7z
        'image/gif'  //gif
    ];
    const UPLOAD_DIRECTORY = '/public/uploads/'; // TODO: poprawne?
    private $messages = [];
    private $statementRepository;

    public function __construct() {
        parent::__construct();
        $this->statementRepository = new StatementRepository();
    }

    public function addStatement() {
        if ($this->isPost() && is_uploaded_file($_FILES['attachment']['tmp_name']) && $this->validate($_FILES['attachment'])) {
            move_uploaded_file($_FILES['attachment']['tmp_name'], dirname(dirname(__DIR__)) . self::UPLOAD_DIRECTORY . $_FILES['attachment']['name']);
            
            // return $this->render('wdpai', ['messages' -> $this->messages]);
        }
        $statement = new Statement($_POST['statement-header'], $_POST['statement-content'], $_FILES['attachment']['name']);
        $this->statementRepository->addStatement($statement);
        $this->render('wdpai', ['messages' => $this->messages, 'statement' => $statement]);
    }

    private function validate(array $file): bool {
        if ($file['size'] > self::MAX_FILE_SIZE) {
            $this->messages[] = 'File is too large for upload';
            return false;
        }
        if (!isset($file['type']) && !in_array($file['type'], self::SUPPORTED_FILE_TYPES)) {
            $this->messages[] = 'File type is not supported';
            return false;
        }
        return true;
    }
}