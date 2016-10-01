<?php
require __DIR__ . '/vendor/autoload.php';
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;

define('SHARED_KEY', 'test');

if (!isset($_GET['reply_to']) ) {
    die('ERROR: reply_to parameter must be provided');
}
$title = isset($_GET['title']) ? $_GET['title'] : 'your site';
// create JWT
$signer = new Sha256();
$jwtBuilder = new Builder();
$jwtBuilder->setIssuer('https://marmix.ig.he-arc.ch/shibjwt/') // Configures the issuer (iss claim)
           ->setId(uniqid(), true) // Configures the id (jti claim), replicating as a header item
           ->setIssuedAt(time()) // Configures the time that the token was issue (iat claim)
           ->setExpiration(time() + 3600); // Configures the expiration time of the token (nbf claim)
// extract attributes from headers
$jwtBuilder->set('firstname', $_SERVER['givenName']);
$jwtBuilder->set('lastname', $_SERVER['surname']);
$jwtBuilder->set('email', $_SERVER['mail']);
$jwtBuilder->set('affiliation', $_SERVER['affiliation']);
$jwtBuilder->set('uniqueID', $_SERVER['uniqueID']);

$jwt = $jwtBuilder->sign($signer, SHARED_KEY)->getToken();

// show form which will post back jwt token to provided return_to address
?>
<!doctype html>
<html>
<head>
<title>Shibboleth - JWT</title>
<meta charset="utf-8">
<style>
html {
    font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
    background-color: #f0f0f0;
}
form {
    margin: 20px auto;
    width: 320px;
    text-align: center;
    box-shadow: 0 1px 3px 0 rgba(0,0,0,.2), 0 1px 1px 0 rgba(0,0,0,.14), 0 2px 1px -1px rgba(0,0,0,.12);

}
p {
    padding: 64px;
    background-color: white;
    margin: 0;
}
input {
    font-size: 20px;
    padding: 4px;
}
h1 {
    background-color: #4caf50;
    color: white;
    padding: 4px;
    margin: 0;
}
</style>
</head>
<body>
<form id="form" method="post" action="<?php echo $_GET['reply_to']; ?>">
<input type="hidden" name="jwt" value="<?php echo $jwt; ?>">
<input type="hidden" name="return_to" value="<?php echo $_GET['return_to']; ?>">
<h1>Authentication successful</h1>
<p><input type="submit" value="continue to <?php echo $title ?>"></p>
</form>
<script>
document.getElementById('form').submit();
</script>
</body>
</html>