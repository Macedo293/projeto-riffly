<?php
// admin/produtor_editar.php
require_once '../config/conexao.php';

if (!isset($_GET['id'])) { header("Location: produtores.php"); exit; }
$id = (int) $_GET['id'];

// Atualizar dados
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nome'])) {
    $nome = trim($_POST['nome']);
    $estilo_id = (int) $_POST['estilo_id'];
    $preco = (float) $_POST['preco'];

    $stmt = $conn->prepare("UPDATE produtores SET nome = :nome, estilo_id = :estilo, preco = :preco WHERE id = :id");
    $stmt->execute([':nome' => $nome, ':estilo' => $estilo_id, ':preco' => $preco, ':id' => $id]);
    header("Location: produtores.php");
    exit;
}

// Buscar dados atuais do produtor
$stmt = $conn->prepare("SELECT * FROM produtores WHERE id = :id");
$stmt->execute([':id' => $id]);
$prod = $stmt->fetch(PDO::FETCH_ASSOC);

// Buscar estilos para o select
$estilos = $conn->query("SELECT * FROM estilos ORDER BY nome ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Produtor</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        /* Mesmo visual clean da tela anterior */
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background-color: #121214; color: #E1E1E6; font-family: 'Poppins', sans-serif; padding: 60px 20px; display: flex; justify-content: center; }
        .card-form { background: #18181B; padding: 32px; border-radius: 12px; width: 100%; max-width: 400px; border: 1px solid #27272A; box-shadow: 0 4px 20px rgba(0,0,0,0.2); }
        h2 { text-align: center; margin-bottom: 24px; font-weight: 600; }
        input, select { width: 100%; padding: 12px 16px; margin-bottom: 16px; border-radius: 8px; border: 1px solid #3F3F46; background: #27272A; color: #FFF; font-family: 'Poppins', sans-serif; outline: none; }
        input:focus, select:focus { border-color: #8B5CF6; }
        button { width: 100%; padding: 12px; border: none; border-radius: 8px; background: #8B5CF6; color: #FFF; font-weight: 600; cursor: pointer; transition: 0.2s; font-family: 'Poppins', sans-serif; margin-bottom: 12px; }
        button:hover { background: #7C3AED; }
        .btn-cancel { display: block; text-align: center; color: #A1A1AA; text-decoration: none; font-size: 14px; transition: 0.2s; }
        .btn-cancel:hover { color: #FFF; }
        label { font-size: 12px; color: #A1A1AA; margin-bottom: 4px; display: block; }
    </style>
</head>
<body>
    <div class="card-form">
        <h2>Editar Perfil</h2>
        <form method="POST">
            <label>Nome do Produtor/Estúdio</label>
            <input type="text" name="nome" value="<?= htmlspecialchars($prod['nome']) ?>" required>
            
            <label>Estilo Musical Principal</label>
            <select name="estilo_id" required>
                <?php foreach ($estilos as $est): ?>
                    <option value="<?= $est['id'] ?>" <?= ($est['id'] == $prod['estilo_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($est['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            <label>Preço por Sessão (R$)</label>
            <input type="number" name="preco" value="<?= $prod['preco'] ?>" step="0.01" required>
            
            <button type="submit">Atualizar Dados</button>
        </form>
        <a href="produtores.php" class="btn-cancel">Cancelar</a>
    </div>
</body>
</html>