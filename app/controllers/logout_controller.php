<?php
session_start();

// Destruir toda la sesión
session_unset();
session_destroy();

// Redirigir al usuario al index.php
header("Location: ../views/index.php");
exit();
