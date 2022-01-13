
<?php
session_start();


if (!empty($_SESSION['user'])){
$firstname = $_SESSION['user']['firstname'];
$name = $_SESSION['user']['name'];
$t = time();
if( $t-  $_SESSION ['time'] > 600){
    echo '<script> window.location.href="deconnexion.php"</script>';
} 
else {}
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="icon" href="./assets/img/favicon.ico" />
    <title>TVShop</title>
</head>

<body>
<header>

        
    <nav class="navbar navbar-expand-md navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">TVShop</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    
                        <li class="nav-item">
                            <a class="nav-link" href="accueil.php">Accueil</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Live</a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li class="nav-item"><a class="nav-link" href="live.php">live à venir</a></li>
                                <li class="nav-item"><a class="nav-link" href="preparelive.php">preparer live</a></li>
                                <li class="nav-item"><a class="nav-link" href="watchlive.php">watch live en cours</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li class="nav-item"><a class="nav-link" href="broadcastlive.php">broadcast</a></li>
                            </ul>
                        </li>
                        
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Replay</a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li class="nav-item"><a class="nav-link" href="replay.php">les replay</a></li>
                                <li class="nav-item"><a class="nav-link" href="upload.php">replay upload</a></li>
                            </ul>
                        </li>


                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Admin</a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li class="nav-item"><a class="nav-link" href="listLive.php">liste live</a></li>
                                <li class="nav-item"><a class="nav-link" href="listReplay.php">liste replay</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="admin_membre.php">membres</a></li>
                            </ul>
                        
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="far fa-user"></i>  
                                <?php  echo ('  '.$firstname.' '.$name);?> 
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li class="nav-item"><a class="nav-link" href="inscription.php">Inscription</a></li>
                                <li class="nav-item"><a class="nav-link" href="login.php">Connexion</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li class="nav-item"><a class="nav-link" href="deconnexion.php">Deconnexion</a></li>
                            </ul>
                        </li>
    
                    </ul>

                </div>
            </div>
        </nav>
        
</header>