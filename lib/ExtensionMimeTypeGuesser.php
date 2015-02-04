<?php
/**
 * Date: 03.02.15
 * Time: 3:14
 * Author: Ivan Voskoboynyk
 * Email: ioann.voskoboynyk@gmail.com
 */

namespace Morphdown;


use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeExtensionGuesser;

class ExtensionMimeTypeGuesser extends MimeTypeExtensionGuesser
{
  /**
   * @param string $extension
   * @return string
   */
  public function guessMimeType($extension)
  {
    return array_search($extension, $this->defaultExtensions);
  }
}