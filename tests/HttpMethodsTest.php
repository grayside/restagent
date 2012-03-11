<?php

namespace restagent;

require_once (__DIR__ . '/TestCase.class.php');
require_once (realpath(__DIR__ . '/../restagent.lib.php'));

/**
 * Functional tests for basic endpoint processing
 */
class HttpMethodsTest extends TestCase {

  private $request;

  public function setUp() {
    parent::setUp();
    $this->request = new \restagent\Request($this->server_url);
  }

  public function test_get() {

    $response = $this->request->set('Content-Type', 'application/json')
      ->add(array("firstName" => "irakli", "lastName" => "Nadareishvili"))
      ->add("hobby", "programming")
      ->set("X-API-Key", "aabbccdd")
      ->set(array("foo" => "bar", "one" => "two"))
      ->get("/somepath");

    eval('$response = ' . "$response;");

    $get = $response['get'];
    $get_vars_correct = ($get['firstName'] == 'irakli' &&
                     $get['lastName'] == 'Nadareishvili' &&
                     $get['hobby'] == 'programming');

    $this->assertEquals($response['server']['REQUEST_METHOD'], "GET",
      "Test of correct method transmitted for HTTP GET");

    $this->assertEquals($get_vars_correct, true,
                        "Test of add() functioning properly for HTTP GET");

    $this->assertEquals($response['server']['CONTENT_TYPE'], "application/json",
      "Test1 (content-type) of set() functioning properly for HTTP GET");

    $this->assertEquals($response['server']['HTTP_FOO'], "bar",
      "Test2 (custom headers, passed as array) of set() functioning properly for HTTP GET");

  }

  /**
   * @TODO: implement firing exception if Content-Type is set during POST request and implement
   *       a test that verifies the firing of such exception
   */
  public function test_post() {

    $response = $this->request->set('Content-Type', 'application/json') // This is actually invalid, but we silently fix it
      ->add(array("firstName" => "irakli", "lastName" => "Nadareishvili"))
      ->add("hobby", "programming")
      ->set("X-API-Key", "aabbccdd")
      ->set(array("foo" => "bar", "one" => "two"))
      ->post("/somepath");

    eval('$response = ' . "$response;");

    $this->assertEquals($response['server']['REQUEST_METHOD'],"POST",
      "Test of correct method transmitted for HTTP POST");

    $post = $response['post'];
    $post_vars_correct = ($post['firstName'] == 'irakli' &&
      $post['lastName'] == 'Nadareishvili' &&
      $post['hobby'] == 'programming');

    $this->assertEquals($post_vars_correct, true,
      "Test of add() functioning properly for HTTP POST");

    $this->assertEquals($response['server']['CONTENT_TYPE'],"application/x-www-form-urlencoded",
      "Test1 (content-type) of set() functioning properly for HTTP POST");

    $this->assertEquals($response['server']['HTTP_FOO'],"bar",
      "Test2 (custom headers, passed as array) of set() functioning properly for HTTP POST");

  }

}