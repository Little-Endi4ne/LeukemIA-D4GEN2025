<?php
// Vérifier si la session est démarrée, sinon la démarrer
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<footer>
    <div class="container">
        <p>&copy; 2025 LeukemIA - Outil d'aide à la décision médicale pour les patients atteints de leucémie</p>
    </div>
</footer>
</body>
</html>