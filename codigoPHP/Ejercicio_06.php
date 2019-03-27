<!DOCTYPE html>
<html>
    <head>
        <title>Ejercicio 06 - Israel García Cabañeros</title>
    </head>
    <body>
        <?php
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        require_once '../core/validacionFormularios.php';
        require_once '../config/config.php';

        $entradaOK = true;
        $transactionOK = true;

        $a_errores[][] = '';
        $a_respuesta[][] = '';

        $contadorDepartamento = 0;
        $numeroInsercion = 0;

        while ($contadorDepartamento < 4) {
            $a_errores[$numeroInsercion]['codDepartamento'] = null;
            $a_respuesta[$numeroInsercion]['codDepartamento'] = null;

            $a_errores[$numeroInsercion]['descDepartamento'] = null;
            $a_respuesta[$numeroInsercion]['descDepartamento'] = null;

            $contadorDepartamento++;
            $numeroInsercion++;
        }

        $numero = $contadorDepartamento;

        switch (true) {
            case (isset($_POST['enviar'])):
                for ($contadorDepartamento = 1; $contadorDepartamento < $numero; $contadorDepartamento++) {
                    $a_errores[$contadorDepartamento]['codDepartamento'] = validacionFormularios::comprobarAlfabetico($_POST['codDepartamento' . $contadorDepartamento], 3, 3, 1);
                    $a_errores[$contadorDepartamento]['descDepartamento'] = validacionFormularios::comprobarAlfaNumerico($_POST['descDepartamento' . $contadorDepartamento], 100, 5, 1);
                }

                foreach ($a_errores as $contadorDepartamento => $a_departamento) {
                    foreach ($a_departamento as $numeroInsercion => $departamento) {
                        if ($a_errores[$contadorDepartamento][$numeroInsercion] != null) {
                            $entradaOK = false;
                            $_POST[$numeroInsercion . $contadorDepartamento] = '';
                        }
                    }
                }

                break;

            default:
                $entradaOK = false;

                break;
        }

        switch (true) {
            case $entradaOK:
                for ($contadorDepartamento = 1; $contadorDepartamento < $numero; $contadorDepartamento++) {
                    $a_respuesta[$contadorDepartamento]['codDepartamento'] = strtoupper($_POST['codDepartamento' . $contadorDepartamento]);
                    $a_respuesta[$contadorDepartamento]['descDepartamento'] = $_POST['descDepartamento' . $contadorDepartamento];
                }

                try {
                    $miDB = new PDO(HOST_DB, USER_DB, PASS_DB);
                    $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    ?>
                    <p>Conexión correcta.</p>
                    <?php
                    $miDB->beginTransaction();
                    $sql = 'INSERT INTO Departamento (CodDepartamento, DescDepartamento) VALUES (:codDepartamento, :descDepartamento)';
                    $statement = $miDB->prepare($sql);

                    for ($numeroInsercion = 1; $numeroInsercion < $numero; $numeroInsercion++) {

                        $statement->bindParam(':codDepartamento', $a_respuesta[$numeroInsercion]['codDepartamento']);
                        $statement->bindParam(':descDepartamento', $a_respuesta[$numeroInsercion]['descDepartamento']);
                    }
                    if (!$statement->execute()) {
                        $transactionOK = false;
                    }

                    if ($transactionOK) {
                        $miDB->commit();
                        ?>
                        <p>Transacción realizada con éxito.</p>
                        <?php
                    } else {
                        $miDB->rollBack();
                        ?>
                        <p>La transacción no ha podido ser realizada.</p>
                        <?php
                    }
                } catch (PDOException $pdoe) {
                    $miDB->rollBack();
                    ?>
                    <p>La transacción no ha podido ser realizada.</p>
                    <?php
                    echo $pdoe->getMessage();
                } finally {
                    unset($miDB);
                }

                break;

            default:
                ?>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                    <?php for ($numeroInsercion = 1; $numeroInsercion < $numero; $numeroInsercion++) { ?>
                        <label for="codDepartamento">Código departamento Nº<?php echo $numeroInsercion ?>:&nbsp;</label>
                        <input size="3" type="text" name="codDepartamento<?php echo $numeroInsercion ?>" value="<?php
                        if (isset($_REQUEST['codDepartamento' . $numeroInsercion]) && is_null($a_errores[$numeroInsercion]['codDepartamento'])) {
                            echo $_REQUEST['codDepartamento' . $numeroInsercion];
                        }
                        ?>" placeholder="AAA"/>
                        <font color="red">&nbsp;*</font>
                        <font color="red"><?php echo $a_errores[$numeroInsercion]['codDepartamento']; ?></font>
                        <br>
                        <label for="descDepartamento">Descripción departamento Nº<?php echo $numeroInsercion ?>:&nbsp;</label>
                        <input size="50" type="text" name="descDepartamento<?php echo $numeroInsercion ?>" value="<?php
                        if (isset($_REQUEST['descDepartamento' . $numeroInsercion]) && is_null($a_errores[$numeroInsercion]['descDepartamento'])) {
                            echo $_REQUEST['descDepartamento' . $numeroInsercion];
                        }
                        ?>" placeholder="Mi departamento AAA"/>
                        <font color="red">&nbsp;*</font>
                        <font color="red"><?php echo $a_errores[$numeroInsercion]['descDepartamento']; ?></font>
                        <br><br>
                    <?php } ?>
                    <input type="submit" name="enviar" value="Alta"/>
                </form>  
                <?php
                break;
        }
        ?>
    </body>
</html>
