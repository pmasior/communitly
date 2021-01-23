<?php
require_once __DIR__ . '/../repository/SessionRepository.php';
require_once __DIR__ . '/../session/Session.php';

class AutoLogin {
    private SessionRepository $sessionRepository;

    public function __construct() {
        $this->sessionRepository = new SessionRepository();
    }

    public function setAutoLogin($userId) {
        $random_auto_login_id = bin2hex(random_bytes(128));
        $this->setAutoLoginCookie($random_auto_login_id);
        $this->sessionRepository->setAutoLoginForUserId($userId,$random_auto_login_id);
        $this->setAutoLoginUpdateCookie();
    }

    public function verifySession(bool $redirectIfNotLogged = true) {
        $userId = $this->checkAutoLoginSession();
        if (!$userId) {
            $this->endSession($redirectIfNotLogged);
            return;
        }

        Session::setSessionVariablesForUser($userId);
        if (!$_COOKIE['not_update_auto_login_until']) {
            $this->updateAutoLoginCookieExpireDate();
            $this->sessionRepository->updateAutoLogin($userId,$_COOKIE['auto_login']);
            $this->setAutoLoginUpdateCookie();
        }
    }

    private function endSession($redirect) {
        foreach ($_COOKIE as $key => $value) {
            setcookie($key, $value, 1, '/');
        }
        if (session_status() == PHP_SESSION_ACTIVE) {
            session_unset();
            session_destroy();
        }
        if ($redirect) {
            header('Location: /');
        }
    }

    private function checkAutoLoginSession() {
        if ($_COOKIE['auto_login']) {
            return $this->sessionRepository->getUserIdForAutoLoginId($_COOKIE['auto_login']);
        }
        return false;
    }

    private function updateAutoLoginCookieExpireDate() {
        $random_auto_login_id = $_COOKIE['auto_login'];
        $expire = time() + 86400;
        setcookie('auto_login', $random_auto_login_id, $expire, '/');
    }

    private function setAutoLoginCookie($random_auto_login_id) {
        $expire = time() + 86400;
        setcookie('auto_login', $random_auto_login_id, $expire, '/');
    }

    private function setAutoLoginUpdateCookie() {
        $expire = time() + 900;
        setcookie('not_update_auto_login_until', true, $expire, '/');
    }
}