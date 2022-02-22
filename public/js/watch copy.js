user = JSON.parse(document.querySelector('#user2js').value)
let peerConnection;

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
console.log (socket);

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
    });
    
    socket.on("broadcaster", () => {
      socket.emit("watcher");
    });

    socket.on ("message", text => {
      const el =document.createElement('li')
      el.innerHTML = text
      document.querySelector('.chat_ul').appendChild(el)
  });
  
    document.querySelector ('#envoi').onclick = ()=>{
      const text = user.prenom + " a dit : " + document.querySelector('#input_message').value
      socket.emit ('message', text)
  };
    
    window.onunload = window.onbeforeunload = () => {
      socket.close();
      peerConnection.close();
    };



