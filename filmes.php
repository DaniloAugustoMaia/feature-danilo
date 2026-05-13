<?php
// Inicia a sessão para que o array persista entre os carregamentos de página
session_start();

// --- INICIALIZAÇÃO ---
// Se não existir a lista de filmes na sessão, cria um array vazio
if (!isset($_SESSION['filmes'])) {
    $_SESSION['filmes'] = [];
}

// Criamos uma referência ou cópia para facilitar o uso no código
$filmes = &$_SESSION['filmes'];
$filmes[] = [
    'id' => uniqid(),
    'titulo' => 'Gabriel',
    'diretor' => 'Priori de Morais',
    'ano' => 2007
];
// --- LÓGICA: ADICIONAR ---
if (isset($_POST['adicionar'])) {
if (empty($filmes)) {
    $filmes[] = [
        'id' => uniqid(),
        'titulo' => 'Matrix',
        'diretor' => 'Lana e Lilly Wachowski',
        'ano' => 1999
    ];
}
    $filmes[] = $novoFilme;
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// --- LÓGICA: REMOVER ---
if (isset($_GET['remover'])) {
    foreach ($filmes as $index => $f) {
        if ($f['id'] === $_GET['remover']) {
            unset($filmes[$index]);
            break;
        }
    }
    // Reorganiza os índices do array
    $filmes = array_values($filmes);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// --- LÓGICA: EDITAR (Prepara os dados) ---
$filmeParaEditar = null;
if (isset($_GET['editar'])) {
    foreach ($filmes as $f) {
        if ($f['id'] === $_GET['editar']) {
            $filmeParaEditar = $f;
            break;
        }
    }
}

// --- LÓGICA: ATUALIZAR ---
if (isset($_POST['atualizar'])) {
    foreach ($filmes as &$f) {
        if ($f['id'] === $_POST['id']) {
            $f['titulo'] = $_POST['titulo'];
            $f['diretor'] = $_POST['diretor'];
            $f['ano'] = $_POST['ano'];
            break;
        }
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>CRUD de Filmes (Array em Sessão)</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 40px; background-color: #f0f2f5; }
        .container { max-width: 800px; margin: auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        form { margin-bottom: 30px; display: flex; gap: 10px; flex-wrap: wrap; }
        input { padding: 8px; border: 1px solid #ccc; border-radius: 4px; flex: 1; min-width: 150px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; border-bottom: 1px solid #eee; text-align: left; }
        th { background-color: #007bff; color: white; }
        .btn { padding: 8px 12px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; font-size: 14px; display: inline-block; }
        .btn-add { background-color: #28a745; color: white; }
        .btn-edit { background-color: #ffc107; color: black; }
        .btn-del { background-color: #dc3545; color: white; }
    </style>
</head>
<body>

<div class="container">
    <h2><?= $filmeParaEditar ? "Editar Filme" : "Adicionar Filme" ?></h2>
    
    <form method="POST">
        <?php if ($filmeParaEditar): ?>
            <input type="hidden" name="id" value="<?= $filmeParaEditar['id'] ?>">
        <?php endif; ?>
        
        <input type="text" name="titulo" placeholder="Título do Filme" value="<?= $filmeParaEditar['titulo'] ?? '' ?>" required>
        <input type="text" name="diretor" placeholder="Diretor" value="<?= $filmeParaEditar['diretor'] ?? '' ?>">
        <input type="number" name="ano" placeholder="Ano" value="<?= $filmeParaEditar['ano'] ?? '' ?>">
        
        <?php if ($filmeParaEditar): ?>
            <button type="submit" name="atualizar" class="btn btn-add">Atualizar</button>
            <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-edit">Cancelar</a>
        <?php else: ?>
            <button type="submit" name="adicionar" class="btn btn-add">Adicionar</button>
        <?php endif; ?>
    </form>

    <h2>Lista de Filmes</h2>
    <table>
        <thead>
            <tr>
                <th>Título</th>
                <th>Diretor</th>
                <th>Ano</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($filmes as $f): ?>
            <tr>
                <td><?= htmlspecialchars($f['titulo']) ?></td>
                <td><?= htmlspecialchars($f['diretor']) ?></td>
                <td><?= htmlspecialchars($f['ano']) ?></td>
                <td>
                    <a href="?editar=<?= $f['id'] ?>" class="btn btn-edit">Editar</a>
                    <a href="?remover=<?= $f['id'] ?>" class="btn btn-del" onclick="return confirm('Excluir este filme?')">Remover</a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($filmes)): ?>
                <tr><td colspan="4">Nenhum filme cadastrado na sessão.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    <br>
    <small>* Os dados serão perdidos se você fechar o navegador ou limpar os cookies.</small>
</div>

</body>
</html>
