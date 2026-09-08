<?php
require_once __DIR__ . '/../bootstrap.php';

use Controller\AuthController;

(new AuthController())->logout();
header('Location: ../index.php');
exit;
