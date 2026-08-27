<?php
// admin/estilos.php
require_once '../config/conexao.php';

// CREATE 
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nome'])) {
    $nome = trim($_POST['nome']);
    if (!empty($nome)) {
        $stmt = $conn->prepare("INSERT INTO estilos (nome) VALUES (:nome)");
        $stmt->bindParam(':nome', $nome);
        $stmt->execute();
        header("Location: estilos.php");
        exit;
    }
}

// DELETE
$erro = "";
if (isset($_GET['deletar'])) {
    $id = (int) $_GET['deletar'];
    try {
        $stmt = $conn->prepare("DELETE FROM estilos WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        header("Location: estilos.php");
        exit;
    } catch (PDOException $e) {
        $erro = "Erro: Este estilo está sendo usado por um produtor.";
    }
}

// READ
$stmt = $conn->query("SELECT * FROM estilos ORDER BY nome ASC");
$estilos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Riffly Studio - Estilos</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background-color: #000000; color: #FFFFFF; font-family: 'Roboto', sans-serif; display: flex; min-height: 100vh; }
        
        /* Barra de Navegação Lateral */
        .sidebar { width: 240px; background-color: #000; border-right: 1px solid #1a1a1a; padding: 24px; display: flex; flex-direction: column; gap: 20px; flex-shrink: 0; }
        .sidebar-logo { font-size: 24px; font-weight: 900; letter-spacing: -1px; }
        .menu-item { color: #AAAAAA; text-decoration: none; font-size: 14px; font-weight: 500; display: block; padding: 10px 0; transition: color 0.2s; }
        .menu-item:hover, .menu-item.active { color: #3B82F6; font-weight: 700; }

        .main-content { flex: 1; padding: 32px 40px; overflow-y: auto; background: linear-gradient(180deg, #121212 0%, #000000 300px); }
        
        .top-bar { background: #212121; padding: 16px 24px; border-radius: 8px; margin-bottom: 40px; display: flex; flex-wrap: wrap; gap: 12px; align-items: center; }
        .top-bar input { background: #000000; border: 1px solid #333; color: #FFF; padding: 12px 16px; border-radius: 24px; outline: none; font-family: inherit; font-size: 14px; flex: 1; }
        .top-bar input:focus { border-color: #555; }
        .btn-add { background: #3B82F6; color: #FFFFFF; border: none; padding: 12px 24px; border-radius: 24px; font-weight: 700; cursor: pointer; transition: background 0.2s; white-space: nowrap; }
        .btn-add:hover { background: #2563EB;}

        .section-title { font-size: 28px; font-weight: 700; margin-bottom: 24px; letter-spacing: -0.5px; }

        /* Grid de Estilos */
        .grid-estilos { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 16px; }
        .genre-card { background: #212121; padding: 24px 20px; border-radius: 8px; display: flex; justify-content: space-between; align-items: center; border-left: 4px solid #555; transition: 0.2s; }
        .genre-card:hover { background: #2a2a2a; border-left-color: #FFF; transform: translateY(-2px); }
        .genre-name { font-size: 16px; font-weight: 700; }
        
        .btn-del { color: #888; text-decoration: none; font-size: 12px; font-weight: 700; padding: 6px 12px; border-radius: 12px; border: 1px solid #444; transition: 0.2s; }
        .btn-del:hover { color: #FF4444; border-color: #FF4444; }
        .msg-erro { background: #FF4444; color: white; padding: 12px 24px; border-radius: 4px; margin-bottom: 24px; display: inline-block; font-weight: 500; font-size: 14px; }
    </style>
</head>
<body>
    <aside class="sidebar">
               <div class="sidebar-logo" style="display: flex; align-items: center; gap: 12px; color: #FFF;">
    <img src="../img/logo.png" alt="Logo Riffly" style="width: 44px; height: 44px; object-fit: contain;">
    Riffly
              </div>
                <nav>
            <a href="../index.php" class="menu-item">&larr; App Cliente</a>
            <a href="estilos.php" class="menu-item active">Estilos Musicais</a>
            <a href="produtores.php" class="menu-item">Produtores / Estúdios</a>
        </nav>
    </aside>

    <main class="main-content">
        <?php if($erro) echo "<div class='msg-erro'>$erro</div>"; ?>

        <form method="POST" class="top-bar">
            <input type="text" name="nome" placeholder="Novo gênero (Ex: Indie, Rock, Trap)..." required>
            <button type="submit" class="btn-add">Adicionar</button>
        </form>

        <div class="section-title">Gêneros Cadastrados</div>

        <div class="grid-estilos">
            <?php foreach ($estilos as $est): ?>
            <div class="genre-card">
                <span class="genre-name"><?= htmlspecialchars($est['nome']) ?></span>
                <div style="display: flex; gap: 8px;">
                    <a href="estilo_editar.php?id=<?= $est['id'] ?>" class="btn-del" style="color: #FFF; border-color: #555;">Editar</a>
                    <a href="estilos.php?deletar=<?= $est['id'] ?>" class="btn-del" onclick="return confirm('Excluir este estilo?')">Excluir</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>