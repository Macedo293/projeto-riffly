<?php
// index.php
require_once 'config/conexao.php';

$stmtEstilos = $conn->query("SELECT * FROM estilos ORDER BY nome ASC");
$estilos = $stmtEstilos->fetchAll(PDO::FETCH_ASSOC);

$sqlProdutores = "SELECT p.id, p.nome, p.preco, e.nome AS estilo_nome 
                  FROM produtores p INNER JOIN estilos e ON p.estilo_id = e.id 
                  ORDER BY p.id DESC";
$stmtProd = $conn->query($sqlProdutores);
$produtores = $stmtProd->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Riffly - Web Player</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background-color: #000000; color: #FFFFFF; font-family: 'Roboto', sans-serif; display: flex; min-height: 100vh; }
        
        /* Sidebar do Cliente */
        .sidebar { width: 240px; background-color: #000; border-right: 1px solid #1a1a1a; padding: 24px; display: flex; flex-direction: column; gap: 20px; flex-shrink: 0; }
        .sidebar-logo { font-size: 24px; font-weight: 900; letter-spacing: -1px; }
        .menu-item { color: #AAAAAA; text-decoration: none; font-size: 14px; font-weight: 500; display: block; padding: 10px 0; transition: color 0.2s; }
        .menu-item:hover, .menu-item.active { color: #3B82F6; font-weight: 700; }
        
        /* Botão para o Admin no fim da sidebar */
        .admin-link { margin-top: auto; color: #555; text-decoration: none; font-size: 12px; font-weight: 700; padding: 10px 0; border-top: 1px solid #1a1a1a; transition: 0.2s; }
        .admin-link:hover { color: #FFF; }

        .main-content { flex: 1; padding: 32px 40px; overflow-y: auto; background: linear-gradient(180deg, #121212 0%, #000000 300px); }
        
        .top-bar { display: flex; align-items: center; gap: 16px; margin-bottom: 40px; }
        .search-box { background: #212121; padding: 12px 24px; border-radius: 24px; color: #FFF; font-size: 14px; width: 100%; max-width: 400px; display: flex; align-items: center; border: 1px solid #333; }
        
        /* Filtros/Tags como as "Moods & Genres" */
        .tags-container { display: flex; gap: 12px; margin-bottom: 32px; overflow-x: auto; padding-bottom: 8px; }
        .tag { background: #212121; color: #FFF; padding: 8px 16px; border-radius: 16px; font-size: 13px; font-weight: 500; cursor: pointer; transition: 0.2s; white-space: nowrap; }
        .tag:hover { background: #333; }
        .tag.active { background: #3B82F6; color: #FFF; }

        .section-title { font-size: 28px; font-weight: 700; margin-bottom: 24px; letter-spacing: -0.5px; }

        /* Grid igual ao admin, mas sem as ações de deletar */
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 24px; }
        .artist-card { display: flex; flex-direction: column; cursor: pointer; }
        .art-placeholder { width: 100%; aspect-ratio: 1; background-color: #212121; border-radius: 4px; margin-bottom: 12px; transition: 0.3s; display: flex; align-items: center; justify-content: center; position: relative; }
        .artist-card:hover .art-placeholder { transform: translateY(-4px); box-shadow: 0 8px 16px rgba(0,0,0,0.5); }
        
        /* Ícone Play falso que aparece ao passar o mouse */
        /* Modifique a cor de fundo do botão para azul */
        .play-btn { position: absolute; bottom: 10px; right: 10px; width: 40px; height: 40px; background: #3B82F6; border-radius: 50%; display: flex; align-items: center; justify-content: center; opacity: 0; transform: translateY(10px); transition: 0.3s; box-shadow: 0 4px 8px rgba(0,0,0,0.3); }
        .artist-card:hover .play-btn { opacity: 1; transform: translateY(0); }
        
        .artist-name { font-size: 14px; font-weight: 700; line-height: 1.2; margin-bottom: 4px; }
        .artist-meta { font-size: 14px; color: #AAAAAA; }
    </style>
</head>
<body>

          <aside class="sidebar">
                  <div class="sidebar-logo" style="display: flex; align-items: center; gap: 12px; color: #FFF;">
    <img src="img/logo.png" alt="Logo Riffly" style="width: 55px; height: 55px; object-fit: contain;">
    Riffly
                  </div>
              <nav>
            <a href="index.php" class="menu-item active">Início</a>
            <a href="#" class="menu-item">Explorar</a>
            <a href="#" class="menu-item">Biblioteca</a>
        </nav>
        
        <!-- O botão de navegação para o painel admin -->
        <a href="admin/produtores.php" class="admin-link">🛠️ Acesso Restrito (Admin)</a>
    </aside>

    <main class="main-content">
        <div class="top-bar">
            <div class="search-box">🔍 Buscar músicas, artistas ou podcasts...</div>
        </div>

        <div class="tags-container">
            <div class="tag active">Tudo</div>
            <?php foreach ($estilos as $est): ?>
                <div class="tag"><?= htmlspecialchars($est['nome']) ?></div>
            <?php endforeach; ?>
        </div>

        <div class="section-title">Produtores Recomendados</div>

        <div class="grid">
            <?php if (empty($produtores)): ?>
                <p style="color: #666;">Nenhum produtor cadastrado no sistema ainda.</p>
            <?php endif; ?>

            <?php foreach ($produtores as $p): ?>
            <div class="artist-card">
               <div class="art-placeholder" style="background: linear-gradient(135deg, hsl(<?= 200 + ($p['id'] * 30 % 100) ?>, 80%, 40%), #111);">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#555" stroke-width="1.5">
                        <path d="M9 18V5l12-2v13"></path>
                        <circle cx="6" cy="18" r="3"></circle><circle cx="18" cy="16" r="3"></circle>
                    </svg>
                    <!-- Botão de play animado -->
                    <div class="play-btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="#FFFFFF" stroke="#FFFFFF" stroke-width="2"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                    </div>
                </div>
                <div class="artist-name"><?= htmlspecialchars($p['nome']) ?></div>
                <div class="artist-meta"><?= htmlspecialchars($p['estilo_nome']) ?> • R$ <?= number_format($p['preco'], 2, ',', '.') ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>