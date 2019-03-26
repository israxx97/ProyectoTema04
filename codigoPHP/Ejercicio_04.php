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
                $a_errores['descDepartamento'] = validacionFormularios::comprobarAlfaNumerico($_POST['descDepartamento'], 100, 1, 1);

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
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                    <span for="descDepartamento">Descripción departamento:&nbsp;</span>
                    <textarea rows="5" cols="20" name="descDepartamento" placeholder="Mi departamento AAA"><?php
                        if (isset($_REQUEST['descDepartamento']) && is_null($a_errores['descDepartamento'])) {
                            echo $_REQUEST['descDepartamento'];
                        }
                        ?></textarea>
                    <font color="red">&nbsp;*</font>
                    <font color="red"><?php echo $a_errores['descDepartamento']; ?></font>
                    <br><br>
                    <input type="submit" name="enviar" value="Enviar">
                </form>
                <?php
                break;
        }
        ?>
    </body>
</html>