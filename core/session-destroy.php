<?php
session_id($_GET['id']);
session_start();
session_destroy();