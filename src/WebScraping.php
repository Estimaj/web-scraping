<?php

namespace Estima\WebScraping;

use DOMDocument;
use DOMXPath;
use GuzzleHttp\Client;

class WebScraping
{
    private $httpClient = null;

    /**
     * WebScraping constructor.
     */
    public function __construct()
    {
        $this->httpClient = new Client();
    }

    /**
     * Get the content from a website.
     * Transform the content into an array.
     * Keeping the keys from the $contentPaths array.
     * 
     * @param string $endpoint
     * @param array $contentPaths
     * @return array
     */
    public function getWebScraping(String $endpoint, Array $contentPaths)
    {
        $response = $this->httpClient->get($endpoint);
        $htmlString = (string) $response->getBody();

        //add this line to suppress any warnings
        libxml_use_internal_errors(true);
        $doc = new DOMDocument();
        $doc->loadHTML($htmlString);
        $xpath = new DOMXPath($doc);

        $extractedContent = [];
        foreach ($contentPaths as $key => $path) {
            $extractedContent[$key] = $this->extractContent($xpath, $path);
        }

        return $extractedContent;
    }

    /**
     * Extract the content from the DOMXPath query.
     * 
     * @param DOMXPath $xpath
     * @param string $query
     * @return array
     */
    private function extractContent(DOMXPath $xpath, string $query)
    {
        $content = $xpath->evaluate($query);
        $extractedContent = [];
        foreach ($content as $item) {
            $extractedContent[] = trim($item->textContent.PHP_EOL);
        }

        return $extractedContent;
    }
}
