<!DOCTYPE html>
<html>
    <head>
        <title>
            Ejercicio 04 - Israel García Cabañeros
        </title>
    </head>
    <body>
        <?php
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        require_once '../core/validacionFormularios.php';
        require_once '../config/config.php';

        $entradaOK = true;

        $a_errores = [
            'descDepartamento' => null
        ];

        $a_respuesta = [
            'descDepartamento' => null
        ];
        ?>
        <h1>Búsqueda de departamento por descripción</h1>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <label for="descDepartamento">Descripción departamento:</label>
            <input size="50" type="text" name="descDepartamento" value="<?php
            if (isset($_REQUEST['descDepartamento']) && is_null($a_errores['descDepartamento'])) {
                echo $_REQUEST['descDepartamento'];
            }
            ?>" placeholder="Mi departamento AAA"/>
            <font color="red"><?php echo $a_errores['descDepartamento']; ?></font>
            <br><br>
            <input type="submit" name="enviar" value="Buscar">
            <input type="button" name="cancelar" value="Cancelar" onclick="location = '../index.php'">
        </form>
        <?php
        try {
            $miDB = new PDO(HOST_DB, USER_DB, PASS_DB);
            $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            switch (true) {
                case (isset($_POST['enviar'])):
                    $a_errores['descDepartamento'] = validacionFormularios::comprobarAlfaNumerico($_POST['descDepartamento'], 100, 1, 0);

                    foreach ($a_errores as $campo => $error) {
                        if ($error != null) {
                            $entradaOK = false;
                            $_POST[$campo] = null;
                        }
                    }

                    break;

                default:
                    $entradaOK = false;

                    break;
            }

            switch (true) {
                case $entradaOK:
                    $a_respuesta['descDepartamento'] = $_POST['descDepartamento'];
                    $statement = $miDB->prepare('SELECT * FROM Departamento WHERE DescDepartamento LIKE "%' . $a_respuesta['descDepartamento'] . '%"');
                    $statement->bindParam(':descDepartamento', $a_respuesta['descDepartamento']);
                    $statement->execute();

                    if ($a_respuesta['descDepartamento'] == '') {
                        ?><p>Tu búsqueda tiene <?php echo $statement->rowCount(); ?> resultados.</p>
                        <table border="1">
                            <tr>
                                <th>CodDepartamento</th>
                                <th>DescDepartamento</th>
                            </tr>
                            <?php
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
                        <?php
                    } else if ($statement->rowCount() > 0) {
                        ?><p>Tu búsqueda tiene <?php echo $statement->rowCount(); ?> resultados.</p>
                        <table border="1">
                            <tr>
                                <th>CodDepartamento</th>
                                <th>DescDepartamento</th>
                            </tr>
                            <?php
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
                        <?php
                    } else if ($statement->rowCount() == 0) {
                        ?><p>Tu búsqueda tiene <?php echo $statement->rowCount(); ?> resultados.</p><?php
                    }
                    break;
            }
        } catch (PDOException $pdoe) {
            ?>
            <p><?php echo $pdoe->getMessage(); ?></p>
            <?php
        } finally {
            unset($miDB);
        }
        ?>
    </body>
</html>