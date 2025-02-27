<?php

namespace hidev\webapp\components;

/**
 * Class TestEndpoints
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
final class TestEndpoints
{
    public static function try(): void
    {
        $endpoint = self::getEndpoint();
        $handlers = [
            'fullEmptyEndpoint' => 'fullEmptyEndpoint',
            'testRemoteAddrEndpoint' => 'testRemoteAddrEndpoint',
        ];
        $handler = $handlers[$endpoint] ?? null;
        if (isset($handler)) {
            self::$handler();
        }
    }

    private static function fullEmptyEndpoint(): void
    {
        self::respondJson('{}');
    }

    private static function testRemoteAddrEndpoint(): void
    {
        $given_ip = $_GET['ip'] ?? 'given_ip';
        $REMOTE_ADDR = $_SERVER['REMOTE_ADDR'] ?? 'REMOTE_ADDR';
        self::respondJson([
            'REMOTE_ADDR' => $REMOTE_ADDR,
            'HTTP_X_FORWARDED_FOR' => $_SERVER['HTTP_X_FORWARDED_FOR'] ?? null,
            'given_ip' => $given_ip,
            'equals' => (string)$given_ip === (string)$REMOTE_ADDR,
        ]);
    }

    private static function respondJson(array|string $data): void
    {
        header('Content-Type: application/json; charset=UTF-8');
        if (is_array($data)) {
            $data = json_encode($data, JSON_THROW_ON_ERROR);
        }
        echo $data;
        die();
    }

    private static function getEndpoint(): string
    {
        return trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    }

    private function __construct()
    {
    }
}
