<?php
require 'conexao.php';
require 'processa_cadastro.php';

$mensagem = "";
$erros = [];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = $_POST['id'];
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $cpf_formatado = trim($_POST['cpf']);
    $cpf_apenas_numeros = preg_replace('/[^0-9]/', '', $cpf_formatado);
    $imagem_antiga = $_POST['imagem_antiga'] ?? null;
    $caminho_completo = $imagem_antiga;

    if (empty($nome)) $erros[] = "O campo Nome é obrigatório.";
    if (!validarEmail($email)) $erros[] = "O E-mail inserido é inválido.";
    if (!validarCPF($cpf_apenas_numeros)) $erros[] = "O CPF inserido é inválido.";
    
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $imagem = $_FILES['imagem'];
        $diretorio_upload = "uploads/"; 
        $nome_original = basename($imagem['name']);
        
        $extensoes_permitidas = ['image/jpeg', 'image/png', 'image/gif'];
        $tipo_mime = mime_content_type($imagem['tmp_name']);
        
        if (!in_array($tipo_mime, $extensoes_permitidas)) { $erros[] = "Apenas arquivos de imagem (JPEG, PNG, GIF) são permitidos para substituição."; }
        else {
            $novo_nome_arquivo = uniqid() . "_" . $nome_original;
            $caminho_completo = $diretorio_upload . $novo_nome_arquivo;

            if (move_uploaded_file($imagem['tmp_name'], $caminho_completo)) {
                if ($imagem_antiga && file_exists($imagem_antiga)) { unlink($imagem_antiga); }
            } else { $erros[] = "Erro ao fazer o upload da nova imagem."; }
        }
    }

    if (empty($erros)) {
        $sql = "UPDATE usuarios SET nome = ?, email = ?, cpf = ?, caminho_imagem = ? WHERE id = ?";
        
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nome, $email, $cpf_formatado, $caminho_completo, $id]);
            $mensagem = "Usuário **$nome** atualizado com sucesso!";
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000) { $mensagem = "Erro: Já existe outro registro com este CPF ou E-mail."; }
            else { $mensagem = "Erro inesperado ao atualizar: " . $e->getMessage(); }
        }
    } else { $mensagem = "Ocorreram erros na edição: <ul><li>" . implode("</li><li>", $erros) . "</li></ul>"; }
} else { $mensagem = "Requisição inválida."; }

header("Location: cadastro.php?mensagem=" . urlencode($mensagem));
exit();