<?php
	
/*
	# Running these test:
	
	cd PHP-RestJSONClient/tests/server
	php -S localhost:1234 &

	cd PHP-RestJSONClient
	phpunit -d error_reporting=3 --bootstrap src/RestJSONClient.php tests/RestJSONClientTest
	
	# Stopping the server:

	kill $(ps S | grep "php -S localhost:1234" | cut -d" " -f2 | head -n 1)
*/
	
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class RestJSONClientTest extends TestCase
{
	public function testCanBeCreated()
    {
	    $defaultClient = new RestJSONClient();
	    $configuredClient = new RestJSONClient("get",
							"http://localhost:1234/group/user?x=1&y=2&z=3",
							"Accept-Language: en-US",
							"",
							"");
	    
	    $this->assertInstanceOf(RestJSONClient::class,$defaultClient);
		$this->assertInstanceOf(RestJSONClient::class,$configuredClient);
		
		return $configuredClient;
    }
    
    /**
     * @depends testCanBeCreated
     */
    public function testConfiguredClient($configuredClient)
    {
	    $request = $configuredClient->get_request();
	    $this->assertInternalType('string',$request);
    }

    public function testValidGET()
    {
	    $tc = new RestJSONClient("get",
							"http://localhost:1234/group/user?x=1&y=2&z=3",
							"Accept-Language: en-US",
							"",
							"");
							
		$request = $tc->get_request();
		$this->assertInternalType('string',$request);
		unset($tc);
    }

	public function testValidPUT()
    {
		$tc = new RestJSONClient("Put",
									"http://localhost:1234/group/user",
									array("Accept-Charset: utf-8","Accept-Language: en-US"),
									array("data" => array("a" => "A","b" => "B","c" => array(1,2,3))),
									"tests/files/xdata.xml");
		$request = $tc->get_request();
		$this->assertInternalType('string',$request);
		unset($tc);
	}
	
	public function testValidPUTWithData()
	{
		$tc = new RestJSONClient("put",
									"http://localhost:1234/group/user",
									array("Accept-Language: en-US"),
									array("data" => array("a" => "A","b" => "B","c" => array(1,2,3))),
									array("tests/files/xdata.xml","tests/files/jsobject.json","tests/files/image.png"));
		$request = $tc->get_request();
		$this->assertInternalType('string',$request);
		unset($tc);		
	}
	
	public function testValidDELETE()
	{
		$tc = new RestJSONClient("delete",
							"http://localhost:1234/group/user/1",
							"",
							"",
							"");
		$request = $tc->get_request();
		$this->assertInternalType('string',$request);
		unset($tc);
	}
	
	public function testWarningQueryMethodWithBody()
    {
	    $tc = new RestJSONClient("head",
							"http://localhost:1234/group/user?x=1&y=2&z=3",
							"Accept-Language: en-US",
							array("data" => array("a","b","c")),
							"");

	    $request = $tc->get_request();
	    $this->assertInternalType('string',$request);
		unset($tc);
    }

	public function testWarningBodyMethodWithQuery()
    {
	    $tc = new RestJSONClient("POST",
							"http://localhost:1234/group/user?x=1",
							array("Accept-Charset: utf-8","Accept-Language: en-US"),
							array("data" => array("a" => "A","b" => "B","c" => array(1,2,3))),
							"");

	    $request = $tc->get_request();
	    $this->assertInternalType('string',$request);
		unset($tc);
    }

	public function testWarningInsecureRequest()
	{
		$tc = new RestJSONClient("HEAD",
									"http://user:password@localhost:1234/group/user/1",
									"",
									"",
									"");

	    $request = $tc->get_request();
	    $this->assertInternalType('string',$request);
		unset($tc);
	}
}


