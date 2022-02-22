user = JSON.parse(document.querySelector('#user2js').value)

// initialisation du WEBRTC
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

// initialisation du  serveur websocket 
const socket = io("https://inspiring-nash.185-13-64-120.plesk.page",{
        withCredentials: true,
        transports: ['websocket', 'polling'],
                    extraHeaders: {
                    "mydev-header": "abcd"
                    }
});

// init video 
let menu = document.querySelector('#manage_file')
const video = document.querySelector("video");
const constraints = {
  video: { facingMode: "user" },
  audio: true
};


//init chat
$(function () {
var FADE_TIME = 150; // ms
var TYPING_TIMER_LENGTH = 1000; // ms
var COLORS = [
  '#e21400', '#91580f', '#f8a700', '#f78b00',
  '#58dc00', '#287b00', '#a8f07a', '#4ae8c4',
  '#3b88eb', '#3824aa', '#a700ff', '#d300e7'
];

    var $window = $(window);
  
    var $messages = $('.messages'); // Messages area
    var $inputMessage = $('.inputMessage'); // Input message input box

    var username = user.prenom
    var connected = false;
    var typing = false;
    var lastTypingTime;
    var $currentInput = $inputMessage.focus()

    // --- chat function


    //---------------------------------------------
    // Sets the client's username
    function setUsername () {
     
      username = user.prenom
      if (username) {
        console.log ('username is valid')
        $currentInput = $inputMessage.focus();

        // Tell the server your username
        socket.emit('user joined', username);
        console.log ( 'je suis à user joined')
        console.log (socket)
      }
    }
//---------------------------------------------
    // Sends a chat message
    function sendMessage () {
      var message = $inputMessage.val();
      // Prevent markup from being injected into the message
      message = cleanInput(message);
      // if there is a non-empty message and a socket connection
      if (message && connected) {
        $inputMessage.val('');
        /*addChatMessage({
          username: username,
          message: message
        });*/
        // tell server to execute 'new message' and send along one parameter
        socket.emit('new message', message);
      }
    }
    //---------------------------------------------
    // Log a message
    function log (message, options) {
      var $el = $('<li>').addClass('log').text(message);
        console.log(message)
      addMessageElement($el, options);
    }
    //---------------------------------------------

    // Adds the visual chat message to the message list
    function addChatMessage (data, options) {
      // Don't fade the message in if there is an 'X was typing'
      var $typingMessages = getTypingMessages(data);
      options = options || {};
      if ($typingMessages.length !== 0) {
        options.fade = false;
        $typingMessages.remove();
      }

      var $usernameDiv = $('<span class="username"/>')
        .text(data.username)
        .css('color', getUsernameColor(data.username));
      var $messageBodyDiv = $('<span class="messageBody">')
        .text(data.message);

      var typingClass = data.typing ? 'typing' : '';
      var $messageDiv = $('<li class="message"/>')
        .data('username', data.username)
        .addClass(typingClass)
        .append($usernameDiv, $messageBodyDiv);

      addMessageElement($messageDiv, options);
    }
    //---------------------------------------------

    // Adds the visual chat typing message
    function addChatTyping (data) {
      data.typing = true;
      data.message = 'is typing';
      addChatMessage(data);
    }
    //---------------------------------------------
    // Removes the visual chat typing message
    function removeChatTyping (data) {
      getTypingMessages(data).fadeOut(function () {
        $(this).remove();
      });
    }

    // Adds a message element to the messages and scrolls to the bottom
    // el - The element to add as a message
    // options.fade - If the element should fade-in (default = true)
    // options.prepend - If the element should prepend
    //   all other messages (default = false)
    function addMessageElement (el, options) {
      var $el = $(el);

      // Setup default options
      if (!options) {
        options = {};
      }
      if (typeof options.fade === 'undefined') {
        options.fade = true;
      }
      if (typeof options.prepend === 'undefined') {
        options.prepend = false;
      }

      // Apply options
      if (options.fade) {
        $el.hide().fadeIn(FADE_TIME);
      }
      if (options.prepend) {
        $messages.prepend($el);
      } else {
        $messages.append($el);
      }
      $messages[0].scrollTop = $messages[0].scrollHeight;
    }
//---------------------------------------------
    // Prevents input from having injected markup
    function cleanInput (input) {
      return $('<div/>').text(input).text();
    }
//---------------------------------------------
    // Updates the typing event
    function updateTyping () {
      if (connected) {
        if (!typing) {
          typing = true;
          socket.emit('typing');
        }
        lastTypingTime = (new Date()).getTime();
 

        setTimeout(function () {
          var typingTimer = (new Date()).getTime();
          var timeDiff = typingTimer - lastTypingTime;
          if (timeDiff >= TYPING_TIMER_LENGTH && typing) {
            socket.emit('stop typing');
            typing = false;
          }
        }, TYPING_TIMER_LENGTH);
      }
    }
    //---------------------------------------------

    // Gets the 'X is typing' messages of a user
    function getTypingMessages (data) {
      return $('.typing.message').filter(function (i) {
        return $(this).data('username') === data.username;
      });
    }
//---------------------------------------------
    // Gets the color of a username through our hash function
    function getUsernameColor (username) {
      // Compute hash code
      var hash = 7;
      for (var i = 0; i < username.length; i++) {
         hash = username.charCodeAt(i) + (hash << 5) - hash;
      }
      // Calculate color
      var index = Math.abs(hash % COLORS.length);
      return COLORS[index];
    }

    // Keyboard events

    $window.keydown(function (event) {
      // Auto-focus the current input when a key is typed
      if (!(event.ctrlKey || event.metaKey || event.altKey)) {
        $currentInput.focus();
      }
      // When the client hits ENTER on their keyboard
      if (event.which === 13) {
      
        if (username) {
          console.log(username)
          sendMessage();
          socket.emit('stop typing');
          typing = false;
        } else {
          setUsername();
        }
      }
    });

    $inputMessage.on('input', function() {
      updateTyping();
    });

    // Click events


    // Focus input when clicking on the message input's border
    $inputMessage.click(function () {
      $inputMessage.focus();
    });





//  startBroadcast 
document.querySelector('#startrec').addEventListener('click' , function(e){ 

   connected=true

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

    // ----------chat 
      socket.on ("message", text => {
        const el =document.createElement('li')
        el.innerHTML = text
        document.querySelector('.messages').prepend(el)
    });
    
      document.querySelector ('#envoi').onclick = ()=>{
        const text = user.prenom + "  ->  " + document.querySelector('.inputMessage').value
        console.log(document.querySelector('.inputMessage').value)
        socket.emit ('message', text)
    };
    // Socket events

  // Whenever the server emits 'login', log the login message
  socket.on('login', function (data) {
    connected = true;
    // Display the welcome message
    var message = "bienvenu";
    log(message, {
      prepend: true
    });
    addParticipantsMessage(data);
  });

  // Whenever the server emits 'new message', update the chat body
  socket.on('new message', function (data) {
    addChatMessage(data);
  });

  // Whenever the server emits 'user joined', log it in the chat body
  socket.on('user joined', function (data) {
    log(data.username + ' joined');
    addParticipantsMessage(data);
  });

  // Whenever the server emits 'user left', log it in the chat body
  socket.on('user left', function (data) {
    log(data.username + ' left');
    addParticipantsMessage(data);
    removeChatTyping(data);
  });

  // Whenever the server emits 'typing', show the typing message
  socket.on('typing', function (data) {
    addChatTyping(data);
  });

  // Whenever the server emits 'stop typing', kill the typing message
  socket.on('stop typing', function (data) {
    removeChatTyping(data);
  });

    // ----------fin du chat 



      window.onunload = window.onbeforeunload = () => {
        socket.close();
      };

      recorder.startRecording();

      // stop broadcast 
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

});
