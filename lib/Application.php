<?php
/**
 * Date: 03.02.15
 * Time: 18:01
 * Author: Ivan Voskoboynyk
 * Email: ioann.voskoboynyk@gmail.com
 */

namespace Morphdown;

use Symfony\Component\HttpFoundation\Request;

class Application extends \Silex\Application
{
  public function run(Request $request = null)
  {
    $request = Request::createFromGlobals();
    $response = $this->handle($request);
    if ($response instanceof FallbackPhpServerResponse)
    {
      return false;
    }
    $response->send();
    $this->terminate($request, $response);
  }
}