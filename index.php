<?php
require 'WordpressCleaner.php';

if ($_POST) {
    $db['host'] = $_POST['db_host'];
    $db['user'] = $_POST['db_user'];
    $db['password'] = $_POST['db_password'];
    $db['name'] = $_POST['db_name'];
    $tablePrefix = $_POST['db_prefix'];

    if (!$mysqli = new mysqli($db['host'], $db['user'], $db['password'], $db['name'])) {
        echo '<strong>Error:</strong> unable to connect your database.';
        exit;
    }

    $cleaner = new WordpressCleaner($mysqli, $tablePrefix);
    $cleaner->run();
}

?><!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>WordPress Cleaner</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>WordPress Cleaner</h1>

<form action="" method="post">
<?php if (!$_POST): ?>
<p class="warning">Before running the script, make sure you've got backup of your database!</p>

<table cellspacing="0">
<tr>
    <th colspan="2">Database connection settings</th>
</tr>
<tr>
    <td class="name">Database host (usually <em>localhost</em>)</td>
    <td><input type="text" name="db_host" value="localhost"></td>
</tr>
<tr>
    <td class="name">Database user name</td>
    <td><input type="text" name="db_user"></td>
</tr>
<tr>
    <td class="name">Database user password</td>
    <td><input type="password" name="db_password"></td>
</tr>
<tr>
    <td class="name">Database name</td>
    <td><input type="text" name="db_name"></td>
</tr>
<tr>
    <td class="name">Table prefix (usually <em>wp_</em>)</td>
    <td><input type="text" name="db_prefix" value="wp_"></td>
</tr>
</table>
<?php endif; ?>
<table cellspacing="0">
<tr>
    <th>Subject of removal</th>
    <th>Description</th>
    <?php if (!$_POST): ?>
    <th>Select</th>
    <?php else: ?>
    <th>Removed rows</th>
    <?php endif; ?>
</tr>

<?php foreach (WordpressCleaner::$tasks as $id => $task): ?>
    <tr>
        <td class="name"><?= $task['name'] ?></td>
        <td class="desc"><?= $task['desc'] ?></td>
        <?php if (!$_POST): ?>
        <td><input type="checkbox" name="tasks[<?= $id ?>]" <?php if ($task['default']) echo 'checked'; ?>></td>
        <?php else: ?>
        <td class="result"><?= (int) $task['result'] ?></td>
        <?php endif; ?>
    </tr>
<?php endforeach; ?>

<?php if (!$_POST): ?>
<tr class="submit"><td colspan="3"><button type="submit">Run</button></td></tr>
<?php endif; ?>
</table>
</form>

<footer>
    <p>Copyright by <a href="http://sobak.pl">Sobak</a></p>
</footer>

</body>
</html>