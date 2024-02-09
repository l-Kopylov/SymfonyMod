<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PartsControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Результат!');
    }


    public function testPartsPage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertCount(3, $crawler->filter('h4'));

        $client->clickLink('View');

        $this->assertPageTitleContains('Блог 1');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Блог 1 Раздел постов');
        // $this->assertSelectorExists('div:contains("Здесь 2 записей.)');
    }
}