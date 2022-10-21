<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$nome = $endereco = $email = $senha = "";
$nome_err = $endereco_err = $email_err = "";
$senha_err = $rep_senha = $rep_senha_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    
    // Validate nome
    $input_nome = trim(isset($_POST["nome"])?$_POST["nome"]:'');
    $senha = isset($_POST["senha"])?$_POST["senha"]:'';
    $rep_senha = isset($_POST["rep_senha"])?$_POST["rep_senha"]:'';

    if(empty($input_nome)){
        $nome_err = "Por favor, insira o nome";
    } else{
        $nome = $input_nome;
    }
    
    // Validate endereco endereco
    $input_endereco = trim(isset($_POST["endereco"])?$_POST["endereco"]:'');
    if(empty($input_endereco)){
        $endereco_err = "Por favor, insira o endereço";     
    } else{
        $endereco = $input_endereco;
    }
    
    if(empty($senha)){
        $senha_err = "Insira uma senha!";
    }
    if($senha !== $rep_senha){
        $rep_senha_err = "As senhas não conferem!";
    }

    // Validate email
    $input_email = trim(isset($_POST["email"])?$_POST["email"]:'');
    if(empty($input_email)){
        $email_err = "O campo E-mail é obrigatório";     
    } elseif(!filter_var($input_email, FILTER_VALIDATE_EMAIL)){
        $email_err = "E-mail inválido";
    } else{
        $email = $input_email;
    }
    
    if(empty($nome_err) && empty($endereco_err) && empty($email_err) && empty($senha_err) && empty($rep_senha_err)){
        
        $md5_senha = md5($senha);
        
        $sql = "UPDATE usuarios SET nome_completo='$input_nome', endereco='$input_endereco', email='$input_email', senha='$md5_senha'  WHERE id=$id";
         
        if($stmt = mysqli_prepare($link, $sql)){

            $param_nome = $nome;
            $param_endereco = $endereco;
            $param_email = $email;
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("location: list.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM usuarios WHERE id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            // Set parameters
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $nome = $row["nome_completo"];
                    $endereco = $row["endereco"];
                    $email = $row["email"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
        
        // Close connection
        mysqli_close($link);
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>
 
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
                    <h2 class="mt-5">Editar dado do usuário <?php echo isset($row['nome_completo'])?$row['nome_completo']:''; ?> </h2>
                    <p>Aqui você atualiza os dados dos usuários</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
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
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="list.php" class="btn btn-secondary ml-2">Cancelar</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>