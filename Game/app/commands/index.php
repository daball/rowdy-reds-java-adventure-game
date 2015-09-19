<?php

$dir = opendir(__DIR__);

while (false !== ($file = readdir($dir))) {
  if ($file !== "index.php" && substr($file, 0, 1) !== '.') {
    require_once $file;
  }
}

closedir($dir);
