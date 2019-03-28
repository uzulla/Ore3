<?php
declare(strict_types=1);

require_once(__DIR__ . '/../vendor/autoload.php');

use PHPUnit\Framework\TestCase;

class HelloTest extends TestCase
{
    public function testRequest()
    {
        $req = new \MyApp\Api\Request\HelloRequest([
            'name' => "taro"
        ]);
        $this->assertTrue($req->isValid());


        $req = new \MyApp\Api\Request\HelloRequest([
        ]);
        $this->assertFalse($req->isValid());
        $this->assertArrayHasKey('name', $req->getErrorList());


        $req = new \MyApp\Api\Request\HelloRequest([
            'name' => "エラーになる名前"
        ]);
        $this->assertFalse($req->isValid());
        $this->assertArrayHasKey('name', $req->getErrorList());
    }

    public function testAction()
    {
        $test_name = "taro" . time();
        $req = new \MyApp\Api\Request\HelloRequest([
            'name' => $test_name
        ]);
        $this->assertTrue($req->isValid());

        $res = \MyApp\Api\Action\HelloAction::run($req);

        $this->assertInstanceOf(\MyApp\Api\Response\HelloSuccessResponse::class, $res);
    }

    public function testResponse()
    {
        $test_name = "taro" . time();
        $req = new \MyApp\Api\Request\HelloRequest([
            'name' => $test_name
        ]);
        $this->assertTrue($req->isValid());

        /** @var \MyApp\Api\Response\HelloSuccessResponse $res */
        $res = \MyApp\Api\Action\HelloAction::run($req);


        $this->assertInstanceOf(\MyApp\Api\Response\HelloSuccessResponse::class, $res);
        $this->assertEquals(200, $res->code);

        $this->assertInstanceOf(\MyApp\Model\NameSan::class, $res->name_san);
        $this->assertEquals($test_name . "-san", $res->name_san->name);


        $data = json_decode($res->getBody(), true);
        $this->assertEquals(200, $data['code']);
        $this->assertEquals($test_name . "-san", $data['name_with_san']);
    }
}
