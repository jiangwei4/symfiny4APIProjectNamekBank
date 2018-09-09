<?php
namespace App\Tests;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
class CompanyControllerTest extends WebTestCase {
    ///////////////////////////////////////////////toutes les company ////////////////////////////////////////
    public function testGetCompanyAdmin()
    {
        $client = static::createClient();
        $client->request('GET', '/api/companys', [], [], ['HTTP_CONTENT_TYPE' => 'application/json','HTTP_AUTH-TOKEN' => 'keyAdmin']);
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($content);
        $arrayContent = json_decode($content, true);
        $this->assertCount(10, $arrayContent);
    }

    public function testGetCompanyMaster()
    {
        $client = static::createClient();
        $client->request('GET', '/api/companys', [], [], ['HTTP_CONTENT_TYPE' => 'application/json','HTTP_AUTH-TOKEN' => 'keyUser']);
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertJson($content);
        $arrayContent = json_decode($content, true);
        $this->assertSame("Not Logged for this user or not an Admin",$arrayContent);
    }

    public function testGetCompanyNotLogged()
    {
        $client = static::createClient();
        $client->request('GET', '/api/companys');
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertJson($content);
        $arrayContent = json_decode($content, true);
        $this->assertSame("Not Logged",$arrayContent);
    }
    /////////////////////////////////////////////// company n°3 ////////////////////////////////////////
    public function testGetCompany3Admin()
    {
        $client = static::createClient();
        $client->request('GET', '/api/companies/3', [], [], ['HTTP_CONTENT_TYPE' => 'application/json','HTTP_AUTH-TOKEN' => 'keyAdmin']);
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($content);
        $arrayContent = json_decode($content, true);
        $this->assertCount(9, $arrayContent);
    }

    public function testGetCompany3User()
    {
        $client = static::createClient();
        $client->request('GET', '/api/companies/3', [], [], ['HTTP_CONTENT_TYPE' => 'application/json','HTTP_AUTH-TOKEN' => 'keyUser']);
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($content);
        $arrayContent = json_decode($content, true);
        $this->assertCount(9, $arrayContent);
    }

    public function testGetCompany3NotLogged()
    {
        $client = static::createClient();
        $client->request('GET', '/api/companies/3');
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($content);
        $arrayContent = json_decode($content, true);
        $this->assertCount(9, $arrayContent);
    }
    public function testGetCompany3456NotLogged()
    {
        $client = static::createClient();
        $client->request('GET', '/api/companies/3456');
        $response = $client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
    }
    ///////////////////////////////////////////////post ////////////////////////////////////////
       public function testPostCompany(){
           $data = [
               "name"  => "ffff",
               "slogan" => "Ebert",
               "phoneNumber" => "yeeap",
               "adress" => "dans ton"
           ];

           $client = static::createClient();
           $client->request('POST', '/api/Company', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));
           $response = $client->getResponse();
           $content = $response->getContent();
           $this->assertEquals(200, $response->getStatusCode());
           $this->assertJson($content);
           $arrayContent = json_decode($content, true);
           $this->assertCount(9, $arrayContent);
       }
    public function testPostCompanyBlank(){
        $data = [

        ];

        $client = static::createClient();
        $client->request('POST', '/api/Company', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(400, $response->getStatusCode());
       //  dump($arrayContent = json_decode($content, true));
    }

    public function testPostCompanyAddressExistant(){
        $data = [
            "name" => "ffff",
            "slogan" => "Ebert",
            "phoneNumber" => "yeeap",
            "adress" => "dans ton"
        ];

        $client = static::createClient();
        $client->request('POST', '/api/Company', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(400, $response->getStatusCode());
    }
    ///////////////////////////////////////////////delete company n°6 ////////////////////////////////////////
    public function testDeleteNotlogged(){
        $client = static::createClient();
        $client->request('DELETE', '/api/companies/6', [], [], ['CONTENT_TYPE' => 'application/json']);
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertJson($content);
        $arrayContent = json_decode($content, true);
        $this->assertSame("Not Logged",$arrayContent);
    }
    public function testDeleteNotGoodCompany(){
        $client = static::createClient();
        $client->request('DELETE', '/api/companies/6', [], [], ['CONTENT_TYPE' => 'application/json','HTTP_AUTH-TOKEN' => 'keyUser']);
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertJson($content);
        $arrayContent = json_decode($content, true);
        $this->assertSame("Not the same user or tu n as pas les droits",$arrayContent);
    }
    public function testDeleteGoodCompanyAdmin(){
        $client = static::createClient();
        $client->request('DELETE', '/api/companies/6', [], [], ['CONTENT_TYPE' => 'application/json','HTTP_AUTH-TOKEN' => 'keyAdmin']);
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(204, $response->getStatusCode());
    }
    public function testDeleteNotGoodCompanyAdmin(){
        $client = static::createClient();
        $client->request('DELETE', '/api/companies/3456', [], [], ['CONTENT_TYPE' => 'application/json','HTTP_AUTH-TOKEN' => 'keyAdmin']);
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(404, $response->getStatusCode());
        $arrayContent = json_decode($content, true);
        $this->assertSame("Compagny does note existe",$arrayContent);
    }
    ///////////////////////////////////////////////put company n°4 ////////////////////////////////////////
       public function testPutCompany4Admin(){
           $data = [
               "name"=>"lol"
           ];

           $client = static::createClient();
           $client->request('PUT', '/api/companies/4', [], [], ['CONTENT_TYPE' => 'application/json','HTTP_AUTH-TOKEN' => 'keyAdmin'], json_encode($data));
           $response = $client->getResponse();
           $content = $response->getContent();
         //  dump($arrayContent = json_decode($content, true));
           $this->assertEquals(200, $response->getStatusCode());

       }
    public function testPutCompany4AdminError(){
        $data = [
            "adress" => "dans ton"
        ];

        $client = static::createClient();
        $client->request('PUT', '/api/companies/4', [], [], ['CONTENT_TYPE' => 'application/json','HTTP_AUTH-TOKEN' => 'keyAdmin'], json_encode($data));
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(401, $response->getStatusCode());
        // dump($arrayContent = json_decode($content, true));
    }
    public function testPutCompany3456AdminError(){
        $data = [
            "name"=>"sdferty"
        ];

        $client = static::createClient();
        $client->request('PUT', '/api/companies/3456', [], [], ['CONTENT_TYPE' => 'application/json','HTTP_AUTH-TOKEN' => 'keyAdmin'], json_encode($data));
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(404, $response->getStatusCode());
        $arrayContent = json_decode($content, true);
        $this->assertSame("Compagny does note existe",$arrayContent);
    }
       public function testPutCompanyMaster(){
           $data = [
               "name"=>"sdfertfgdfy@yahoo.com"
           ];

           $client = static::createClient();
           $client->request('PUT', '/api/companies/2', [], [], ['CONTENT_TYPE' => 'application/json','HTTP_AUTH-TOKEN' => 'keyUser'], json_encode($data));
           $response = $client->getResponse();
           $this->assertEquals(200, $response->getStatusCode());
         //  dump($arrayContent = json_decode($content, true));
       }

}