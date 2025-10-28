<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0">Cadastro de Usuário</h3>
            </div>
            <div class="card-body">
                <?php
                if (isset($mensagem)) {
                    $alerta_tipo = strpos($mensagem, 'sucesso') !== false ? 'success' : 'danger';
                    echo "<div class='alert alert-$alerta_tipo' role='alert'>$mensagem</div>";
                }
                ?>
                <form action="processa_cadastro.php" method="POST" enctype="multipart/form-data">
                    
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome Completo</label>
                        <input type="text" class="form-control" id="nome" name="nome" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                        <div class="form-text">Deve ser um e-mail válido.</div>
                    </div>

                    <div class="mb-3">
                        <label for="cpf" class="form-label">CPF</label>
                        <input type="text" class="form-control" id="cpf" name="cpf" required maxlength="14" placeholder="000.000.000-00">
                        <div class="form-text">Deve ser um CPF válido.</div>
                    </div>

                    <div class="mb-3">
                        <label for="imagem" class="form-label">Upload de Imagem</label>
                        <input type="file" class="form-control" id="imagem" name="imagem" accept="image/*" required>
                        <div class="form-text">Obrigatório. Apenas imagens (jpeg, png, etc.).</div>
                    </div>

                    <button type="submit" class="btn btn-success">Cadastrar</button>
                    
                </form>
            </div>
        </div>

        <h3 class="mt-5 mb-3">📋 Usuários Cadastrados</h3>
            <?php
            require_once 'conexao.php'; 

            try {
                $stmt = $pdo -> query("SELECT id, nome, email, cpf, caminho_imagem, data_cadastro FROM usuarios ORDER BY id DESC");
                
                if ($stmt -> rowCount() > 0) {
            ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>#ID</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>CPF</th>
                            <th>Imagem</th>
                            <th>Data Cadastro</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        while ($usuario = $stmt -> fetch()) {
                            $caminho_img = $usuario['caminho_imagem'];
                            $imagem_display = '';

                            if (!empty($caminho_img) && file_exists($caminho_img)) { $imagem_display = "<img src='{$caminho_img}' alt='Foto de {$usuario['nome']}' style='width: 50px; height: 50px; object-fit: cover; border-radius: 5px;'>"; }
                            else { $imagem_display = "Sem Imagem"; }

                            $data_formatada = (new DateTime($usuario['data_cadastro'])) -> format('d/m/Y H:i');
                    ?>
                        <tr>
                            <td><?php echo $usuario['id']; ?></td>
                            <td><?php echo htmlspecialchars($usuario['nome']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['cpf']); ?></td>
                            <td><?php echo $imagem_display; ?></td>
                            <td><?php echo $data_formatada; ?></td>
                            <td>
                                <a href="editar.php?id=<?php echo $usuario['id']; ?>" class="btn btn-warning btn-sm"> Editar </a>

                                <form action="processa_cadastro.php" method="POST" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja excluir este usuário?');">
                                    <input type="hidden" name="acao" value="deletar">
                                    <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm"> Excluir </button>
                                </form>
                            </td>   
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
            <?php
                } else { echo "<div class='alert alert-info'>Nenhum usuário cadastrado ainda.</div>"; }
            } catch (\PDOException $e) { echo "<div class='alert alert-danger'>Erro ao consultar o banco de dados: " . $e -> getMessage() . "</div>"; }
            ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
