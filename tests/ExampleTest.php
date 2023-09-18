<?php

namespace Tests\Unit;

use Estima\WebScraping\WebScraping;
use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_that_true_is_true()
    {
        $this->assertTrue(true);
    }

    /**
     * Test the WebScraping service.
     * Goes to my Goodreads list and gets the books I've read.
     * 
     * @return void
     */
    public function test_get_webscraping_service()
    {
        $endpoint = 'https://www.goodreads.com/review/list/139885910-jo-o-estima?shelf=read';
        $contentPaths = [
            // tbody#booksBody > tr > td.field.title > div.value > a (attribute title)
            'titles' => '//tbody[@id="booksBody"]//tr//td[@class="field title"]//div[@class="value"]//a//@title', // @learn https://www.w3schools.com/xml/xpath_syntax.asp
        ];

        $books = (new WebScraping)->getWebScraping($endpoint, $contentPaths);

        $this->assertNotEmpty($books);
        $this->assertArrayHasKey('titles', $books);
        $this->assertContains('Flowers for Algernon', $books['titles']);
    }
}
