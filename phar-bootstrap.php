<?php
Phar::mapPhar();

// Use Composer autoloader
// $basePath = 'phar://' . __FILE__ . '/';
// require $basePath . 'vendor/autoload.php';


// Use custom autoloader
spl_autoload_register(function ($class) {

  // project-specific namespace prefix
  $prefix = 'jelgblad\\VCard\\';

  // base directory for the namespace prefix
  $base_dir = 'phar://' . __FILE__ . '/';

  // does the class use the namespace prefix?
  $len = strlen($prefix);
  if (strncmp($prefix, $class, $len) !== 0) {
    // no, move to the next registered autoloader
    return;
  }

  // get the relative class name
  $relative_class = substr($class, $len);

  // replace the namespace prefix with the base directory, replace namespace
  // separators with directory separators in the relative class name, append
  // with .php
  $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

  // if the file exists, require it
  if (file_exists($file)) {
    require $file;
  }
});

__HALT_COMPILER();