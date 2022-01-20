user = JSON.parse(document.querySelector('#user2js').value)

const peerConnections = {};
const config = {
  iceServers: [
    { 
      "urls": "stun:stun.l.google.com:19302",
    },
    // { 
    //   "urls": "turn:TURN_IP?transport=tcp",
    //   "username": "TURN_USERNAME",
    //   "credential": "TURN_CREDENTIALS"
    // }
  ]
};

const socket = io("https://inspiring-nash.185-13-64-120.plesk.page",{
        withCredentials: true,
        transports: ['websocket', 'polling'],
                    extraHeaders: {
                    "mydev-header": "abcd"
                    }
});


let menu = document.querySelector('#manage_file')
const video = document.querySelector("video");
const constraints = {
  video: { facingMode: "user" },
  audio: true
};


document.querySelector('#startrec').addEventListener('click' , function(e){ 

  navigator.mediaDevices
    .getUserMedia({ //retourne une promesse
      video: true,  // on demande l'autorisation pour récupérer la video de la cam
      audio: true // on demande l'autorisation poour récupérer l'audio de la cam
    })
    .then((stream) => {
      video.srcObject = stream;
      socket.emit("broadcaster");

      let recorder = RecordRTC(stream, {
          type: 'video',
          mimeType: 'video/webm\;codecs=vp9',
          recorderType: MediaStreamRecorder,
          bitsPerSecond: 512000,
          frameRate: 60
      });


      socket.on("watcher", id => {
        const peerConnection = new RTCPeerConnection(config);
        peerConnections[id] = peerConnection;
        console.log (peerConnection)
      
        let stream = video.srcObject;
        stream.getTracks().forEach(track => peerConnection.addTrack(track, stream));
          
        peerConnection.onicecandidate = event => {
          if (event.candidate) {
            socket.emit("candidate", id, event.candidate);
          }
        };
        peerConnection
          .createOffer()
          .then(sdp => peerConnection.setLocalDescription(sdp))
          .then(() => {
            socket.emit("offer", id, peerConnection.localDescription);
          });
      });
      
      socket.on("answer", (id, description) => {
        peerConnections[id].setRemoteDescription(description);
      });
      
      socket.on("candidate", (id, candidate) => {
        peerConnections[id].addIceCandidate(new RTCIceCandidate(candidate));
      });

      socket.on("disconnectPeer", id => {
        peerConnections[id].close();
        delete peerConnections[id];
      });

      socket.on ("message", text => {
        const el =document.createElement('li')
        el.innerHTML = text
        document.querySelector('.chat_ul').prepend(el)
    });
    
      document.querySelector ('#envoi').onclick = ()=>{
        const text = user.prenom + "  ->  " + document.querySelector('#input_message').value
        console.log(document.querySelector('#input_message').value)
        socket.emit ('message', text)
    };

      window.onunload = window.onbeforeunload = () => {
        socket.close();
      };

      recorder.startRecording();

     

      document.querySelector('#stoprec').addEventListener('click' , function(e){
          recorder.stopRecording(function() {
            menu.classList.remove("d-none")
              let blob = recorder.getBlob();
              
         
          document.querySelector('#save-to-disk').addEventListener('click', function(e){
              invokeSaveAsDialog(blob);
           
          });

          document.querySelector('#upload-to-server').addEventListener('click', function(e){

              formData = new FormData();
              let fileName = (Math.random() * 1000).toString().replace('.', '');
              formData.append('video-blob', blob);
              formData.append('video-filename', fileName);
              $.ajax({
                xhr : function () {
                  const xhr = new window.XMLHttpRequest();
                  xhr.upload.addEventListener("progress" , function (evt){
                    if (evt.lengthComputable) {
                      const progression = ((evt.loaded /evt.total) *100);
                      document.querySelector ('.progress-bar').style.width= progression+'%';
                      document.querySelector ('.progress-bar').innerHTML= (progression+'%');
                      console.log (progression)
                    }
                  }, false)
                  // le 3 eme paramètre  de addEventListener est le use Capture 
                  // false veut dire quu'on attend la fin de l'événement
                  console.log (xhr)
                  return xhr

                },
                 
                  url: 'broadcastlive.php', 
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
                        window.location.href="broadcastlive.php";
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
