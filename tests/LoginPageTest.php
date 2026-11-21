<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class LoginPageTest extends WebTestCase
{
    public function testLoginPageRendersWithACsrfToken(): void
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/login');

        self::assertResponseIsSuccessful();
        self::assertNotEmpty($crawler->filter('input[name="_csrf_token"]')->attr('value'));
    }
}
