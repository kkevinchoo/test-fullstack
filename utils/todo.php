<?php

    /**
     * Función para evitar inyecciones
     */
    function cleanInput($input) {
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);
        return $input;
    }

    /**
     * Encryptación con método nativo de PHP
     */
    function encryptPass($pass) {
        return password_hash($pass, PASSWORD_BCRYPT);
    }

?>