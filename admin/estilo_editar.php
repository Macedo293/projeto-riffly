<?php
require_once '../config/conexao.php';
if (!isset($_GET['id'])) { header("Location: estilos.php"); exit; }
$id = (int) $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nome'])) {
    $stmt = $conn->prepare("UPDATE estilos SET nome = :nome WHERE id = :id");
    $stmt->execute([':nome' => trim($_POST['nome']), ':id' => $id]);
    header("Location: estilos.php"); exit;
}
$estilo = $conn->prepare("SELECT * FROM estilos WHERE id = :id");
$estilo->execute([':id' => $id]);
$est = $estilo->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Estilo</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">
    <style>
        body { background-color: #000; color: #FFF; font-family: 'Roboto', sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .card { background: #121212; padding: 40px; border-radius: 12px; width: 100%; max-width: 400px; border: 1px solid #333; }
        input { width: 100%; background: #000; border: 1px solid #333; color: #FFF; padding: 12px; border-radius: 8px; margin: 20px 0; outline: none; box-sizing: border-box; }
        button { width: 100%; background: #FFF; color: #000; border: none; padding: 12px; border-radius: 8px; font-weight: bold; cursor: pointer; margin-bottom: 10px; }
        a { color: #888; text-decoration: none; font-size: 14px; display: block; text-align: center; }
    </style>
</head>
<body>
    <div class="card">
        <h2 style="margin:0 0 10px 0;">Editar Gênero</h2>
        <form method="POST">
            <input type="text" name="nome" value="<?= htmlspecialchars($est['nome']) ?>" required>
            <button type="submit">Salvar Alterações</button>
        </form>
        <a href="estilos.php">Cancelar</a>
    </div>
</body>
</html>