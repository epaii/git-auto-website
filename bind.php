<?php

use epii\git\auto\website\website;

require __DIR__."/src/website.php";

website::bindToGit($argv[1],$argv[2]);