<?php
require_once 'AppController.php';
require_once __DIR__ . '/../models/Link.php';
require_once __DIR__ . '/../repository/LinkRepository.php';

class LinkController extends AppController {
    private LinkRepository $linkRepository;

    public function __construct() {
        parent::__construct();
        $this->linkRepository = new LinkRepository();
    }

    public function addLink() {
        (new Session())->handleSession(true);
        if (!$this->isPost() || !$this->isValidateLink()) {
            (new SubgroupController())->dashboard(['Brak danych w polach tytuł lub link']);
            header('Location: /dashboard');
        }

        $link = $this->createLinkInstance();
        $linkId = $this->linkRepository->addLink($link);

        if ($_POST['thread']) {
            foreach ($_POST['thread'] as $thread) {
                $this->linkRepository->associateLinkWithThread($linkId, $thread);
            }
        }

        header('Location: /dashboard');
    }

    public function removeLink() {
        (new Session())->handleSession(true);
        if (!$this->isPost()) {
            header('Location: /dashboard');
        }
        $linkId = $_POST['linkId'];
        $this->linkRepository->removeLink($linkId);
        header('Location: /dashboard');
    }

    private function isValidateLink(): bool {
        return $_POST['linkName']
            && $_POST['linkURL'];
    }

    private function createLinkInstance(): Link {
        return new Link(
            $_POST['linkId'],
            $_POST['linkName'],
            $_POST['linkURL'],
            $_POST['linkNote']
        );
    }
}