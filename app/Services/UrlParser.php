<?php

namespace App\Services;

class UrlParser
{
    public function parse(string $url): array
    {
        $result = [
            'scheme'   => null,
            'host'     => null,
            'port'     => null,
            'user'     => null,
            'pass'     => null,
            'path'     => null,
            'query'    => null,
            'fragment' => null,
        ];

        // Схема (http, https, ftp, etc.)
        if (preg_match('#^([a-z][a-z0-9+\-.]*):\/\/#i', $url, $matches)) {
            $result['scheme'] = strtolower($matches[1]);
            $url = substr($url, strlen($matches[0]));
        }

        // Разделяем фрагмент (#...)
        if (strpos($url, '#') !== false) {
            [$url, $fragment] = explode('#', $url, 2);
            $result['fragment'] = $fragment;
        }

        // Разделяем query (?...)
        if (strpos($url, '?') !== false) {
            [$url, $query] = explode('?', $url, 2);
            $result['query'] = $query;
        }

        // Разделяем user:pass@host
        if (strpos($url, '@') !== false) {
            [$auth, $url] = explode('@', $url, 2);
            if (strpos($auth, ':') !== false) {
                [$user, $pass] = explode(':', $auth, 2);
                $result['user'] = $user;
                $result['pass'] = $pass;
            } else {
                $result['user'] = $auth;
            }
        }

        // Определяем host:port/path
        if (preg_match('#^\[([a-f0-9:]+)\]#i', $url, $matches)) {
            // IPv6 [::1]
            $result['host'] = $matches[1];
            $url = substr($url, strlen($matches[0]));
        } elseif (preg_match('#^([^/:]+)#', $url, $matches)) {
            // IPv4 или домен
            $result['host'] = $matches[1];
            $url = substr($url, strlen($matches[0]));
        }

        // Порт
        if (preg_match('#^:(\d+)#', $url, $matches)) {
            $result['port'] = (int) $matches[1];
            $url = substr($url, strlen($matches[0]));
        }

        // Остаток — path
        if ($url) {
            $result['path'] = $url;
        }

        return array_filter($result, fn($v) => $v !== null);
    }
}
