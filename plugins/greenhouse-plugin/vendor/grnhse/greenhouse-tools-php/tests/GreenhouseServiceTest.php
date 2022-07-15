<?php

namespace Greenhouse\GreenhouseToolsPhp\Tests;

use Greenhouse\GreenhouseToolsPhp\GreenhouseService;
use Greenhouse\GreenhouseToolsPhp\Services\ApiService;

class GreenhouseServiceTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->apiKey       = 'testapikey';
        $this->boardToken   = 'test_token';
        $this->greenhouseService = new GreenhouseService(array(
            'apiKey'    => $this->apiKey,
            'boardToken'=> $this->boardToken
        ));
    }
    
    public function testConstructWithNoBoardToken()
    {
        $service = new GreenhouseService(array('apiKey' => 'test_key'));
        $this->assertInstanceOf(
            '\Greenhouse\GreenhouseToolsPhp\GreenhouseService',
            $service
        );
    }
    
    public function testConstructWithNoApiKey()
    {
        $service = new GreenhouseService(array('boardToken' => 'test_token'));
        $this->assertInstanceOf(
            '\Greenhouse\GreenhouseToolsPhp\GreenhouseService',
            $service
        );
    }
    
    public function testGetJobBoardService()
    {
        $service = $this->greenhouseService->getJobBoardService();
        $this->assertInstanceOf(
            '\Greenhouse\GreenhouseToolsPhp\Services\JobBoardService',
            $service
        );
        $this->assertContains($this->boardToken, $service->scriptTag());
    }
    
    public function testGetJobApiService()
    {
        $service = $this->greenhouseService->getJobApiService();
        $this->assertInstanceOf(
            '\Greenhouse\GreenhouseToolsPhp\Services\JobApiService',
            $service
        );
        
        $this->assertEquals(
            'https://api.greenhouse.io/v1/boards/test_token/embed/',
            $service->getJobBoardBaseUrl()
        );
        
        $this->assertInstanceOf('\Greenhouse\GreenhouseToolsPhp\Clients\GuzzleClient', $service->getClient());
    }
    
    public function testGetApplicationService()
    {
        $service = $this->greenhouseService->getApplicationApiService();
        $this->assertInstanceOf(
            '\Greenhouse\GreenhouseToolsPhp\Services\ApplicationService',
            $service
        );
        
        $baseUrl = ApiService::jobBoardBaseUrl($this->boardToken);
        $authHeader = 'Basic ' . base64_encode($this->apiKey . ':');
        $this->assertEquals($baseUrl, $service->getJobBoardBaseUrl());
        $this->assertEquals($authHeader, $service->getAuthorizationHeader());
    }
    
    public function testGetHarvestService()
    {
        $service = $this->greenhouseService->getHarvestService();
        $this->assertInstanceOf(
            '\Greenhouse\GreenhouseToolsPhp\Services\HarvestService',
            $service
        );
        $authHeader = 'Basic ' . base64_encode($this->apiKey . ':');
        $this->assertEquals($authHeader, $service->getAuthorizationHeader());
    }
}
