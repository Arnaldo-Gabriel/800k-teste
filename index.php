

<?php
//Inclui arquivo de configuração
require_once "config.php";

// Define variáveis ​​e inicializa com valores vazios
$email = $senha = "";
$email_err = $senha_err = "";
 

if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    $senha = isset($_POST["senha"])?$_POST["senha"]:'';
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

    if(empty($email_err) && empty($senha_err)){

        $md5_senha = md5($senha);
        $sql = "SELECT * FROm usuarios WHERE email = '$email' AND senha = '$md5_senha'";
        if($stmt = mysqli_prepare($link, $sql)){
           
            if(mysqli_stmt_execute($stmt)){

                $result = mysqli_stmt_get_result($stmt);
                if(mysqli_num_rows($result)){
           
                    if (!isset($_SESSION)) session_start();
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    // $_SESSION['email'] = $row['email'];
                    header("Location: list.php");

                }
            }
        }
    }
    
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
                    <h2 class="mt-5">Login</h2>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>E-mail</label>
                            <input type="text" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>">
                            <span class="invalid-feedback"><?php echo $email_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Senha</label>
                            <input type="password" name="senha" class="form-control <?php echo (!empty($senha_err)) ? 'is-invalid' : ''; ?>" >
                            <span class="invalid-feedback"><?php echo $senha_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Login">
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>