<?php
// admin/produtores.php
require_once '../config/conexao.php';

// Buscar estilos para o formulário
$stmtEstilos = $conn->query("SELECT * FROM estilos ORDER BY nome ASC");
$estilos = $stmtEstilos->fetchAll(PDO::FETCH_ASSOC);

// CREATE - Adicionar Produtor
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nome'])) {
    $nome = trim($_POST['nome']);
    $estilo_id = (int) $_POST['estilo_id'];
    $preco = (float) $_POST['preco'];

    if (!empty($nome) && $estilo_id > 0) {
        $stmt = $conn->prepare("INSERT INTO produtores (nome, estilo_id, preco) VALUES (:nome, :estilo, :preco)");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':estilo', $estilo_id);
        $stmt->bindParam(':preco', $preco);
        $stmt->execute();
        header("Location: produtores.php");
        exit;
    }
}

// DELETE - Excluir Produtor
$erro = "";
if (isset($_GET['deletar'])) {
    $id = (int) $_GET['deletar'];
    try {
        $stmt = $conn->prepare("DELETE FROM produtores WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        header("Location: produtores.php");
        exit;
    } catch (PDOException $e) {
        $erro = "Não é possível excluir este produtor pois há faixas vinculadas a ele.";
    }
}

// READ - Buscar produtores 
$sql = "SELECT p.id, p.nome, p.preco, e.nome AS estilo_nome 
        FROM produtores p 
        INNER JOIN estilos e ON p.estilo_id = e.id 
        ORDER BY p.id DESC";
$stmt = $conn->query($sql);
$produtores = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Riffly Studio - Produtores</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">
    <style>
        /* Base inspirada no print (YouTube Music/Spotify style) */
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background-color: #000000; color: #FFFFFF; font-family: 'Roboto', sans-serif; display: flex; min-height: 100vh; }
        
        /* Sidebar lateral (Fake) */
        .sidebar { width: 240px; background-color: #000; border-right: 1px solid #1a1a1a; padding: 24px; display: flex; flex-direction: column; gap: 20px; }
        .sidebar-logo { font-size: 24px; font-weight: 900; letter-spacing: -1px; }
        .menu-item { color: #AAAAAA; text-decoration: none; font-size: 14px; font-weight: 500; display: block; padding: 10px 0; transition: color 0.2s; }
        .menu-item:hover, .menu-item.active { color: #3B82F6; font-weight: 700; }

        /* Conteúdo Principal */
        .main-content { flex: 1; padding: 32px 40px; overflow-y: auto; background: linear-gradient(180deg, #121212 0%, #000000 300px); }
        
        /* Barra de Adicionar (Estilo Search Bar do print) */
        .top-bar { background: #212121; padding: 16px 24px; border-radius: 8px; margin-bottom: 40px; display: flex; flex-wrap: wrap; gap: 12px; align-items: center; }
        .top-bar input, .top-bar select { background: #000000; border: 1px solid #333; color: #FFF; padding: 12px 16px; border-radius: 24px; outline: none; font-family: inherit; font-size: 14px; flex: 1; min-width: 150px; }
        .top-bar input:focus, .top-bar select:focus { border-color: #555; }
        .btn-add { background: #3B82F6; color: #FFFFFF; border: none; padding: 12px 24px; border-radius: 24px; font-weight: 700; cursor: pointer; transition: background 0.2s; white-space: nowrap; }
        .btn-add:hover { background: #2563EB;}

        /* Títulos das Seções */
        .section-title { font-size: 28px; font-weight: 700; margin-bottom: 24px; letter-spacing: -0.5px; }

        /* Grid de Produtores (Estilo Álbuns/Singles) */
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 24px; }
        
        .artist-card { display: flex; flex-direction: column; cursor: pointer; position: relative; }
        
        /* Quadrado que simula a capa do álbum */
        .art-placeholder { width: 100%; aspect-ratio: 1; background-color: #212121; border-radius: 4px; margin-bottom: 12px; position: relative; overflow: hidden; transition: 0.3s; display: flex; align-items: center; justify-content: center; }
        
        /* Ações que aparecem no hover da capa (Editar/Excluir) */
        .card-actions { position: absolute; inset: 0; background: rgba(0,0,0,0.8); display: flex; flex-direction: column; justify-content: center; align-items: center; gap: 10px; opacity: 0; transition: opacity 0.2s; }
        .artist-card:hover .card-actions { opacity: 1; }
        .artist-card:hover .art-placeholder { transform: translateY(-4px); box-shadow: 0 8px 16px rgba(0,0,0,0.5); }
        
        .btn-action { padding: 8px 16px; border-radius: 20px; font-size: 12px; font-weight: 700; text-decoration: none; width: 120px; text-align: center; }
        .btn-edit-action { background: #3B82F6; color: #FFFFFF; }
        .btn-del-action { background: transparent; color: #AAAAAA; border: 1px solid #AAAAAA; }
        .btn-del-action:hover { color: #FF4444; border-color: #FF4444; }

        /* Textos do Card */
        .artist-name { font-size: 14px; font-weight: 700; line-height: 1.2; margin-bottom: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .artist-meta { font-size: 14px; color: #AAAAAA; line-height: 1.4; }
        
        /* Notificações */
        .msg-erro { background: #FF4444; color: white; padding: 12px 24px; border-radius: 4px; margin-bottom: 24px; display: inline-block; font-weight: 500; font-size: 14px; }
    </style>
</head>
<body>

    <!-- Lateral fixa -->
    <aside class="sidebar">
                <div class="sidebar-logo" style="display: flex; align-items: center; gap: 12px; color: #FFF;">
    <img src="../img/logo.png" alt="Logo Riffly" style="width: 55px; height: 55px; object-fit: contain;">
    Riffly
                </div>
        <nav>
            <a href="../index.php" class="menu-item">&larr; App Cliente</a>
            <a href="estilos.php" class="menu-item">Estilos Musicais</a>
            <a href="produtores.php" class="menu-item active">Produtores / Estúdios</a>
        </nav>
    </aside>

    <!-- Conteúdo Dinâmico -->
    <main class="main-content">
        
        <?php if($erro) echo "<div class='msg-erro'>$erro</div>"; ?>

        <!-- Formulário (Estilo barra de busca do print) -->
        <form method="POST" class="top-bar">
            <input type="text" name="nome" placeholder="Adicionar Produtor/Estúdio..." required>
            <select name="estilo_id" required>
                <option value="">Gênero principal</option>
                <?php foreach ($estilos as $est): ?>
                    <option value="<?= $est['id'] ?>"><?= htmlspecialchars($est['nome']) ?></option>
                <?php endforeach; ?>
            </select>
            <input type="number" name="preco" placeholder="Preço (Ex: 350)" step="0.01" required>
            <button type="submit" class="btn-add">Salvar</button>
        </form>

        <div class="section-title">Produtores & Estúdios</div>

        <!-- O Grid que substitui a Tabela -->
        <div class="grid">
            <?php foreach ($produtores as $prod): ?>
            <div class="artist-card">
                <!-- Capa Genérica (Aparece ações no hover) -->
                <div class="art-placeholder" style="background: linear-gradient(135deg, hsl(<?= 200 + ($prod['id'] * 30 % 100) ?>, 80%, 40%), #111);">
                    <!-- Ícone genérico musical SVG -->
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#555" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 18V5l12-2v13"></path>
                        <circle cx="6" cy="18" r="3"></circle>
                        <circle cx="18" cy="16" r="3"></circle>
                    </svg>

                    <!-- Botões de Ação Ocultos (Aparecem ao passar o mouse) -->
                    <div class="card-actions">
                        <a href="produtor_editar.php?id=<?= $prod['id'] ?>" class="btn-action btn-edit-action">Editar</a>
                        <a href="produtores.php?deletar=<?= $prod['id'] ?>" class="btn-action btn-del-action" onclick="return confirm('Tem certeza?')">Excluir</a>
                    </div>
                </div>

                <!-- Info -->
                <div class="artist-name" title="<?= htmlspecialchars($prod['nome']) ?>">
                    <?= htmlspecialchars($prod['nome']) ?>
                </div>
                <div class="artist-meta">
                    <?= htmlspecialchars($prod['estilo_nome']) ?> • R$ <?= number_format($prod['preco'], 2, ',', '.') ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

    </main>
</body>
</html>