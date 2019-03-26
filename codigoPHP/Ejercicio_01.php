<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../config/config.php';
?>
<h1>Conexión correcta.</h1>
<?php
try {
    $miDB = new PDO(HOST_DB, USER_DB, PASS_DB);
    $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    ?>
    <p>Conexión correcta.</p>
    <?php
} catch (PDOException $pdoe) {
    ?><p><?php echo $pdoe->getMessage(); ?></p><?php
} finally {
    unset($miDB);
}
?>
<h1>Conexión incorrecta.</h1>
<?php
try {
    $miDB = new PDO(HOST_DB, USER_DB, 'paco');
    $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    ?>
    <p>Conexión correcta.</p>
    <?php
} catch (PDOException $pdoe) {
    ?><p><?php echo $pdoe->getMessage(); ?></p><?php
} finally {
    unset($miDB);
}