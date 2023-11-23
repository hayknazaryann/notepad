<?php


namespace App\Services;


use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class ClientService
{
    const MIN_DELAY = 2000;
    const MAX_DELAY = 5000;

    protected $client;

    protected $stack;

    protected $options = [];

    protected $proxies;

    protected $current_proxy = 0;

    public function __construct()
    {
        $proxies = config('app.guzzle_proxies');
        $this->proxies = array_filter($proxies, function ($item) {
            return $item;
        });
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options): ClientService
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @param array $options
     */
    public function addOptions(array $options): ClientService
    {
        $this->options = array_merge($this->options, $options);

        return $this;
    }

    public function newRequest(string $url, array $options = [], string $method = 'GET')
    {
        if (!$this->client) {
            $this->client = new Client();
        }

        $options = array_merge($this->getDefaultOptions(), $this->options, $options);

        return $this->client->request($method, $url, $options);
    }

    protected function getHtml(string $url, array $options = [], string $method = 'GET')
    {
        return $this->newRequest($url, $options, $method)->getBody()->getContents();
    }

    public function getCrawler(string $url, array $options = [], string $method = 'GET')
    {
        $html = $this->getHtml($url, $options, $method);

        $crawler = new Crawler();
        $crawler->addHtmlContent($html, 'UTF-8');

        return $crawler;
    }

    protected function getDefaultOptions()
    {
        $options = [ 'delay' => random_int(static::MIN_DELAY, static::MAX_DELAY) ];
        if (count($this->proxies)) {
            $options['proxy'] = $this->proxies[$this->current_proxy];
            $this->current_proxy = $this->current_proxy > (count($this->proxies) - 2)
                ? 0
                : $this->current_proxy + 1;
        }

        return $options;
    }
}
