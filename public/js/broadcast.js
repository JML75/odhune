user = JSON.parse(document.querySelector('#user2js').value)
const colors = ['#888'];
const $window = $(window);
//attibution d'une couleur aléatoire
user.color = colors [Math.trunc(Math.random() * (colors.length))]
//récupération de l'élément input 
const $inputMessage = document.querySelector('.inputMessage');

 // nettoyage d'un input 
 const cleanInput = (input) => {
  input.value="";
}

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

      socket.on ("message", message => {
        const elt_contain =document.createElement('div')
        const elt_text =document.createElement('div')
        const elt_user =document.createElement('div')
  
        elt_contain.setAttribute ('class', 'gauche')
        elt_text.setAttribute ('class', 'text')
        elt_user.setAttribute ('class', 'nom')
        if (message.user.user_id  !== user.user_id){
          elt_contain.removeAttribute ('class', 'gauche')
          elt_contain.setAttribute ('class', 'droite')
        }
        elt_text.style.color= '#000'
        elt_user.style.color= message.user.color
        //elt_text.style.backgroundColor= message.user.color+'33'
        elt_text.innerHTML = message.text
        elt_user.innerHTML = message.user.prenom
        elt_contain.prepend(elt_text)
        elt_contain.prepend(elt_user)
        document.querySelector('.messages').prepend (elt_contain)
        
    });
    
     
  // si on click sur envoi
    document.querySelector ('#envoi').onclick = ()=>{     
      let input = document.querySelector('.inputMessage')
      let text= input.value
      if (text !==""){
        let message = {
          text : text,
          user: user
       }
         cleanInput(input)
          socket.emit ('message', message)
      }
   };
   // Keyboard events 

 $window.keydown(event => {
  // Auto-focus the current input when a key is typed
  if (!(event.ctrlKey || event.metaKey || event.altKey)) {
    $inputMessage.focus();
  }
  // When the client hits ENTER on their keyboard
  if (event.which === 13) {
    let input = document.querySelector('.inputMessage')
    let text= input.value
    if (text !==""){
      let message = {
        text : text,
        user: user
      }
       cleanInput(input)
        socket.emit ('message', message)
      }
    }
});

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
