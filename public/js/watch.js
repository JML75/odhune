user = JSON.parse(document.querySelector('#user2js').value)
let peerConnection;

//jeu de couleur pour le chat
const colors = [
  '#e21400', '#91580f', '#f8a700', '#ff7f50',
  '#58dc00', '#287b00', '#669900', '#4ae8c4',
  '#3b88eb', '#3824aa', '#a700ff', '#d300e7'
];
const $window = $(window);
//attibution d'une couleur aléatoire
user.color = colors [Math.trunc(Math.random() * (colors.length))]
//récupération de l'élément input 
const $inputMessage = document.querySelector('.inputMessage');

 // nettoyage d'un input 
 const cleanInput = (input) => {
  input.value="";
}


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

    // on se connecte à la socket du coté client
    const socket = io("https://inspiring-nash.185-13-64-120.plesk.page",{
        withCredentials: true,
        transports: ['websocket', 'polling'],
                    extraHeaders: {
                    "mydev-header": "abcd"
                    }
});

    const video = document.querySelector("video");
    

    socket.on("offer", (id, description) => {
      peerConnection = new RTCPeerConnection(config);
      peerConnection
        .setRemoteDescription(description)
        .then(() => peerConnection.createAnswer())
        .then(sdp => peerConnection.setLocalDescription(sdp))
        .then(() => {
          socket.emit("answer", id, peerConnection.localDescription);
        });
      peerConnection.ontrack = event => {
        video.srcObject = event.streams[0];
      };
      peerConnection.onicecandidate = event => {
        if (event.candidate) {
          socket.emit("candidate", id, event.candidate);
        }
      };
    });

    socket.on("candidate", (id, candidate) => {
      peerConnection
        .addIceCandidate(new RTCIceCandidate(candidate))
        .catch(e => console.error(e));
    });
    
    socket.on("connect", () => {
      socket.emit("watcher");
      const text = " est connecté(e)"
      let message = {
        user: user,
        text : text,
      }
      socket.emit ('message', message)
     


    });
    
    socket.on("broadcaster", () => {
      socket.emit("watcher");
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
      elt_text.style.color= message.user.color
      elt_user.style.color= message.user.color
      elt_text.style.backgroundColor= message.user.color+'33'
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
      peerConnection.close();
    };



