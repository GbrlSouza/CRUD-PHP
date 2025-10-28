<?php
require 'conexao.php';

$mensagem = "";
$usuario = null;

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $stmt = $pdo->prepare("SELECT id, nome, email, cpf, caminho_imagem FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        $usuario = $stmt->fetch();

        if (!$usuario) {
            $mensagem = "Usuário não encontrado.";
        }
    } catch (\PDOException $e) {
        $mensagem = "Erro ao carregar dados: " . $e->getMessage();
    }
} else {
    $mensagem = "ID de usuário inválido.";
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-warning text-white">
                <h3 class="mb-0">✏️ Editar Usuário: <?php echo htmlspecialchars($usuario['nome'] ?? 'Erro'); ?></h3>
            </div>
            <div class="card-body">
                <?php
                if (!empty($mensagem)) {
                    $alerta_tipo = (strpos($mensagem, 'Erro') !== false || strpos($mensagem, 'inválido') !== false) ? 'danger' : 'info';
                    echo "<div class='alert alert-$alerta_tipo' role='alert'>$mensagem</div>";
                }
                
                if ($usuario):
                ?>
                    <form action="processa_edicao.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
                        
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome Completo</label>
                            <input type="text" class="form-control" id="nome" name="nome" value="<?php echo htmlspecialchars($usuario['nome']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="cpf" class="form-label">CPF</label>
                            <input type="text" class="form-control" id="cpf" name="cpf" value="<?php echo htmlspecialchars($usuario['cpf']); ?>" required maxlength="14">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Imagem Atual</label><br>
                            <?php if (!empty($usuario['caminho_imagem'])): ?>
                                <img src="<?php echo htmlspecialchars($usuario['caminho_imagem']); ?>" style="width: 100px; height: 100px; object-fit: cover; border: 1px solid #ccc;">
                                <input type="hidden" name="imagem_antiga" value="<?php echo htmlspecialchars($usuario['caminho_imagem']); ?>">
                            <?php else: ?>
                                <p>Nenhuma imagem cadastrada.</p>
                                <input type="hidden" name="imagem_antiga" value="">
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="imagem" class="form-label">Nova Imagem (Opcional)</label>
                            <input type="file" class="form-control" id="imagem" name="imagem" accept="image/*">
                            <div class="form-text">Envie um arquivo apenas se desejar substituir a imagem atual.</div>
                        </div>

                        <button type="submit" class="btn btn-success">Salvar Alterações</button>
                        <a href="cadastro.php" class="btn btn-secondary">Cancelar</a>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>