<?php

namespace Greenhouse\GreenhouseToolsPhp\Tests\Clients;

use Greenhouse\GreenhouseToolsPhp\Clients\GuzzleClient;
use Greenhouse\GreenhouseToolsPhp\Tests\Clients\Mocks\MockGuzzleResponse;

class GuzzleClientTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->client = new GuzzleClient(array('base_uri' => 'http://www.example.com'));
        $this->resumePath = realpath(dirname(__FILE__)) . '/../files/documents/test_resume.docx';
    }

    public function testGuzzleInitialize()
    {
        $client = new GuzzleClient(array('base_uri' => 'http://www.example.com'));
        $this->assertInstanceOf(
            '\Greenhouse\GreenhouseToolsPhp\Clients\GuzzleClient',
            $client
        );
        
        $this->assertInstanceOf('\GuzzleHttp\Client', $client->getClient());
    }
    
    public function testGetException()
    {
        $errorUrl = 'https://api.greenhouse.io/v1/boards/exception_co/embed/';
        $client = new GuzzleClient(array('base_uri' => $errorUrl));
        $this->expectException('\Greenhouse\GreenhouseToolsPhp\Clients\Exceptions\GreenhouseAPIResponseException');
        $response = $client->get('jobs');
    }
    
    public function testFormatPostParametersNoFiles()
    {
        $postVars = array(
            'first_name' => 'Hiram',
            'last_name' => 'Abiff',
            'talents' => array('building', 'things', 'and', 'stuff')
        );
        $expected = array(
            ['name' => 'first_name', 'contents' => 'Hiram'],
            ['name' => 'last_name', 'contents' => 'Abiff'],
            ['name' => 'talents[]', 'contents' => 'building'],
            ['name' => 'talents[]', 'contents' => 'things'],
            ['name' => 'talents[]', 'contents' => 'and'],
            ['name' => 'talents[]', 'contents' => 'stuff']
        );
        
        $this->assertEquals($expected, $this->client->formatPostParameters($postVars));
    }
    
    public function testFormatPostParametersWithFiles()
    {
        $testDoc = new \CURLFile($this->resumePath, 'application/msword', 'resume');

        $postVars = array(
            'first_name' => 'Hiram',
            'last_name' => 'Abiff',
            'talents' => array('building', 'things', 'and', 'stuff'),
            'resume' =>  $testDoc
        );
        
        $response = $this->client->formatPostParameters($postVars);
        
        $this->assertEquals($response[0], ['name' => 'first_name', 'contents' => 'Hiram']);
        $this->assertEquals($response[1], ['name' => 'last_name', 'contents' => 'Abiff']);
        $this->assertEquals($response[2], ['name' => 'talents[]', 'contents' => 'building']);
        $this->assertEquals($response[3], ['name' => 'talents[]', 'contents' => 'things']);
        $this->assertEquals($response[4], ['name' => 'talents[]', 'contents' => 'and']);
        $this->assertEquals($response[5], ['name' => 'talents[]', 'contents' => 'stuff']);
        $this->assertEquals($response[6]['name'],       'resume');
        $this->assertEquals($response[6]['filename'],   'resume');
    }
    
    public function testLinksAllIncluded()
    {
        $linksResponse = array(
            '<https://harvest.greenhouse.io/v1/candidates?page=3&per_page=100>; rel="next",' .
            '<https://harvest.greenhouse.io/v1/candidates?page=1&per_page=100>; rel="prev",' .
            '<https://harvest.greenhouse.io/v1/candidates?page=8273&per_page=100>; rel="last"'
        );

        $mockResponse = $this->createMock('Greenhouse\GreenhouseToolsPhp\Tests\Clients\Mocks\MockGuzzleResponse');
        $mockResponse->method('getHeader')
                     ->willReturn($linksResponse);
        $this->client->guzzleResponse = $mockResponse;

        $reflector = new \ReflectionClass('Greenhouse\GreenhouseToolsPhp\Clients\GuzzleClient');
        $method = $reflector->getMethod('_setLinks');
        $method->setAccessible(true);
        
        $this->assertEquals($this->client->getNextLink(), '');
        $this->assertEquals($this->client->getPrevLink(), '');
        $this->assertEquals($this->client->getLastLink(), '');
        
        $method->invokeArgs($this->client, array());

        $this->assertEquals($this->client->getNextLink(), 'https://harvest.greenhouse.io/v1/candidates?page=3&per_page=100');
        $this->assertEquals($this->client->getPrevLink(), 'https://harvest.greenhouse.io/v1/candidates?page=1&per_page=100');
        $this->assertEquals($this->client->getLastLink(), 'https://harvest.greenhouse.io/v1/candidates?page=8273&per_page=100');
    }
    
    public function testLinksNoneIncluded()
    {
        $linksResponse = array('');
        
        $mockResponse = $this->createMock('Greenhouse\GreenhouseToolsPhp\Tests\Clients\Mocks\MockGuzzleResponse');
        $mockResponse->method('getHeader')
                     ->willReturn($linksResponse);
        $this->client->guzzleResponse = $mockResponse;

        $reflector = new \ReflectionClass('Greenhouse\GreenhouseToolsPhp\Clients\GuzzleClient');
        $method = $reflector->getMethod('_setLinks');
        $method->setAccessible(true);
        
        $this->assertEquals($this->client->getNextLink(), '');
        $this->assertEquals($this->client->getPrevLink(), '');
        $this->assertEquals($this->client->getLastLink(), '');
        
        $method->invokeArgs($this->client, array());

        $this->assertEquals($this->client->getNextLink(), '');
        $this->assertEquals($this->client->getPrevLink(), '');
        $this->assertEquals($this->client->getLastLink(), '');
    }
    
    public function testLinksSomeIncluded()
    {
        $linksResponse = array(
            '<https://harvest.greenhouse.io/v1/candidates?page=1&per_page=100>; rel="prev",' .
            '<https://harvest.greenhouse.io/v1/candidates?page=8273&per_page=100>; rel="last"'
        );
        
        $mockResponse = $this->createMock('Greenhouse\GreenhouseToolsPhp\Tests\Clients\Mocks\MockGuzzleResponse');
        $mockResponse->method('getHeader')
                     ->willReturn($linksResponse);
        $this->client->guzzleResponse = $mockResponse;

        $reflector = new \ReflectionClass('Greenhouse\GreenhouseToolsPhp\Clients\GuzzleClient');
        $method = $reflector->getMethod('_setLinks');
        $method->setAccessible(true);
        
        $this->assertEquals($this->client->getNextLink(), '');
        $this->assertEquals($this->client->getPrevLink(), '');
        $this->assertEquals($this->client->getLastLink(), '');
        
        $method->invokeArgs($this->client, array());

        $this->assertEquals($this->client->getNextLink(), '');
        $this->assertEquals($this->client->getPrevLink(), 'https://harvest.greenhouse.io/v1/candidates?page=1&per_page=100');
        $this->assertEquals($this->client->getLastLink(), 'https://harvest.greenhouse.io/v1/candidates?page=8273&per_page=100');    
    }
}