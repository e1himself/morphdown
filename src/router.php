<?php

use League\Flysystem;
use Phine\Path\Path;

require __DIR__.'/../vendor/autoload.php';

$filename = realpath(getenv('MORPHDOWN_FILE'));
$morphdown_static_path = Path::canonical(__DIR__.'/../web');
$project_static_path = dirname($filename);

$app = new Morphdown\Application();
$app['debug'] = true;
$app['theme'] = 'neat';
// Templates dir
$app['templates_path'] = __DIR__.'/../tpl';

$app['morphdown_static_path'] = $morphdown_static_path;
$app['morphdown_static'] = function($context) {
  return new Flysystem\Filesystem(new Flysystem\Adapter\Local($context['morphdown_static_path']));
};

$app['project_static_path'] = $project_static_path;
$app['project_static'] = function($context) {
  return new Flysystem\Filesystem(new Flysystem\Adapter\Local($context['project_static_path']));
};
$app['project_file'] = function($context) use ($filename) {
  /** @var Flysystem\FilesystemInterface $files */
  $files = $context['project_static'];
  /** @var Flysystem\File $file */
  $file = $files->get(basename($filename));
  return $file;
};

$app['markdown'] = function($context) {
  $converter = new \League\CommonMark\CommonMarkConverter();
  return $converter;
};

$app['markdownify'] = function($context) {
  /** @var \League\CommonMark\CommonMarkConverter $converter */
  $converter = $context['markdown'];
  return function($md) use ($converter) {
    return $converter->convertToHtml($md);
  };
};

$app->post('/content', new \Morphdown\Controller\ContentController());
$app->get('/{path}', new \Morphdown\Controller\StaticController())->assert('path', '.+');
$app->get('/', new \Morphdown\Controller\IndexController());

// Silex does not behave great when using in built-in server router.
// Fix server variables to be like it would be in normal application.
$_SERVER['PATH_INFO'] = $_SERVER['PHP_SELF'];
$_SERVER['PHP_SELF'] = '/app.php/'.ltrim($_SERVER['PHP_SELF'], '/');
$_SERVER['SCRIPT_NAME'] = '/app.php';
$_SERVER['SCRIPT_FILENAME'] = Path::canonical(__DIR__.'/../web/app.php');

return $app->run();