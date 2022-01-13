<?php
require_once('./templates/header.php');
require_once('../src/controllers/UserController.php'); 
?>


<div class="col-md-12 my-3 d-flex flex-row justify-content-between" >
    <video class ="col-md-5 mx-auto" playsinline autoplay muted></video>
    <div  class="col-md-5 mx-auto" style =" border: solid 1px black;">
        <div class="input-group" placeholder="message">
       <input id ="input_message" class="form-control" placeholder="message">
        <button id ="envoi" class = "btn btn-primary">envoi</button>
        </div>
        <ul class="chat_ul col-md-5- flew-column ml-5 mt-2 mx-2" >
        </ul>
    </div>
</div>
<textarea  hidden name="" id="user2js" cols="30" rows="10"></textarea>
<script>
   let user = <?php echo json_encode ($_SESSION['user']);?>;
   let userstr =  JSON.stringify(user);
    document.querySelector('#user2js').value = userstr
</script> 
</script> 
<script src="https://code.jquery.com/jquery-3.4.1.js"></script>
<script src="https://inspiring-nash.185-13-64-120.plesk.page/node_modules/socket.io/client-dist/socket.io.js"></script> 
<script defer src="./assets/js/watch.js"></script>


<?php
    require_once('./templates/footer.php'); 

?>