<?php

namespace Core\Security;

class Session
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            $this->configure();
            session_start();
        }
        
        $this->regenerateIfNeeded();
    }

    private function configure(): void
    {
        ini_set('session.cookie_httponly', '1');
        ini_set('session.cookie_secure', config('app.env') === 'production' ? '1' : '0');
        ini_set('session.cookie_samesite', 'Lax');
        ini_set('session.use_strict_mode', '1');
        ini_set('session.gc_maxlifetime', '3600');
        ini_set('session.use_only_cookies', '1');
    }

    private function regenerateIfNeeded(): void
    {
        if (!isset($_SESSION['_last_regeneration'])) {
            $this->regenerate();
            return;
        }

        $regenerateAfter = 300; // 5 minutes
        if (time() - $_SESSION['_last_regeneration'] > $regenerateAfter) {
            $this->regenerate();
        }
    }

    public function regenerate(): void
    {
        session_regenerate_id(true);
        $_SESSION['_last_regeneration'] = time();
    }

    public function destroy(): void
    {
        $_SESSION = [];
        
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }
        
        session_destroy();
    }
} 