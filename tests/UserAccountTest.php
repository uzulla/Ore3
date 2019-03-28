<?php
declare(strict_types=1);

if (!defined("BASE_DIR")) define("BASE_DIR", __DIR__ . "/../");

require_once(__DIR__ . '/../vendor/autoload.php');

use PHPUnit\Framework\TestCase;

class UserAccountTest extends TestCase
{

    public function createOne()
    {
        $data = [
            'name' => "taro " . microtime(true),
            'email' => "taro" . microtime(true) . "@example.jp",
            'plain_pass' => "password" . microtime(true),
            'id' => null
        ];

        $ua = new \MyApp\Model\UserAccount();
        $ua->name = $data['name'];
        $ua->email = $data['email'];
        $ua->setPassword($data['plain_pass']);

        $last_insert_id = \MyApp\Service\UserAccountService::create($ua);

        $new_ua = \MyApp\Service\UserAccountService::getById($last_insert_id);
        $this->assertInstanceOf(\MyApp\Model\UserAccount::class, $new_ua);

        return [
            'inputs' => $data,
            'id' => $last_insert_id,
            'ua' => $new_ua
        ];
    }

    public function testCreate()
    {
        $test_create = $this->createOne();
        /** @var \MyApp\Model\UserAccount $ua */
        $ua = $test_create['ua'];
        $inputs = $test_create['inputs'];


        $this->assertEquals($inputs['name'], $ua->name);
        $this->assertEquals($inputs['email'], $ua->email);
        $this->assertTrue($ua->passwordVerification($inputs['plain_pass']));
    }

    public function testGetById()
    {
        $test_one = $this->createOne();

        $ua = \MyApp\Service\UserAccountService::getById($test_one['id']);

        $this->assertInstanceOf(\MyApp\Model\UserAccount::class, $ua);

        $this->assertEquals($ua->id, $test_one['id']);
    }

}
