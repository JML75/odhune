<?php

require_once('./templates/header.php');
require_once('../src/controllers/VideoController.php');
if (empty($_SESSION['user'])){
    header('Location: login.php');
} ;

$titre = $_POST['titre'];
$sstitre= $_POST['sstitre'];
$description = $_POST['description'];
$button = "Valider";



if (!empty($_POST['titre']) && !empty($_POST['sstitre']) && !empty($_POST['description']) ){
    // sauvegarde du $_POST dans la SESSION car il est effacé par la requête ajax
    $_SESSION ['video']['titre'] = $_POST['titre'];
    $_SESSION ['video']['sstitre']= $_POST['sstitre'];
    $_SESSION ['video']['description'] = $_POST['description'];
    $button = "Mofifier";
};


if (!empty($_FILES) && !empty($_SESSION["user"])) {

    $titre = $_SESSION ['video']['titre'];
    $sstitre = $_SESSION ['video']['sstitre'];
    $description = $_SESSION ['video']['description'];

    $nom_video  =  "user_id- ". $_SESSION ["user"]['user_id']."-".date("YmdHis") . "-" . uniqid().'.webm';

    move_uploaded_file($_FILES["video-blob"]["tmp_name"], "./assets/video/replay/" . $nom_video);

    if (!empty($titre) && !empty($sstitre) && !empty( $description))
    {
        $videoController = new VideoController($titre, $sstitre, $description, $_SESSION ["user"]['user_id'],$nom_video);
        $videoController->setVideo();
  
    } else {
        $videoController = new VideoController( 'live de '.$_SESSION ["user"]['firstname'], '', '', $_SESSION ["user"]['user_id'],$nom_video);
        $videoController->setVideo();
    }
};


?>


<div class="col-md-12 my-5 d-flex flex-row justify-content-around" >
    

    <div class="form-group col-md-3 mt-3 mx-auto"  >

    <form action="broadcastlive.php" method='post' enctype="multipart/form-data">
            <label  for="input_titre">Titre</label>
            <?php echo'<input id="input_titre" type="text" class ="form-control my-1" name="titre" placeholder="titre de votre live" value ="'.$titre.'">'; ?>
            <label for="sstitre">Sous-titre</label>
            <?php echo'<input id="input_sstitre" type="text" class ="form-control my-1" name="sstitre" placeholder="sous-titre de votre live" value ="'.$sstitre.'">';?>
            <label for="description">Description</label>
            <?php echo'<textarea id="input_description" type="textarea" class ="form-control my-1" name="description" placeholder="decrivez votre live (max 255 caractères)">'.$description.'</textarea>'; ?>
            <?php echo '<button  id="valider" type= "submit" class="btn btn-primary col-md-12 my-2">'.$button.'</button> ' ?>
           
        </form> 
           
        
       
    </div>
    <div  class="col-md-3 mx-auto" style =" border: solid 1px black;">
        <div class="input-group" placeholder="message">
        <input id ="input_message" class="form-control" placeholder="message">
        <button id ="envoi" class = "btn btn-primary">envoi</button>
        </div>
        <ul class="chat_ul col-md-5 flew-column mt-2 mx-2" >
        </ul>
    </div>
    <div class = "col-md-4 mx-auto">
        <video  class="col-md-12" playsinline autoplay muted></video>
        <div class="col-md-12 d-flex justify-content-between">
            <button id ="startrec" class="btn btn-primary my-2 col-md-5">start broadcast</button>
            <button id ="stoprec" class="btn btn-primary  my-2 col-md-5">stop broadcast</button>
        </div>
            <div class="progress col-md-12 d-flex">
                <div class="progress-bar"></div>
            </div>
             <div class="uploadStatus"></div>
            
        <div id ="manage_file" class ="col-md-12 d-flex justify-content-between d-none" >
            <button id="save-to-disk" class="btn btn-primary col-md-5 my-2">save to disk</button>
            <button id="upload-to-server" class="btn btn-primary col-md-5 my-2" >Upload to Server</button>      
        </div>
    </div>
    
   
</div>

<textarea  hidden name="" id="user2js" cols="30" rows="10"></textarea>
<script>
    
</script>
<script>
   let user = <?php echo json_encode ($_SESSION['user']);?>;
   let userstr =  JSON.stringify(user);
   console.log (userstr)
  document.querySelector('#user2js').value = userstr
</script> 
<script src="https://code.jquery.com/jquery-3.4.1.js"></script>
<script src="https://inspiring-nash.185-13-64-120.plesk.page/node_modules/socket.io/client-dist/socket.io.js"></script> 
<script defer src="./assets/js/broadcast.js"></script>
<script defer src="./assets/js/recortRTC.js"></script> 

<?php
    require_once('./templates/footer.php'); 

?>