<?php
require_once '../config/config.php';

try {
    $miDB = new PDO(HOST_DB, USER_DB, PASS_DB);
    $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    ?>
    <p>Conexión correcta.</p>
    <?php
    $sql = 'SELECT * FROM Departamento';
    $statement = $miDB->prepare($sql);
    $statement->execute();
    ?><table border="1"><?php
        while ($resultado = $statement->fetchObject()) {
            ?>
            <tr>
                <td><?php echo $resultado->CodDepartamento; ?></td>
                <td><?php echo $resultado->DescDepartamento; ?></td>
            </tr>
            <?php
        }
        ?>
    </table>
    <p>Se han encontrado <?php echo $statement->rowCount(); ?> registros.</p>
    <?php
} catch (PDOException $pdoe) {
    ?><p><?php $pdoe->getMessage(); ?></p><?php
} finally {
    unset($miDB);
}

