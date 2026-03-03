<?php
// src/Core/Request.php
namespace App\Core;

class Request
{
    private $method;
    private $path;
    private $query;
    private $body;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $this->path = parse_url($uri, PHP_URL_PATH) ?: '/';
        $this->query = $_GET;
        $this->body = $_POST;
    }

    public function getMethod(): string { return strtoupper($this->method); }
    public function getPath(): string { return '/' . ltrim($this->path, '/'); }

    public function getQueryParam(string $key, $default = null)
    { return isset($this->query[$key]) ? $this->query[$key] : $default; }

    public function getBodyParam(string $key, $default = null)
    { return isset($this->body[$key]) ? $this->body[$key] : $default; }

    public function allBody(): array { return $this->body; }
    public function allQuery(): array { return $this->query; }
}