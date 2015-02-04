<?php

namespace Morphdown\Controller;

use Morphdown\FallbackPhpServerResponse;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * Date: 03.02.15
 * Time: 17:56
 * Author: Ivan Voskoboynyk
 * Email: ioann.voskoboynyk@gmail.com
 */

class StaticController
{
  function __invoke(Application $app, Request $request)
  {
    $uri = $request->getRequestUri();
    $morphdown_static = $app['morphdown_static'];
    if ($morphdown_static->has($uri))
    {
      return new FallbackPhpServerResponse();
    }

    $project_static = $app['project_static'];
    if ($project_static->has($uri))
    {
      $guesser = new \Morphdown\ExtensionMimeTypeGuesser();
      $ext = pathinfo($uri, PATHINFO_EXTENSION);
      $mime = $guesser->guessMimeType($ext) ?: $project_static->getMimetype($uri);

      $streaming = function () use ($project_static, $uri)
      {
        $stream = $project_static->readStream($uri);
        fpassthru($stream);
        fclose($stream);
      };
      return $app->stream($streaming, 200, ['Content-Type' => $mime]);
    }

    return null;
  }
}