<?php
define('ROOT', $_SERVER['DOCUMENT_ROOT']);

use module\VacationController;

require_once __DIR__ . '/vendor/autoload.php';

$content = (new VacationController())->run();
?>

<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vacation Module</title>

    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>

<div class="wrapper">
    <?= $content; ?>
</div>

</body>
</html>