<?php

declare(strict_types=1);

namespace url;

function parse(string $url) {
    $url = trim($url);
    $protocolIndex = strpos($url, "://");
    $guessProtocols = $protocolIndex !== false ? array_filter(explode('+', substr($url, 0, $protocolIndex))) : [''];

    $output = [
        'scheme' => null,
        'port' => null,
        'host' => "",
        'user' => "",
        'pass' => "",
        'path' => "",
        'fragment' => "",
        'query' => "",
        'href' => $url,
    ];

    $resourceIndex = -1;
    $splits = null;
    $parts = null;

    if (strpos($url, '.') === 0) {
        if (strpos($url,"./") === 0) {
            $url = substr($url, 2);
        }

        $output['path'] = $url;
        $output['scheme'] = "file";
    }


    if (!$output['scheme']) {
        $output['scheme'] = $guessProtocols[0];

        if (!$output['scheme']) {
            $firstChar = $url[0];
            if (strpos($url, 'www.') === 0) {
                $output['scheme'] = "http";
            } elseif ($firstChar === "/" || $firstChar === "~") {
                $url = substr($url, 2);
                $output['scheme'] = "file";
            } else {
                $output['scheme'] = "file";                
            }
        }
    }

    if ($protocolIndex !== false) {
        $url = substr($url, $protocolIndex + 3);
    }

    $parts = preg_split("/\/|\\\\/", $url);
    if ($output['scheme'] !== "file") {
        $output['host'] = array_shift($parts);
    } else {
        $output['host'] = "";
    }

    // user@domain
    $splits = explode('@', $output['host']);
    if (count($splits) === 2) {
        list($output['user'], $output['pass']) = explode(":", $splits[0], 2);
        $output['host'] = $splits[1];
    }

    // domain.com:port
    $splits = explode(":", $output['host']);
    if (count($splits) === 2) {
        $output['host'] = $splits[0];
        if ($splits[1]) {
            $output['port'] = +$splits[1];
            if (is_nan($output['port'])) {
                $output['port'] = null;
                array_unshift($parts[1]);
            }
        } else {
            $output['port'] = null;
        }
    }

    // Remove empty elements
    $parts = array_filter($parts);

    // Stringify the pathname
    if ($output['scheme'] === "file") {
        $output['path'] = $output['href'];
    } else {
        $output['path'] = $output['path'] ?: (($output['scheme'] !== "file" || $output['href'][0] === "/" ? "/" : "") . implode("/", $parts));
    }

    // #some-hash
    $splits = explode('#', $output['path']);
    if (count($splits) === 2) {
        $output['path'] = $splits[0];
        $output['fragment'] = $splits[1];
    }

    // ?foo=bar
    $splits = explode("?", $output['path']);
    if (count($splits) === 2) {
        $output['path'] = $splits[0];
        $output['query'] = $splits[1];
    }

    $output['href'] = rtrim($output['href'], "/");
    $output['path'] = rtrim($output['path'], "/");

    return $output;
}