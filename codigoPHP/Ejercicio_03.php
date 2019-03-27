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
            'codDepartamento' => null,
            'descDepartamento' => null,
        ];

        $a_respuesta = [
            'codDepartamento' => null,
            'descDepartamento' => null
        ];
        ?>
        <h1>Alta de departamento</h1>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <label for="codDepartamento">Código departamento:</label>
            <input size="3" type="text" name="codDepartamento" value="<?php
            if (isset($_REQUEST['codDepartamento']) && is_null($a_errores['codDepartamento'])) {
                echo $_REQUEST['codDepartamento'];
            }
            ?>" placeholder="AAA"/><font color="red">&nbsp;*</font>
            <font color="red"><?php echo $a_errores['codDepartamento']; ?></font>
            <br>
            <label for="descDepartamento">Descripción departamento:</label>
            <input size="50" type="text" name="descDepartamento" value="<?php
            if (isset($_REQUEST['descDepartamento']) && is_null($a_errores['descDepartamento'])) {
                echo $_REQUEST['descDepartamento'];
            }
            ?>" placeholder="Mi departamento AAA"/><font color="red">&nbsp;*</font>
            <font color="red"><?php echo $a_errores['descDepartamento']; ?></font>
            <br><br>
            <input type="submit" name="enviar" value="Alta">
            <input type="button" name="cancelar" value="Cancelar" onclick="location = '../index.php'">
        </form>
        <?php
        try {
            $miDB = new PDO(HOST_DB, USER_DB, PASS_DB);
            $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            /* switch (true) {
              case (isset($_POST['enviar'])): */
            if (isset($_POST['enviar'])) {
                $a_errores['codDepartamento'] = validacionFormularios::comprobarAlfabetico($_POST['codDepartamento'], 3, 3, 1);
                $a_errores['descDepartamento'] = validacionFormularios::comprobarAlfaNumerico($_POST['descDepartamento'], 100, 5, 1);
                $a_respuesta['codDepartamento'] = strtoupper($_POST['codDepartamento']);
                $statement1 = $miDB->prepare('SELECT * FROM Departamento WHERE CodDepartamento = ' . $a_respuesta['codDepartamento']);
                $statement1->bindParam(':codDepartamento', $a_respuesta['codDepartamento']);

                if ($statement1->rowCount() > 0) {
                    $a_errores['codDepartamento'] = "El departamento con el código " . $a_respuesta['codDepartamento'] . " ya existe.";
                }

                foreach ($a_errores as $campo => $error) {
                    if ($error != null) {
                        $entradaOK = false;
                        $_POST[$campo] = null;
                    }
                }

                /* break;

                  default: */
            } else {
                $entradaOK = false;

                /* break; */
            }

            /* switch (true) {
              case $entradaOK: */
            if ($entradaOK) {
                $a_respuesta['codDepartamento'] = strtoupper($_POST['codDepartamento']);
                $a_respuesta['descDepartamento'] = $_POST['descDepartamento'];
                $statement = $miDB->prepare('INSERT INTO Departamento (CodDepartamento, DescDepartamento) VALUES (:codDepartamento, :descDepartamento)');
                $statement->bindParam(':codDepartamento', $a_respuesta['codDepartamento']);
                $statement->bindParam(':descDepartamento', $a_respuesta['descDepartamento']);
                $statement->execute();
                if ($statement) {
                    ?>
                    <p>Se ha añadido el departamento correctamente.</p>
                    <?php
                    /* break; */
                } else {
                    ?>
                    <p>No se ha podido añadir ese departametno.</p>
                    <?php
                }
            }
        } catch (PDOException $pdoe) {
            ?>
            <p>El departamento ya existe.</p>
            <?php
        } finally {
            unset($miDB);
        }
        ?>
    </body>
</html>