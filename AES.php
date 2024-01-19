<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encriptación y Desencriptación AES</title>
</head>
<body>
    <h2>Encriptación y Desencriptación AES</h2>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="mensaje">Mensaje:</label>
        <input type="text" name="mensaje" id="mensaje" required>

        <button type="submit" name="encriptar">Encriptar</button>
        <button type="submit" name="desencriptar">Desencriptar</button>
    </form>

    <?php
    // Procesar el formulario
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $mensaje = $_POST["mensaje"];

        if (isset($_POST["encriptar"])) {
            $mensaje_encriptado = encriptarAES($mensaje);
            echo "<p>Mensaje Encriptado: $mensaje_encriptado</p>";
        } elseif (isset($_POST["desencriptar"])) {
            $mensaje_desencriptado = desencriptarAES($mensaje);
            echo "<p>Mensaje Desencriptado: $mensaje_desencriptado</p>";
        }
    }

    // Funciones de encriptación y desencriptación
    function encriptarAES($mensaje) {
        $clave = "w5p97+SGqlp3YK20/CTZYVFbai/iQqSH6d2z8QOf0IsZOqjwy2gdxEb5VZIbH+yT"; // Debes cambiar esto por tu propia clave
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length("aes-128-cbc"));
        $mensaje_encriptado = openssl_encrypt($mensaje, "aes-128-cbc", $clave, 0, $iv);
        return base64_encode($iv . $mensaje_encriptado);
    }

    function desencriptarAES($mensaje_encriptado) {
        $clave = "w5p97+SGqlp3YK20/CTZYVFbai/iQqSH6d2z8QOf0IsZOqjwy2gdxEb5VZIbH+yT"; // Debes cambiar esto por tu propia clave
        $datos = base64_decode($mensaje_encriptado);
        $iv = substr($datos, 0, openssl_cipher_iv_length("aes-128-cbc"));
        $mensaje_encriptado = substr($datos, openssl_cipher_iv_length("aes-128-cbc"));
        return openssl_decrypt($mensaje_encriptado, "aes-128-cbc", $clave, 0, $iv);
    }
    ?>
</body>
</html>
