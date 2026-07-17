<?php
/**
 * PHPUnit bootstrap for the DPR test suite.
 *
 * CI3 library/model files open with `defined('BASEPATH') OR exit(...)`.
 * For pure-PHP unit tests we define a stub BASEPATH so those files can be
 * classmap-autoloaded by Composer without bootstrapping the full framework.
 */
if (! defined('BASEPATH')) {
    define('BASEPATH', __DIR__);
}

require __DIR__ . '/../vendor/autoload.php';
