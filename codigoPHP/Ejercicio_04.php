<!DOCTYPE html>
<html>
    <head>
        <title>
            Ejercicio 03 - Israel García Cabañeros
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

                try {
                    $miDB = new PDO(HOST_DB, USER_DB, PASS_DB);
                    $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    ?>
                    <p>Conexión correcta.</p>
                    <?php
                    $statement = $miDB->prepare('SELECT * FROM Departamento WHERE DescDepartamento LIKE "%' . $a_respuesta['descDepartamento'] . '%"');
                    $statement->execute();

                    if ($a_respuesta['descDepartamento'] == '') {
                        ?><p>Tu búsqueda tiene <?php echo $statement->rowCount(); ?> resultados.</p><?php
                        while ($resultado = $statement->fetchObject()) {
                            ?><p>El departamento con descripción <?php echo $resultado->DescDepartamento; ?> tiene el código <?php echo $resultado->CodDepartamento; ?>.</p><?php
                        }
                    }

                    if ($statement->rowCount() == 0) {
                        ?><p>NO existen coincidencias con la búsqueda <?php echo $a_respuesta['descDepartamento']; ?>.</p><?php
                    } else {
                        ?><p>Tu búsqueda tiene <?php echo $statement->rowCount(); ?> resultados.</p><?php
                        while ($resultado = $statement->fetchObject()) {
                            ?><p>El departamento con descripción <?php echo $resultado->DescDepartamento; ?> tiene el código <?php echo $resultado->CodDepartamento; ?>.</p><?php
                        }
                    }
                } catch (PDOException $pdoe) {
                    ?>
                    <p><?php echo $pdoe->getMessage(); ?></p>
                    <?php
                } finally {
                    unset($miDB);
                }

                break;

            default:
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
                </form>
                <?php
                break;
        }
        ?>
    </body>
</html>