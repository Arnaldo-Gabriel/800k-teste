<?php
//Inclui arquivo de configuração
require_once "config.php";
 
// Define variáveis ​​e inicializa com valores vazios
$nome = $endereco = $email = $senha = "";
$nome_err = $endereco_err = $email_err = $senha_err = $rep_senha = $rep_senha_err = "";
 
// Processando dados do formulário quando o formulário é enviado
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validar nome
    $input_nome = trim(isset($_POST["nome"])?$_POST["nome"]:'');
    $senha = isset($_POST["senha"])?$_POST["senha"]:'';
    $rep_senha = isset($_POST["rep_senha"])?$_POST["rep_senha"]:'';

    if(empty($input_nome)){
        $nome_err = "Por favor inserir um nome.";
    } else{
        $nome = $input_nome;
    }

    
    // Validar endereco
    $input_endereco = trim(isset($_POST["endereco"])?$_POST["endereco"]:'');
    
    if(empty($input_endereco)){
        $endereco_err = "Por favor digite um Endereço.";     
    } else{
        $endereco = $input_endereco;
    }
    
    // Validar email
    $input_email = trim(isset($_POST["email"])?$_POST["email"]:'');
    
    if(empty($input_email)){
        $email_err = "Insira um e-mail.";     
    } elseif(!filter_var($input_email, FILTER_VALIDATE_EMAIL)){
        $email_err = "Insira um E-mail válido.";
    } else{
        $email = $input_email;
    }

    if(empty($senha)){
        $senha_err = "Insira uma senha!";
    }
    if($senha !== $rep_senha){
        $rep_senha_err = "As senhas não conferem!";
    }
    
        // Verifica os erros de entrada antes de inserir no banco de dados
    if(empty($nome_err) && empty($endereco_err) && empty($email_err) && empty($senha_err) && empty($rep_senha_err)){
        // Prepara uma instrução de inserção
        $md5_senha = md5($senha);
        $sql = "INSERT INTO usuarios(nome_completo, endereco, email, senha) VALUES ( '$nome','$endereco','$email', '$md5_senha' )";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Define os parâmetros
            $param_nome = $nome;
            $param_endereco = $endereco;
            $param_email = $email;
            
            // Tenta executar a instrução preparada
            if(mysqli_stmt_execute($stmt)){
            // Registros criados com sucesso. Redirecionar para a página de destino
                header("location: list.php");
                exit();
            } else{
                echo "Ops! Algo deu errado. Por favor, tente novamente mais tarde.";
            }
        }
         
       //Fecha a declaração
        mysqli_stmt_close($stmt);
    }
    
    //Fecha a conexão
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Criar regirstro</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Criar registro</h2>
                    <p>Preencha este formulário e envie para adicionar o registro do funcionário ao banco de dados.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Nome</label>
                            <input type="text" name="nome" class="form-control <?php echo (!empty($nome_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $nome; ?>">
                            <span class="invalid-feedback"><?php echo $nome_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Endereço</label>
                            <textarea name="endereco" class="form-control <?php echo (!empty($endereco_err)) ? 'is-invalid' : ''; ?>"><?php echo $endereco; ?></textarea>
                            <span class="invalid-feedback"><?php echo $endereco_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>E-mail</label>
                            <input type="text" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                            <span class="invalid-feedback"><?php echo $email_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Senha</label>
                            <input type="password" name="senha" class="form-control <?php echo (!empty($senha_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $senha; ?>">
                            <span class="invalid-feedback"><?php echo $senha_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Rep. Senha</label>
                            <input type="password" name="rep_senha" class="form-control <?php echo (!empty($rep_senha_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $rep_senha; ?>">
                            <span class="invalid-feedback"><?php echo $rep_senha_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Cadastrar">
                        <a href="list.php" class="btn btn-secondary ml-2">Cancelar</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>