<?php

namespace App\Service;

class DockerService
{
    private $socket;

    public function __construct()
    {
        $this->socket = socket_create(AF_UNIX, SOCK_STREAM, 0);
        socket_connect($this->socket, '/var/run/docker.sock');
    }

    public function listContainers(): array
    {
        $request = "GET /containers/json?all=true HTTP/1.1\r\nHost: localhost\r\nConnection: close\r\n\r\n";
        socket_write($this->socket, $request);

        $response = '';
        while ($read = socket_read($this->socket, 2048)) {
            $response .= $read;
        }

        list($headers, $body) = explode("\r\n\r\n", $response, 2);

        if (strpos($headers, 'Transfer-Encoding: chunked') !== false) {
            $body = $this->decodeChunkedBody($body);
        }

        $containers = json_decode($body, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Invalid JSON: ' . json_last_error_msg());
        }

        return $containers ?? [];
    }

    private function decodeChunkedBody(string $body): string
    {
        $decodedBody = '';
        while (trim($body)) {
            $pos = strpos($body, "\r\n");
            $chunkSize = hexdec(substr($body, 0, $pos));
            $decodedBody .= substr($body, $pos + 2, $chunkSize);
            $body = substr($body, $pos + 2 + $chunkSize + 2);
        }
        return $decodedBody;
    }

    public function startContainer(string $id): array
    {
        $request = "POST /containers/$id/start HTTP/1.1\r\nHost: localhost\r\nConnection: close\r\n\r\n";
        socket_write($this->socket, $request);

        $response = '';
        while ($read = socket_read($this->socket, 2048)) {
            $response .= $read;
        }

        list($headers, $body) = explode("\r\n\r\n", $response, 2);

        preg_match('#HTTP/1\.[01] (\d{3})#', $headers, $matches);
        $statusCode = $matches[1] ?? 500;

        return ['status' => (int)$statusCode];
    }

    public function stopContainer(string $id): array
    {
        $request = "POST /containers/$id/stop HTTP/1.1\r\nHost: localhost\r\nConnection: close\r\n\r\n";
        socket_write($this->socket, $request);

        $response = '';
        while ($read = socket_read($this->socket, 2048)) {
            $response .= $read;
        }

        list($headers, $body) = explode("\r\n\r\n", $response, 2);

        preg_match('#HTTP/1\.[01] (\d{3})#', $headers, $matches);
        $statusCode = $matches[1] ?? 500;

        return ['status' => (int)$statusCode];
    }

    public function __destruct()
    {
        socket_close($this->socket);
    }
}