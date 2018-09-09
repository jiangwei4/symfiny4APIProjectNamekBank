<?php
namespace App\Tests;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
class MasterControllerTest extends WebTestCase
{
    ///////////////////////////////////////////////tous les utilisateurs ////////////////////////////////////////
   public function testGetMasterAdmin()
    {
        $client = static::createClient();
        $client->request('GET', '/api/masters', [], [], ['HTTP_CONTENT_TYPE' => 'application/json','HTTP_AUTH-TOKEN' => 'keyAdmin']);
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($content);
        $arrayContent = json_decode($content, true);
        $this->assertCount(11, $arrayContent);
    }

    public function testGetMasterMaster()
    {
        $client = static::createClient();
        $client->request('GET', '/api/masters', [], [], ['HTTP_CONTENT_TYPE' => 'application/json','HTTP_AUTH-TOKEN' => 'keyUser']);
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertJson($content);
        $arrayContent = json_decode($content, true);
        $this->assertSame("Not Logged for this user or not an Admin",$arrayContent);
    }

    public function testGetMasterNotLogged()
    {
        $client = static::createClient();
        $client->request('GET', '/api/masters');
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertJson($content);
        $arrayContent = json_decode($content, true);
        $this->assertSame("Not Logged",$arrayContent);
    }
    /////////////////////////////////////////////// utilisateurs n°3 ////////////////////////////////////////
    public function testGetMaster3Admin()
    {
        $client = static::createClient();
        $client->request('GET', '/api/masters/3', [], [], ['HTTP_CONTENT_TYPE' => 'application/json','HTTP_AUTH-TOKEN' => 'keyAdmin']);
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($content);
        $arrayContent = json_decode($content, true);
        $this->assertCount(6, $arrayContent);
    }

    public function testGetMaster3User()
    {
        $client = static::createClient();
        $client->request('GET', '/api/masters/3', [], [], ['HTTP_CONTENT_TYPE' => 'application/json','HTTP_AUTH-TOKEN' => 'keyUser']);
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertJson($content);
        $arrayContent = json_decode($content, true);
        $this->assertCount(6, $arrayContent);
    }

    public function testGetMaster3NotLogged()
    {
        $client = static::createClient();
        $client->request('GET', '/api/masters/3');
        $response = $client->getResponse();
        $content = $response->getContent();

        $this->assertJson($content);
        $arrayContent = json_decode($content, true);
        $this->assertCount(6, $arrayContent);
    }
    public function testGetMaster3456NotLogged()
    {
        $client = static::createClient();
        $client->request('GET', '/api/masters/3456');
        $response = $client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
    }
    ///////////////////////////////////////////////post ////////////////////////////////////////
   public function testPostMaster(){
        $data = [
            "firstname" => "ffff",
            "lastname"=> "Ebert",
            "email"=> "donovan.ferre@outlook.fr"
        ];

        $client = static::createClient();
        $client->request('POST', '/api/masters', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));
        $response = $client->getResponse();
        $content = $response->getContent();

       $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($content);
       $arrayContent = json_decode($content, true);
       // dump($arrayContent = json_decode($content, true));
        $this->assertCount(6, $arrayContent);
    }
    public function testPostMasterBlank(){
        $data = [

        ];

        $client = static::createClient();
        $client->request('POST', '/api/masters', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson($content);
        // dump($arrayContent = json_decode($content, true));
    }

    public function testPostMasterMailExistant(){
        $data = [
            "firstname" => "ffff",
            "lastname"=> "Ebert",
            "email"=> "donovan.ferre@outlook.fr"

        ];

        $client = static::createClient();
        $client->request('POST', '/api/masters', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson($content);
        // dump($arrayContent = json_decode($content, true));
    }
    ///////////////////////////////////////////////delete utilisateur n°3 ////////////////////////////////////////
    public function testDeleteNotlogged(){
        $client = static::createClient();
        $client->request('DELETE', '/api/masters/3', [], [], ['CONTENT_TYPE' => 'application/json']);
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertJson($content);
        $arrayContent = json_decode($content, true);
        $this->assertSame("Not Logged",$arrayContent);
    }
    public function testDeleteNotGoodMaster(){
        $client = static::createClient();
        $client->request('DELETE', '/api/masters/3', [], [], ['CONTENT_TYPE' => 'application/json','HTTP_AUTH-TOKEN' => 'keyUser']);
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertJson($content);
        $arrayContent = json_decode($content, true);
        $this->assertSame("Not the same user or tu n as pas les droits",$arrayContent);
    }
    public function testDeleteGoodMasterAdmin(){
        $client = static::createClient();
        $client->request('DELETE', '/api/masters/3', [], [], ['CONTENT_TYPE' => 'application/json','HTTP_AUTH-TOKEN' => 'keyAdmin']);
        $response = $client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
    }
    public function testDeleteNotGoodMasterAdmin(){
        $client = static::createClient();
        $client->request('DELETE', '/api/masters/3456', [], [], ['CONTENT_TYPE' => 'application/json','HTTP_AUTH-TOKEN' => 'keyAdmin']);
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(404, $response->getStatusCode());
        $arrayContent = json_decode($content, true);
        $this->assertSame("User does note existe",$arrayContent);
    }
    ///////////////////////////////////////////////put utilisateur n°3 ////////////////////////////////////////
    public function testPutMaster3Admin(){
        $data = [
            "firstname"=>"lol",
            "email"=>"sdferty@yahoo.com"
        ];

        $client = static::createClient();
        $client->request('PUT', '/api/masters/4', [], [], ['CONTENT_TYPE' => 'application/json','HTTP_AUTH-TOKEN' => 'keyAdmin'], json_encode($data));
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(200, $response->getStatusCode());

    }
    public function testPutMaster3AdminError(){
        $data = [
            "email"=>"sdferty"
        ];

        $client = static::createClient();
        $client->request('PUT', '/api/masters/4', [], [], ['CONTENT_TYPE' => 'application/json','HTTP_AUTH-TOKEN' => 'keyAdmin'], json_encode($data));
        $response = $client->getResponse();
        $this->assertEquals(401, $response->getStatusCode());
       // dump($arrayContent = json_decode($content, true));
    }
    public function testPutMaster3456AdminError(){
        $data = [
            "email"=>"sdferty"
        ];

        $client = static::createClient();
        $client->request('PUT', '/api/masters/3456', [], [], ['CONTENT_TYPE' => 'application/json','HTTP_AUTH-TOKEN' => 'keyAdmin'], json_encode($data));
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(404, $response->getStatusCode());
        $arrayContent = json_decode($content, true);
        $this->assertSame("User does note existe",$arrayContent);
    }
   public function testPutMasterMaster(){
        $data = [
            "email"=>"sdfertfgdfy@yahoo.com"
        ];

        $client = static::createClient();
        $client->request('PUT', '/api/masters/2', [], [], ['CONTENT_TYPE' => 'application/json','HTTP_AUTH-TOKEN' => 'keyUser'], json_encode($data));
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }
}