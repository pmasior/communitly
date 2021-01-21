<?php
require_once 'AppController.php';
require_once __DIR__ . '/../models/File.php';
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
    private $relativeUploadDirectory;
    private $messages = [];
    private $statementRepository;

    public function __construct() {
        parent::__construct();
        $this->statementRepository = new StatementRepository();
        $this->relativeUploadDirectory = dirname(dirname(__DIR__)) . self::UPLOAD_DIRECTORY;
    }

    public function addStatement() {
        (new Session())->handleSession(true);
        if (!$this->isPost() || !$this->isValidateStatement()) {
            header('Location: /dashboard');
        }

        $statement = $this->createStatementInstance();
        $statementId = $this->statementRepository->addStatement($statement);

        if ($_POST['thread']) {
            foreach ($_POST['thread'] as $thread) {
                $this->statementRepository->associateStatementWithThread($statementId, $thread);
            }
        }

        for ($i = 0; $i < count($_FILES['attachment']['tmp_name']); $i++) {
            if ($_FILES['attachment']['error'][$i] == UPLOAD_ERR_OK) {
                $file = $this->createFileInstance($i);
                if ($this->moveFile($file)) {
                    $attachmentId = $this->statementRepository->addAttachment($file, $statementId);
                }
            }
        }

        header('Location: /dashboard');
    }

    public function confirmStatement() {
        (new Session())->handleSession(true);
        if (!$this->isPost()) {
            header('Location: /dashboard');
        }

        $statementId = $_POST['statementId'];
        $userId = $_POST['userId'];
        $approveDate = (new DateTime())->format('c');

        $this->statementRepository->confirmStatement($userId, $statementId, $approveDate);

        header('Location: /dashboard');
    }

    public function undoConfirmStatement() {
        (new Session())->handleSession(true);
        if (!$this->isPost()) {
            header('Location: /dashboard');
        }

        $statementId = $_POST['statementId'];
        $userId = $_POST['userId'];

        $this->statementRepository->undoConfirmStatement($userId, $statementId);

        header('Location: /dashboard');
    }

    public function editStatement() {
        (new Session())->handleSession(true);
        if (!$this->isPost() || !$this->isValidateStatement()) {
            header('Location: /dashboard');
        }

        $statement = $this->createStatementInstance();
        $statementId = $this->statementRepository->editStatement($statement);

        if ($_POST['thread']) {
            foreach ($_POST['thread'] as $thread) {
                $this->statementRepository->associateStatementWithThread($statementId, $thread);
            }
        }

        for ($i = 0; $i < count($_FILES['attachment']['tmp_name']); $i++) {
            if ($_FILES['attachment']['error'][$i] == UPLOAD_ERR_OK) {
                $file = $this->createFileInstance($i);
                if ($this->moveFile($file)) {
                    $attachmentId = $this->statementRepository->addAttachment($file, $statementId);
                }
            }
        }

        header('Location: /dashboard');

    }

    private function isValidateStatement() {
        return $_POST['statement-header']
            && $_POST['statement-content']
            && $_POST['statement-url'];
    }

    private function createStatementInstance() {
        return new Statement(
            $_POST['statementId'],
            $_POST['statement-header'], 
            $_POST['statement-content'], 
            new DateTime(), 
            $_SESSION['userId'],
            // NULL, 
            // NULL, 
            // $_FILES['attachment']['name'], 
            $_POST['statement-url']
        );
    }

    private function createFileInstance($i) {
        return new File(
            $_FILES['attachment']['name'][$i],
            $_FILES['attachment']['type'][$i],
            $_FILES['attachment']['tmp_name'][$i], 
            $_FILES['attachment']['error'][$i],
            $_FILES['attachment']['size'][$i]
        );
    }

    private function moveFile($file): bool {
        if ($this->isFilesizeValid($file->getSize())
            && $this->isFiletypeSupported($file->getType())
            && is_uploaded_file($file->getTmpName())
            ) {
            return move_uploaded_file($file->getTmpName(), $this->relativeUploadDirectory . $file->getName());
        }
    }

    private function isFilesizeValid($size): bool {
        if ($size > self::MAX_FILE_SIZE) {
            $this->messages[] = 'File is too large for upload';
            return false;
        }
        return true;
    }

    private function isFiletypeSupported(string $type): bool {
        if (!isset($type) 
            && !in_array($type, self::SUPPORTED_FILE_TYPES)) {
            $this->messages[] = 'File type is not supported';
            return false;
        }
        return true;
    }
}