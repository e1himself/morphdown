<?php
/**
 * @var string $file
 * @var string $theme
 *
 * @var string $input
 * @var string $output
 */
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Morphdown [<?= $file ?>]</title>
    <link rel="stylesheet" href="/codemirror/codemirror.css">
    <link rel="stylesheet" href="/codemirror/theme/<?= $theme ?>.css">
    <link rel="stylesheet" href="/style.css">
    <script src="/bundle.js"></script>
  </head>
  <body data-theme="<?= $theme ?>">
    <div id="input"><textarea><?= htmlentities($input) ?></textarea></div>
    <div id="output"><div><?= $output ?></div></div>
  </body>
</html>
