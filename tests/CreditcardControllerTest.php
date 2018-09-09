<?php
namespace App\Tests;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
class CreditcardControllerTest extends WebTestCase
{

    /////////////////////////////////////////////// toutes les cc /////////////////////////////////////////////////////
    public function testGetCreditcardofallcompanyAdmin()
    {
        $client = static::createClient();
        $client->request('GET', '/api/creditcardsofallcompany', [], [], ['HTTP_CONTENT_TYPE' => 'application/json','HTTP_AUTH-TOKEN' => 'keyAdmin']);
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($content);
        $arrayContent = json_decode($content, true);
        $this->assertCount(9, $arrayContent);
    }

    public function testGetCreditcardofallcompanyMaster()
    {
        $client = static::createClient();
        $client->request('GET', '/api/creditcardsofallcompany', [], [], ['HTTP_CONTENT_TYPE' => 'application/json','HTTP_AUTH-TOKEN' => 'keyUser']);
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertJson($content);
        $arrayContent = json_decode($content, true);
        $this->assertSame("Not Logged for this user or not an Admin",$arrayContent);
    }

    public function testGetCreditcardofallcompanyNotLogged()
    {
        $client = static::createClient();
        $client->request('GET', '/api/creditcardsofallcompany');
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertJson($content);
        $arrayContent = json_decode($content, true);
        $this->assertSame("Not Logged",$arrayContent);
    }

    ///////////////////////////////////////////////toutes les cc d'une company ////////////////////////////////////////
    public function testGetCreditcardAdmin()
    {
        $client = static::createClient();
        $client->request('GET', '/api/creditcards', [], [], ['HTTP_CONTENT_TYPE' => 'application/json','HTTP_AUTH-TOKEN' => 'keyAdmin']);
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($content);
        $arrayContent = json_decode($content, true);
        $this->assertCount(1, $arrayContent);
    }

    public function testGetCreditcardMaster()
    {
        $client = static::createClient();
        $client->request('GET', '/api/creditcards', [], [], ['HTTP_CONTENT_TYPE' => 'application/json','HTTP_AUTH-TOKEN' => 'keyUser']);
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($content);
        $arrayContent = json_decode($content, true);
        $this->assertCount(1, $arrayContent);
    }

    public function testGetCreditcardNotLogged()
    {
        $client = static::createClient();
        $client->request('GET', '/api/creditcards');
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertJson($content);
        $arrayContent = json_decode($content, true);
        $this->assertSame("Not Logged",$arrayContent);
    }
/////////////////////////////////////////////// company n°9 ////////////////////////////////////////
    public function testGetCompany9Admin()
    {
        $client = static::createClient();
        $client->request('GET', '/api/creditcards/9', [], [], ['HTTP_CONTENT_TYPE' => 'application/json','HTTP_AUTH-TOKEN' => 'keyAdmin']);
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($content);
        $arrayContent = json_decode($content, true);
        $this->assertCount(5, $arrayContent);
    }

    public function testGetCompany9User()
    {
        $client = static::createClient();
        $client->request('GET', '/api/creditcards/9', [], [], ['HTTP_CONTENT_TYPE' => 'application/json','HTTP_AUTH-TOKEN' => 'keyUser']);
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($content);
        $arrayContent = json_decode($content, true);
        $this->assertCount(5, $arrayContent);
    }

