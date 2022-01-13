user = JSON.parse(document.querySelector('#userData').value)

const menu = document.querySelector('#manage_file')
const video = document.querySelector("video");


document.querySelector('#startrec').addEventListener('click' , function(e){ 

  navigator.mediaDevices
    .getUserMedia({ //retourne une promesse
      video: true,  // on demande l'autorisation pour récupérer la video de la cam
      audio: true // on demande l'autorisation poour récupérer l'audio de la cam
    })
    .then((stream) => {
      video.srcObject = stream;
      // objet de la librairie recordRTC
      let recorder = RecordRTC(stream, {
          type: 'video',
          mimeType: 'video/webm\;codecs=vp9',
          recorderType: MediaStreamRecorder,
          bitsPerSecond: 512000,
          frameRate: 60
      });
      recorder.startRecording();

  document.querySelector('#stoprec').addEventListener('click' , function(e){
          recorder.stopRecording(function() {
            // on arrete le stream 
              const tracks = video.srcObject.getTracks();
            
              tracks.forEach(function(track) {
                track.stop();
              });
              
              video.srcObject = null;

          menu.classList.remove("d-none")
          let blob = recorder.getBlob();
            
          document.querySelector('#watch').addEventListener('click', function(e){
            console.log (blob)
            const newObjectUrl = URL.createObjectURL( blob );
            video.setAttribute ('controls', 'controls')
            video.src=newObjectUrl           
             });
         
          document.querySelector('#save-to-disk').addEventListener('click', function(e){
              invokeSaveAsDialog(blob);
           
             });

          document.querySelector('#upload-to-server').addEventListener('click', function(e){
              let formData = new FormData();
              let fileName = (Math.random() * 1000).toString().replace('.', '');
              formData.append('video', blob);
              formData.append('video-filename', fileName);
              $.ajax({
                xhr : function () {
                  const xhr = new window.XMLHttpRequest();
                  xhr.upload.addEventListener("progress" , function (evt){
                    if (evt.lengthComputable) {
                      const progression = ((evt.loaded /evt.total) *100);
                      document.querySelector ('.progress-bar').style.width= progression+'%';
                      document.querySelector ('.progress-bar').innerHTML= (progression+'%');
                    }
                  }, false)
                  // le 3 eme paramètre  de add event lsitener est le use Capture 
                  return xhr
                },
                 
                  url: 'preparelive.php', 
                  data: formData,
                  cache: false,
                  contentType: false,
                  processData: false,
                  type: 'post',
                  beforeSend: function (){
                    document.querySelector ('.progress-bar').style.width= 0+'%';
                    // document.querySelector ('#uploadStatus').innerHTML='<img src = "../img/loading.gif"/>'
                  },
                  success: function(reponse) {
                      if (reponse="ok"){
                        document.querySelector('#uploadStatus').innerHTML ='<p style =" color : #28A74B">enregistrement terminé</p>'
                        window.location.href="preparelive.php";
                      }

                  },
                  error: function(){
                    document.querySelector('#uploadStatus').innerHTML ='<p style =" color : #ba4343">une erreur est intervenue</p>'
                  }
              });
          });          
              
          });
       }) 
 
  });

})


