<?php
require 'conexao.php'; 

$mensagem = "";
$erros = [];

function validarEmail($email) { return filter_var($email, FILTER_VALIDATE_EMAIL); }
function validarCPF($cpf) {
    $cpf = preg_replace('/[^0-9]/', '', $cpf);

    if (strlen($cpf) != 11) return false;
    if (preg_match('/(\d)\1{10}/', $cpf)) return false;

    for ($t = 9; $t < 11; $t++) {
        for ($d = 0, $c = 0; $c < $t; $c++) { $d += $cpf[$c] * (($t + 1) - $c); }

        $d = ((10 * $d) % 11) % 10;

        if ($cpf[$c] != $d) { return false; }
    }

    return true;
}

if (isset($_POST['acao']) && $_POST['acao'] == 'deletar') {
    $id = $_POST['id'] ?? null;
    
    if ($id) {
        try {
            $stmt_select = $pdo->prepare("SELECT caminho_imagem FROM usuarios WHERE id = ?");
            $stmt_select->execute([$id]);
            $caminho_imagem = $stmt_select->fetchColumn();

            $stmt_delete = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
            if ($stmt_delete->execute([$id])) {
                if ($caminho_imagem && file_exists($caminho_imagem)) { unlink($caminho_imagem); }

                $mensagem = "Usuário excluído com sucesso!";
            } else {  $mensagem = "Erro ao excluir o usuário."; }
        } catch (\PDOException $e) { $mensagem = "Erro: Não foi possível excluir. " . $e->getMessage(); }
    } else { $mensagem = "ID de usuário não fornecido para exclusão."; }
    
    header("Location: cadastro.php?mensagem=" . urlencode($mensagem));
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $cpf_formatado = trim($_POST['cpf']);
    $cpf_apenas_numeros = preg_replace('/[^0-9]/', '', $cpf_formatado);

    if (empty($nome)) $erros[] = "O campo Nome é obrigatório.";
    if (!validarEmail($email)) $erros[] = "O E-mail inserido é inválido.";
    if (!validarCPF($cpf_apenas_numeros)) $erros[] = "O CPF inserido é inválido.";

    $caminho_completo = '';
    
    if (empty($_FILES['imagem']['name'])) { $erros[] = "O upload da imagem é obrigatório."; } 
    else {
        $imagem = $_FILES['imagem'];
        $diretorio_upload = "uploads/";
        $nome_original = basename($imagem['name']);
        
        $extensoes_permitidas = ['image/jpeg', 'image/png', 'image/gif'];
        $tipo_mime = mime_content_type($imagem['tmp_name']);
        
        if (!in_array($tipo_mime, $extensoes_permitidas)) { $erros[] = "Apenas arquivos de imagem (JPEG, PNG, GIF) são permitidos."; }
        else {
            $novo_nome_arquivo = uniqid() . "_" . $nome_original;
            $caminho_completo = $diretorio_upload . $novo_nome_arquivo;
        }
    }

    if (empty($erros)) {
        if (move_uploaded_file($imagem['tmp_name'], $caminho_completo)) {
            $sql = "INSERT INTO usuarios (nome, email, cpf, caminho_imagem) VALUES (?, ?, ?, ?)";
            
            try {
                $stmt = $pdo -> prepare($sql);
                $stmt -> execute([$nome, $email, $cpf_formatado, $caminho_completo]);
                $mensagem = "Usuário **$nome** cadastrado com sucesso e imagem salva!";
                
            } catch (\PDOException $e) {
                if ($e -> getCode() == 23000) { $mensagem = "Erro: Já existe um registro com este CPF ou E-mail."; }
                else { $mensagem = "Erro inesperado ao cadastrar: " . $e -> getMessage(); }
            }
        } else { $mensagem = "Erro ao fazer o upload da imagem."; }
    } else { $mensagem = "Ocorreram erros no cadastro: <ul><li>" . implode("</li><li>", $erros) . "</li></ul>"; }
}

include 'cadastro.php'; 