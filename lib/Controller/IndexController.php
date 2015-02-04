<?php
/**
 * Date: 03.02.15
 * Time: 18:00
 * Author: Ivan Voskoboynyk
 * Email: ioann.voskoboynyk@gmail.com
 */

namespace Morphdown\Controller;

use League\Flysystem\File;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class IndexController
{
  public function __invoke(Application $app, Request $request)
  {
    $_tplFile = $app['templates_path'].'/index.html.php';
    /** @var callable $markdownify */
    $markdownify = $app['markdownify'];

    /** @var File $project_file */
    $project_file = $app['project_file'];

    $file = basename($project_file->getPath());
    $theme = $app['theme'];
    $input = $project_file->read();
    $output = $markdownify($input);

    $render = function() use ($_tplFile, $file, $theme, $input, $output) {
      include $_tplFile;
    };

    return $app->stream($render);
  }
}