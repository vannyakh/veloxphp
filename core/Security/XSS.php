<?php

namespace Core\Security;

class XSS
{
    public function clean($data)
    {
        if (is_array($data)) {
            return array_map([$this, 'clean'], $data);
        }

        if (is_string($data)) {
            return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        }

        return $data;
    }

    public function cleanAllowHtml(string $data): string
    {
        return strip_tags($data, [
            'p', 'br', 'b', 'strong', 'i', 'em', 'u', 'a', 'ul', 'ol', 'li'
        ]);
    }
} 