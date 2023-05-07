<?php

namespace App\Tests\Infrastructure\UI\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class StatsControllerTest extends WebTestCase
{
    public function testStatsExampleUseCase(): void
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
"margin":22
}
]';

        $client->request('POST', '/stats', [], [], ['CONTENT_TYPE' => 'application/json'], $jsonPayload);

        self::assertResponseIsSuccessful();
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());

        $responseData = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $this->assertEquals(8.29, $responseData['avg_night']);
        $this->assertEquals(8, $responseData['min_night']);
        $this->assertEquals(8.58, $responseData['max_night']);
    }

    public function testStatsExampleUseCase2(): void
    {
        $client = static::createClient();

        $jsonPayload = '[
{
"request_id":"bookata_XY123",
"check_in":"2020-01-01",
"nights":1,
"selling_rate":50,
"margin":20
},
{
"request_id":"kayete_PP234",
"check_in":"2020-01-04",
"nights":1,
"selling_rate":55,
"margin":22
},
{
"request_id":"trivoltio_ZX69",
"check_in":"2020-01-07",
"nights":1,
"selling_rate":49,
"margin":21
}
]';

        $client->request('POST', '/stats', [], [], ['CONTENT_TYPE' => 'application/json'], $jsonPayload);

        self::assertResponseIsSuccessful();
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());

        $responseData = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $this->assertEquals(10.80, $responseData['avg_night']);
        $this->assertEquals(10, $responseData['min_night']);
        $this->assertEquals(12.1, $responseData['max_night']);
    }

    public function testStatsMissingField(): void
    {
        $client = static::createClient();

        $jsonPayload = '[
{
"request_id":"bookata_XY123",
"check_in":"2020-01-01",
"selling_rate":200,
"margin":20
},
{
"request_id":"kayete_PP234",
"check_in":"2020-01-04",
"nights":4,
"selling_rate":156,
"margin":22
}
]';

        $client->request('POST', '/stats', [], [], ['CONTENT_TYPE' => 'application/json'], $jsonPayload);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());

        $responseData = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals("Error! Field 'nights' is mandatory", $responseData['message']);
    }
}