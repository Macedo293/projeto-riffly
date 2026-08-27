<?php
// admin/faixas.php
require_once '../config/conexao.php';

// Buscar produtores para vincular à faixa
$stmtProdutores = $conn->query("SELECT id, nome FROM produtores ORDER BY nome ASC");
$produtores = $stmtProdutores->fetchAll(PDO::FETCH_ASSOC);

// CREATE - Adicionar Faixa
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['titulo'])) {
    $titulo = trim($_POST['titulo']);
    $duracao = trim($_POST['duracao']);
    $produtor_id = (int) $_POST['produtor_id'];

    if (!empty($titulo) && !empty($duracao) && $produtor_id > 0) {
        $stmt = $conn->prepare("INSERT INTO faixas (titulo, duracao, produtor_id) VALUES (:titulo, :duracao, :produtor)");
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':duracao', $duracao);
        $stmt->bindParam(':produtor', $produtor_id);
        $stmt->execute();
        header("Location: faixas.php");
        exit;
    }
}

// DELETE - Excluir Faixa
if (isset($_GET['deletar'])) {
    $id = (int) $_GET['deletar'];
    $stmt = $conn->prepare("DELETE FROM faixas WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    header("Location: faixas.php");
    exit;
}

// READ - Buscar faixas com nome do produtor
$sql = "SELECT f.id, f.titulo, f.duracao, p.nome AS produtor_nome 
        FROM faixas f 
        INNER JOIN produtores p ON f.produtor_id = p.id 
        ORDER BY f.id DESC";
$stmt = $conn->query($sql);
$faixas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Riffly Studio - Faixas & Portfólio</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background-color: #000000; color: #FFFFFF; font-family: 'Roboto', sans-serif; display: flex; min-height: 100vh; }
        
        .sidebar { width: 240px; background-color: #000; border-right: 1px solid #1a1a1a; padding: 24px; display: flex; flex-direction: column; gap: 20px; }
        .sidebar-logo { font-size: 24px; font-weight: 900; letter-spacing: -1px; }
        .menu-item { color: #AAAAAA; text-decoration: none; font-size: 14px; font-weight: 500; display: block; padding: 10px 0; transition: color 0.2s; }
        .menu-item:hover, .menu-item.active { color: #FFFFFF; }

        .main-content { flex: 1; padding: 32px 40px; overflow-y: auto; background: linear-gradient(180deg, #121212 0%, #000000 300px); }
        
        .top-bar { background: #212121; padding: 16px 24px; border-radius: 8px; margin-bottom: 40px; display: flex; flex-wrap: wrap; gap: 12px; align-items: center; }
        .top-bar input, .top-bar select { background: #000000; border: 1px solid #333; color: #FFF; padding: 12px 16px; border-radius: 24px; outline: none; font-family: inherit; font-size: 14px; flex: 1; min-width: 140px; }
        .btn-add { background: #FFFFFF; color: #000000; border: none; padding: 12px 24px; border-radius: 24px; font-weight: 700; cursor: pointer; transition: background 0.2s; white-space: nowrap; }
        .btn-add:hover { background: #DDDDDD; }

        .section-title { font-size: 28px; font-weight: 700; margin-bottom: 24px; letter-spacing: -0.5px; }

        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 24px; }
        .track-card { display: flex; flex-direction: column; position: relative; }
        
        .art-placeholder { width: 100%; aspect-ratio: 1; background-color: #212121; border-radius: 4px; margin-bottom: 12px; position: relative; overflow: hidden; transition: 0.3s; display: flex; align-items: center; justify-content: center; }
        
        .card-actions { position: absolute; inset: 0; background: rgba(0,0,0,0.8); display: flex; justify-content: center; align-items: center; opacity: 0; transition: opacity 0.2s; }
        .track-card:hover .card-actions { opacity: 1; }
        .track-card:hover .art-placeholder { transform: translateY(-4px); box-shadow: 0 8px 16px rgba(0,0,0,0.5); }
        
        .btn-del-action { background: transparent; color: #FF4444; border: 1px solid #FF4444; padding: 8px 16px; border-radius: 20px; font-size: 12px; font-weight: 700; text-decoration: none; }
        .btn-del-action:hover { background: #FF4444; color: #FFF; }

        .track-title { font-size: 14px; font-weight: 700; line-height: 1.2; margin-bottom: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .track-meta { font-size: 14px; color: #AAAAAA; line-height: 1.4; }
    </style>
</head>
<body>

    <aside class="sidebar">
                <div class="sidebar-logo" style="display: flex; align-items: center; gap: 12px; color: #FFF;">
        <img src="../img/logo.png" alt="Logo Riffly" style="width: 55px; height: 55px; object-fit: contain;">
        Riffly
                </div>
        <nav>
            <a href="../index.php" class="menu-item">&larr; App Cliente</a>
            <a href="estilos.php" class="menu-item">Estilos Musicais</a>
            <a href="produtores.php" class="menu-item">Produtores / Estúdios</a>
            <a href="faixas.php" class="menu-item active">Faixas / Portfólio</a>
        </nav>
    </aside>

    <main class="main-content">
        <form method="POST" class="top-bar">
            <input type="text" name="titulo" placeholder="Título da faixa demo..." required>
            <input type="text" name="duracao" placeholder="Duração (ex: 2:45)" required>
            <select name="produtor_id" required>
                <option value="">Selecione o Produtor</option>
                <?php foreach ($produtores as $p): ?>
                    <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nome']) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn-add">Salvar Faixa</button>
        </form>

        <div class="section-title">Portfólio & Demonstrações</div>

        <div class="grid">
            <?php foreach ($faixas as $f): ?>
            <div class="track-card">
                <div class="art-placeholder">
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#666" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>
                        <path d="M15.54 8.46a5 5 0 0 1 0 7.07"></path>
                    </svg>

                    <div class="card-actions">
                        <a href="faixas.php?deletar=<?= $f['id'] ?>" class="btn-del-action" onclick="return confirm('Excluir faixa demo?')">Excluir</a>
                    </div>
                </div>

                <div class="track-title" title="<?= htmlspecialchars($f['titulo']) ?>">
                    <?= htmlspecialchars($f['titulo']) ?>
                </div>
                <div class="track-meta">
                    <?= htmlspecialchars($f['produtor_nome']) ?> • <?= htmlspecialchars($f['duracao']) ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>