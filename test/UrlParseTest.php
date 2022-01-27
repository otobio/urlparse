<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use function url\parse as urlparse;

require 'vendor/autoload.php';

class UrlParseTest extends TestCase {
    public function testHttpBasedVariousUrls()
    {
        $inputs = [
            [
                'input' => 'https://attacker.com\\@example.com',
                'output' => [
                    'scheme' => "https",
                    'port' => null,
                    'host' => "attacker.com",
                    'user' => "",
                    'pass' => "",
                    'path' => "/@example.com",
                    'fragment' => "",
                    'query' => "",
                    'href' => 'https://attacker.com\\@example.com'
                ]
            ],
            [
                'input' => 'http://domain.com/path/name#some-hash?foo=bar&bar=42',
                'output' => [
                    'scheme' => "http",
                    'port' => null,
                    'host' => "domain.com",
                    'user' => "",
                    'pass' => "",
                    'path' => "/path/name",
                    'fragment' => "some-hash?foo=bar&bar=42",
                    'query' => "",
                    'href' => 'http://domain.com/path/name#some-hash?foo=bar&bar=42'
                ]
            ],
            [
                'input' => 'http://domain.com/path/name?foo=bar&bar=42#some-hash',
                'output' => [
                    'scheme' => "http",
                    'port' => null,
                    'host' => "domain.com",
                    'user' => "",
                    'pass' => "",
                    'path' => "/path/name",
                    'fragment' => "some-hash",
                    'query' => "foo=bar&bar=42",
                    'href' => 'http://domain.com/path/name?foo=bar&bar=42#some-hash'
                ],
            ],
            [
                'input' => 'http://ionicabizau.net/blog',
                'output' => [
                    'scheme' => "http",
                    'port' => null,
                    'host' => "ionicabizau.net",
                    'user' => "",
                    'pass' => "",
                    'path' => "/blog",
                    'fragment' => "",
                    'query' => "",
                    'href' => 'http://ionicabizau.net/blog'
                ]
            ],
            [
                'input' => 'https://www.attacker.com\\@example.com',
                'output' => [
                    'scheme' => "https",
                    'port' => null,
                    'host' => "www.attacker.com",
                    'user' => "",
                    'pass' => "",
                    'path' => "/@example.com",
                    'fragment' => "",
                    'query' => "",
                    'href' => 'https://www.attacker.com\\@example.com'
                ]
            ],
            [
                'input' => 'www.attacker.com\\@example.com',
                'output' => [
                    'scheme' => "http",
                    'port' => null,
                    'host' => "www.attacker.com",
                    'user' => "",
                    'pass' => "",
                    'path' => "/@example.com",
                    'fragment' => "",
                    'query' => "",
                    'href' => 'www.attacker.com\\@example.com'
                ]
            ],
            [
                'input' => 'http://user:pass@domain.com/path/name?foo=bar&bar=42#some-hash',
                'output' => [
                    'scheme' => "http",
                    'port' => null,
                    'host' => "domain.com",
                    'user' => "user",
                    'pass' => "pass",
                    'path' => "/path/name",
                    'fragment' => "some-hash",
                    'query' => "foo=bar&bar=42",
                    'href' => 'http://user:pass@domain.com/path/name?foo=bar&bar=42#some-hash'
                ],
            ],            
        ];

        foreach ($inputs as $testCase) {
            $this->assertEquals(urlparse($testCase['input']), $testCase['output']);
        }
    }
}