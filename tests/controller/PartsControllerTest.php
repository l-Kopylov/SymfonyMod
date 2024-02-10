<?php

namespace App\Tests\Controller;

use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
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
    public function testCommentSubmission()
    {
        $client = static::createClient();
        $client->request('GET', '/parts/part0-777');
        $client->submitForm('Submit', [
            'comment[autor]' => 'Fabien',
            'comment[text]'  => 'Some feedback from an automated functional test',
            'comment[email]' => $email ='me@autoimat.ed',
            'comment[photo]' => dirname(__DIR__, 2).'/public/images/service.gif',
        ]);
        $this->assertResponseRedirects();

        $comment = self::getContainer()->get(CommentRepository::class)->findOneByEmail($email);
        $comment->setState('published');
        self::getContainer()->get(EntityManagerInterface::class)->flush();

        $client->followRedirect();
        $this->assertSelectorExists('div:contains("Здесь 2 записей.")');
    }
}