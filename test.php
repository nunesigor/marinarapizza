<?php
ini_set('allow_url_fopen',1);
ini_set('allow_url_include',1);
$nome = $_POST['cname'];
$email = $_POST['cemail'];
$mensagem = $_POST['cmessage'];

$email_remetente = "info@marinarapizzatx.com";
$email_destinatario = "info@marinarapizzatx.com";

$email_reply = "$email";
$email_assunto = "Marinara Pizza email contact site";

$email_conteudo = "Nome = $nome \n";
$email_conteudo .= "Email = $email \n";
$email_conteudo .= "Mensagem = $mensagem \n";

if (isset($_POST['g-recaptcha-response'])) {
    $captcha_data = $_POST['g-recaptcha-response'];
}

if (!$captcha_data) {
    echo "Confirm the captcha, please.<br><a href='http://www.marinarapizzatx.com/index.html#contact'>Back</a>";
    exit;
}

$postdata = http_build_query(
    array(
        'secret' => '6LdlBjAUAAAAAKeOj66P7m8cjzE71VGz74381GHZ',
        'response' => $captcha_data,
        'remoteip' => $_SERVER['REMOTE_ADDR']
    )
);

$opts = array('http' =>
    array(
        'method'  => 'POST',
        'header'  => 'Content-type: application/x-www-form-urlencoded',
        'content' => $postdata,
        'ignore_errors'=>true
    )
);

$context  = stream_context_create($opts);
$resposta = @file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);

//$resposta = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LdlBjAUAAAAAKeOj66P7m8cjzE71VGz74381GHZ&response=".$captcha_data."&remoteip=".$_SERVER['REMOTE_ADDR']);

//$ch = curl_init();
//$secretKey ="6LdlBjAUAAAAAKeOj66P7m8cjzE71VGz74381GH";
//curl_setopt_array($ch, [
//    CURLOPT_URL => 'https://www.google.com/recaptcha/api/siteverify',
//    CURLOPT_POST => true,
//    CURLOPT_POSTFIELDS => [
//        'secret' => $secretKey,
//        'response' => $captcha,
//        'remoteip' => $_SERVER['REMOTE_ADDR']
//    ],
//    CURLOPT_RETURNTRANSFER => true
//]);
//$output = curl_exec($ch);
//curl_close($ch);
//$json = json_decode($output);

//Seta os Headers (Alterar somente caso necessario) 
$email_headers = implode("\n", array("From: $email_remetente",
    "Reply-To: $email_reply", "Return-Path: $email_remetente", 
    "MIME-Version: 1.0", "X-Priority: 3", "Content-Type: text/html; charset=UTF-8"));
//Enviando o email 
if ($resposta.success){
    if (mail($email_destinatario, $email_assunto, nl2br($email_conteudo), $email_headers)) {
        echo "</b>E-Mail sended. Thank's!</b><br><a href='http://marinarapizzatx.com/'>Back</a></p>";
    } else {
        echo "</b>Error to E-Mail. Sorry!</b><br><a href='http://marinarapizzatx.com/'>Back</a></p>";
    }
} else {
    echo "Robot detected. the message was blocked.";
    exit;
}
?>