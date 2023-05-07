<?php

namespace App\Tests\Infrastructure\UI\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class MaximizeControllerTest extends WebTestCase
{
    public function testMaximizeExampleUseCase(): void
    {
        $client = static::createClient();

        $jsonPayload = '[
{
"request_id":"bookata_XY123",
"check_in":"2020-01-01",
"nights":5,
"selling_rate":200,
"margin":20
},
{
"request_id":"kayete_PP234",
"check_in":"2020-01-04",
"nights":4,
"selling_rate":156,
"margin":5
},
{
"request_id":"atropote_AA930",
"check_in":"2020-01-04",
"nights":4,
"selling_rate":150,
"margin":6
},
{
"request_id":"acme_AAAAA",
"check_in":"2020-01-10",
"nights":4,
"selling_rate":160,
"margin":30
}
]';

        $client->request('POST', '/maximize', [], [], ['CONTENT_TYPE' => 'application/json'], $jsonPayload);

        self::assertResponseIsSuccessful();
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());

        $responseData = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $this->assertEquals(['bookata_XY123', 'acme_AAAAA'], $responseData['request_ids']);
        $this->assertEquals(88, $responseData['total_profit']);
        $this->assertEquals(10, $responseData['avg_night']);
        $this->assertEquals(8, $responseData['min_night']);
        $this->assertEquals(12, $responseData['max_night']);
    }

    public function testMaximizeMissingField(): void
    {
        $client = static::createClient();

        $jsonPayload = '[
{
"check_in":"2020-01-01",
"nights":5,
"selling_rate":200,
"margin":20
},
{
"request_id":"kayete_PP234",
"check_in":"2020-01-04",
"nights":4,
"selling_rate":156,
"margin":5
},
{
"request_id":"atropote_AA930",
"check_in":"2020-01-04",
"nights":4,
"selling_rate":150,
"margin":6
},
{
"request_id":"acme_AAAAA",
"check_in":"2020-01-10",
"nights":4,
"selling_rate":160,
"margin":30
}
]';

        $client->request('POST', '/maximize', [], [], ['CONTENT_TYPE' => 'application/json'], $jsonPayload);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());

        $responseData = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals("Error! Field 'request_id' is mandatory", $responseData['message']);

    }
}