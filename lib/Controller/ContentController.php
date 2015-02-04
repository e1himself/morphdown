<?php
/**
 * Date: 03.02.15
 * Time: 21:04
 * Author: Ivan Voskoboynyk
 * Email: ioann.voskoboynyk@gmail.com
 */

namespace Morphdown\Controller;

use League\Flysystem\File;
use Morphdown\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ContentController
{
  public function __invoke(Application $app, Request $req)
  {
    /** @var callable $markdownify */
    $markdownify = $app['markdownify'];

    $cookies = $req->cookies;
    $body = json_decode($req->getContent());

    // out of time request, ignore it
    if ($cookies->has('morphdown_lt') && $body->ts < $cookies->get('morphdown_lt'))
    {
      return new Response('stale', 200, ['Content-Type' => 'text/plain']);
    }

    $output = $markdownify($body->content);
    $responseBody = ['content' => $output];

    /** @var File $file */
    $file = $app['project_file'];
    $file->update($body->content);

    $cookies->set('morphdown_lt', $body->ts);

    return $app->json($responseBody);
  }
}