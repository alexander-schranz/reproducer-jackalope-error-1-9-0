<?php

namespace App\Tests\Functional\Templates;


use Sulu\Bundle\PageBundle\Document\HomeDocument;
use Sulu\Bundle\TestBundle\Testing\SuluTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class DefaultTest extends SuluTestCase
{
    private KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = $this->createAuthenticatedClient();
        $this->purgeDatabase();
        $this->initPhpcr();
    }

    public function testCreatePage()
    {
        $this->getTestUser();
        $documentManager = $this->getContainer()->get('sulu_document_manager.document_manager');

        /** @var HomeDocument $homepage */
        $homepage = $documentManager->find('/cmf/example/contents', 'en');

        $this->client->request(
            'POST',
            '/admin/api/pages?parentId=' . $homepage->getUuid() . '&webspace=example&action=draft&locale=en',
            [],
            [],
            [
                'HTTP_Accept' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ],
            '{"template":"default","title":"Test","url":"/test","article":null}'
        );

        $response = $this->client->getResponse();

        $this->assertHttpStatusCode(200, $response);
    }
}
