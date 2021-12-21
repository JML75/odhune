<?php
  // gestion du retour de la DropZone photo
  $upload_dir = '%kernel.project_dir%/public/img/uploadDropzone/';
  $remove = false;
  if(isset($_POST['remove'])){
      $remove = $_POST['remove'];
  }
  if ($remove == false) {
           
    if (isset($_FILES['file'])) {
    $photoFile = $_FILES['file'];
    

    for ($i=0 ; $i<count($photoFile['name']); $i++){                                                                                                                                  
        var_dump($photoFile['name'][$i]);
       var_dump($upload_dir.$photoFile['name'][$i]);
       var_dump($photoFile["tmp_name"][$i]);
        move_uploaded_file( $_FILES['file']["tmp_name"][$i],  $upload_dir.$photoFile['name'][$i]);    
        }

    }
}
 // gestion de la suppression d'une photo dans la dropzone 
 if (isset($_POST['remove']) && $_POST['remove'] == true){

}
if ($remove == true) {
    $filename = $upload_dir.$_POST['filename'];
    unlink($filename); exit;
}




?>