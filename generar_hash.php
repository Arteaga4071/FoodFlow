<?php
/**
 * FoodFlow - Utilidad para generar contraseñas
 * ------------------------------------------------
 * Este archivo NO es parte del framework, es solo una herramienta.
 * Colócalo en la raíz de tu proyecto (junto a index.php) y ábrelo
 * en el navegador: http://localhost/foodflow/generar_hash.php?pass=miclave
 *
 * Copia el hash resultante y pégalo en la columna "password" de la
 * tabla "usuarios" desde phpMyAdmin para crear o cambiar un usuario.
 *
 * IMPORTANTE: elimina este archivo cuando termines, por seguridad.
 */

$password = isset($_GET['pass']) ? $_GET['pass'] : null;

if ($password) {
    $hash = password_hash($password, PASSWORD_BCRYPT);
    echo "<h3>Hash generado para: " . htmlspecialchars($password) . "</h3>";
    echo "<textarea style='width:100%;height:80px'>" . $hash . "</textarea>";
} else {
    echo "<form method='get'>
        <p>Escribe una contraseña y genera su hash bcrypt:</p>
        <input type='text' name='pass' placeholder='Ej: 123456'>
        <button type='submit'>Generar</button>
    </form>";
}
