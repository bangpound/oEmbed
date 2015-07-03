<?php

require_once 'vendor/autoload.php';

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

$client = new Client();
/** @var Crawler $crawler */
$crawler = $client->request('get', 'http://www.oembed.com');
$crawler = $crawler->filterXPath('//a[@id = \'section7.1\']')->nextAll();
$crawler->removeAll($crawler->filterXPath('//a[@id = \'section7.2\']')->nextAll());
$crawler->removeAll($crawler->filterXPath('//a[@id = \'section7.2\']'));

$data = $crawler->filterXPath('//p')->each(function(Crawler $nameCrawler, $i) use ($crawler) {
    $data = array(
        'name' => preg_replace('/^(.+?) \(.+?\)$/', '\1', trim($nameCrawler->text())),
        'url' => $nameCrawler->filterXPath('//a')->extract('href')[0],
    );

    $siblings = $crawler->filterXPath('//p')->eq($i)->nextAll();
    $siblings->removeAll($siblings->filterXPath('//p')->eq(0)->nextAll());
    $siblings->removeAll($siblings->filterXPath('//p')->eq(0));

    $providers = $siblings->filter('ul')->each(function(Crawler $crawler) {
        $provider = array();
        $crawler->filterXPath('//li')->each(function(Crawler $crawler) use (&$provider) {

            // Key names are letters and spaces only.
            $key = strtolower(preg_replace('/^([A-Za-z<>\s\(\)]+?):.+?$/', '\1', trim($crawler->text())));

            switch ($key) {

                case 'url scheme':
                case 'url scheme (group video)':
                case 'url scheme (video)':
                case 'url scheme (videos)':
                    $key = 'scheme';
                    $value = $crawler->filterXPath('//code')->extract('_text');
                    break;

                case 'api endpoint':
                    $key = 'endpoint';
                case 'endpoint':
                    $value = $crawler->filterXPath('//code')->extract('_text');
                    break;

                case 'supports discovery via <link> tags':
                    $key = 'discovery';
                    $value = array(true);
                    break;

                case 'docs':
                    $key = 'documentation';
                case 'documentation':
                case 'example':
                    $value = $crawler->filterXPath('//a')->extract('href');
                    break;

                default:
                    $key = 'note';
                    $value = (array) trim($crawler->html());
                    break;
            }

            $value = array_filter((array) $value);

            // Missing or unparseable values are stashed in the data.
            if (empty($value)) {
                $key = '_error';
                $value = (array) trim($crawler->text());
            }

            // Always turn duplicate values into an array.
            if (isset($provider[$key])) {
                $provider[$key] = (array) $provider[$key];
                $provider[$key][] = $value[0];
            }
            // Always make example and scheme values an array.
            elseif (in_array($key, array('example', 'scheme'))) {
                $provider[$key][] = $value[0];
            } else {
                $provider[$key] = $value[0];
            }
        });

        if (isset($provider['example'])) {
            foreach ($provider['example'] as $example) {
                $url = parse_url($example);
                if (isset($url['query'])) {
                    $query = array();
                    parse_str($url['query'], $query);
                    if (isset($query['url'])) {
                        $provider['test'][] = $query['url'];
                    }
                }
            }

            if (isset($provider['test'])) {
                $provider['test'] = array_unique($provider['test']);
            }
        }

        if (isset($provider['endpoint'])) {
            $endpoint = explode(' ', $provider['endpoint'], 2);
            $provider['endpoint'] = array_shift($endpoint);
            if (!empty($endpoint)) {
                if ($endpoint[0] === '(only supports json)') {
                    $provider['default']['format'] = 'json';
                    $provider['requirement']['format'] = 'json';
                }
            }

            $matches = array();
            if (preg_match_all('/\{([^\}]+)\}/', $provider['endpoint'], $matches)) {
                $requirements = array_combine($matches[1], array_fill(0, count($matches[1]), ''));
                if (!isset($provider['requirement'])) {
                    $provider['requirement'] = array();
                }
                $provider['requirement'] = array_merge($requirements, $provider['requirement']);
            }
        }

        return $provider;
    });

    $data['providers'] = $providers;

    return $data;
});

echo json_encode($data);
