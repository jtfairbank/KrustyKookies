<?php

/* Rest Request
 * ================================================================================
 * Represents an API REST request.
 *
 * Each api request is made via a POST operation, and all data needed to process
 * it is sent as a JSON object in the request's body:
 *
 * ```
 *   {
 *       'object': Controller's Name
 *     , 'action': Operation to Perform
 *     , 'data':   Data Needed to Take Action
 *   }
 * ```
 *
 * A `RestRequest` represents static information (the existing request) and thus
 * should not change.  Private member variables, accessed through getters,
 * enforce this.
 *
 * Code loosely based on [this tutorial](http://www.gen-x-design.com/archives/create-a-rest-api-with-php/).
 */
class RestRequest {

  /* **type:** [string] The requested object's unique type. */
  private $type;

  /* **action:** [string] The action to perform on the requested object.
   * This should be a CRUD operation or a custom action defined by the object.
   */
  private $action;

  /* **Data:** [object] Data needed to fufill the request.
   * This is often the object itself, for CRUD operations.
   * This may not be defined if the action doesn't need additional data to
   * fulfill the request.
   */
  private $data;

  /* Constructors
   * ------------------------------------------------------------ */
  public function __construct($type, $action, $data) {
    $this->type = $type;
    $this->action = $action;
    $this->data = $data;
  }

  public static function GetCurrentRequest() {
    $body = static::getRequestBody();
		$type = $body['object'];
		$action = $body['action'];
		$data = isset($body['data']) ? $body['data'] : null;

		return new RestRequest($type, $action, $data);
  }

  /* **Get Request Body**
   *
   * A helper function to return the request's body.
   */
  private static function getRequestBody() {
    return (array) (json_decode( file_get_contents('php://input') ));
  }

  /* Getters & Setters
   * ------------------------------------------------------------ */
  public function getType() {
    return $this->type;
  }

  public function getAction() {
    return $this->action;
  }

  public function getData() {
    return $this->data;
  }

  /* Handle the Request
   * ------------------------------------------------------------ */
  public function fufill() {
    $controller = chooseController($this->type);
    return $controller::takeAction($this);
  }

} // end class RestRequest


/* Rest Response
 * ================================================================================
 * An object to setup and send an http response to a `RestRequest`.
 *
 * Code loosely based on [this tutorial](http://www.gen-x-design.com/archives/create-a-rest-api-with-php/).
 */
class RestResponse {

  public $status;
  public $content;
  public $contentType;

  /* Constructors
   * ------------------------------------------------------------ */
  public function __construct($status, $content, $contentType = 'text/plain') {
    $this->status      = $status;
    $this->content     = $content;
    $this->contentType = $contentType;
  }

  /* Send the Response
   * ------------------------------------------------------------ */
  public function send() {
    $status_header = 'HTTP/1.1 ' . $this->status . ' ' . RestUtils::getStatusCodeMessage($this->status);
		header($status_header);
		header('Content-type: ' . $this->contentType);

    echo $this->encode();
  }

  /* **Encode**
   *
   * A helper function to properly encode the `content` based on its
   * `contentType`.
   */
  private function encode() {
    switch($this->contentType) {
      case "text/csv":
      case "text/html":
      case "text/plain":
        return $this->content;

      case "text/json":
        return json_encode($this->content);

      default:
        throw new Exception("Invalid Parameter: RestResponse->encode doesn't know how to handle content type {$this->contentType}.");
        break;
    }
  }

} // end class RestResponse


/* Rest Utils
 * ================================================================================
 * Helper functions for dealing with REST Requests / Responses.
 *
 * Code loosely based on [this tutorial](http://www.gen-x-design.com/archives/create-a-rest-api-with-php/).
 */
class RestUtils {

  public static function getStatusCodeMessage($status) {
    $codes = array(
      100 => 'Continue',
      101 => 'Switching Protocols',
      200 => 'OK',
      201 => 'Created',
      202 => 'Accepted',
      203 => 'Non-Authoritative Information',
      204 => 'No Content',
      205 => 'Reset Content',
      206 => 'Partial Content',
      300 => 'Multiple Choices',
      301 => 'Moved Permanently',
      302 => 'Found',
      303 => 'See Other',
      304 => 'Not Modified',
      305 => 'Use Proxy',
      306 => '(Unused)',
      307 => 'Temporary Redirect',
      400 => 'Bad Request',
      401 => 'Unauthorized',
      402 => 'Payment Required',
      403 => 'Forbidden',
      404 => 'Not Found',
      405 => 'Method Not Allowed',
      406 => 'Not Acceptable',
      407 => 'Proxy Authentication Required',
      408 => 'Request Timeout',
      409 => 'Conflict',
      410 => 'Gone',
      411 => 'Length Required',
      412 => 'Precondition Failed',
      413 => 'Request Entity Too Large',
      414 => 'Request-URI Too Long',
      415 => 'Unsupported Media Type',
      416 => 'Requested Range Not Satisfiable',
      417 => 'Expectation Failed',
      500 => 'Internal Server Error',
      501 => 'Not Implemented',
      502 => 'Bad Gateway',
      503 => 'Service Unavailable',
      504 => 'Gateway Timeout',
      505 => 'HTTP Version Not Supported'
    );

    if (isset($codes[$status])) {
      return $codes[$status];
    } else {
      throw new Exception("Invalid parameter exception: RestUtils::getStatusCodeMessage doesn't know how to handle status $status.");
    }
  }

} // end class RestUtils

?>