    public function testGetCompany9NotLogged()
    {
        $client = static::createClient();
        $client->request('GET', '/api/creditcards/9');
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($content);
        $arrayContent = json_decode($content, true);
        $this->assertCount(5, $arrayContent);
    }
    public function testGetCompany3456NotLogged()
    {
        $client = static::createClient();
        $client->request('GET', '/api/creditcards/3456');
        $response = $client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
    }
    ///////////////////////////////////////////////post ////////////////////////////////////////
    public function testPostCreditCardNotLogged(){
        $data = [
            "name"  => "ffff",
            "creditcardType" => "Ebert",
            "creditcardNumber" => "123"
        ];

        $client = static::createClient();
        $client->request('POST', '/api/Creditcard', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertJson($content);
        $arrayContent = json_decode($content, true);
        $this->assertSame("Not Logged",$arrayContent);
    }
    public function testPostCreditCardBlank(){
        $data = [

        ];

        $client = static::createClient();
        $client->request('POST', '/api/Creditcard', [], [], ['HTTP_CONTENT_TYPE' => 'application/json','HTTP_AUTH-TOKEN' => 'keyAdmin'], json_encode($data));
        $response = $client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
        //  dump($arrayContent = json_decode($content, true));
    }

    public function testPostCreditCardNumberExistant(){
        $data = [
            "name"  => "ffff",
            "creditcardType" => "Ebert",
            "creditcardNumber" => "4556670528041909"
        ];

        $client = static::createClient();
        $client->request('POST', '/api/Creditcard', [], [], ['HTTP_CONTENT_TYPE' => 'application/json','HTTP_AUTH-TOKEN' => 'keyAdmin'], json_encode($data));
        $response = $client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
    }
    ///////////////////////////////////////////////delete Creditcard n°9 ////////////////////////////////////////
    public function testDeleteNotlogged(){
        $client = static::createClient();
        $client->request('DELETE', '/api/creditcards/9', [], [], ['CONTENT_TYPE' => 'application/json']);
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertJson($content);
        $arrayContent = json_decode($content, true);
        $this->assertSame("Not Logged",$arrayContent);
    }
    public function testDeleteNotGoodCreditcard(){
        $client = static::createClient();
        $client->request('DELETE', '/api/creditcards/9', [], [], ['CONTENT_TYPE' => 'application/json','HTTP_AUTH-TOKEN' => 'keyUser']);
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertJson($content);
        $arrayContent = json_decode($content, true);
        $this->assertSame("Not the same user or tu n as pas les droits",$arrayContent);
    }

    public function testDeleteNotGoodCompanyAdmin(){
        $client = static::createClient();
        $client->request('DELETE', '/api/creditcards/3456', [], [], ['CONTENT_TYPE' => 'application/json','HTTP_AUTH-TOKEN' => 'keyAdmin']);
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(404, $response->getStatusCode());
        $arrayContent = json_decode($content, true);
        $this->assertSame("Creditcard does note existe",$arrayContent);
    }

    ///////////////////////////////////////////////put creditcard n°4 ////////////////////////////////////////
    public function testPutCreditcard4Admin(){
        $data = [
            "name"=>"lol",
            "creditcardNumber" => "4556670528041909"
        ];

        $client = static::createClient();
        $client->request('PUT', '/api/creditcards/4', [], [], ['CONTENT_TYPE' => 'application/json','HTTP_AUTH-TOKEN' => 'keyAdmin'], json_encode($data));
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

    }
    public function testPutCreditcard8AdminErrorNumberExistant(){
        $data = [
            "creditcardNumber" => "4556670528041909"
        ];

        $client = static::createClient();
        $client->request('PUT', '/api/creditcards/8', [], [], ['CONTENT_TYPE' => 'application/json','HTTP_AUTH-TOKEN' => 'keyAdmin'], json_encode($data));
        $response = $client->getResponse();
        $this->assertEquals(401, $response->getStatusCode());
        // dump($arrayContent = json_decode($content, true));
    }
    public function testPutCreditcard3456AdminError(){
        $data = [
            "name"=>"sdferty"
        ];

        $client = static::createClient();
        $client->request('PUT', '/api/creditcards/3456', [], [], ['CONTENT_TYPE' => 'application/json','HTTP_AUTH-TOKEN' => 'keyAdmin'], json_encode($data));
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(404, $response->getStatusCode());
        $arrayContent = json_decode($content, true);
        $this->assertSame("Creditcard does note existe",$arrayContent);
    }
    public function testPutCreditcardMaster(){
        $data = [
            "name"=>"sdfertfgdf"
        ];

        $client = static::createClient();
        $client->request('PUT', '/api/creditcards/2', [], [], ['CONTENT_TYPE' => 'application/json','HTTP_AUTH-TOKEN' => 'keyUser'], json_encode($data));
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        //  dump($arrayContent = json_decode($content, true));
    }

}