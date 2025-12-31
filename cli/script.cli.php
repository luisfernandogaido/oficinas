<?php

use modelo\Os;

include 'def.cli.php';
$codigos = Os::all(24);
d($codigos);