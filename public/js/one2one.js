
let initiator= null

function Events (p) {
    p.on ('error',function(err){
        console.log('error',err)
    })
    //sur la connexion p on veut ecouter l'évènement signal ( qui correspond à l'envoi de l'offre WebRTC ) et lorsque c'est evènement se passe on lance une fonction qui prend le signal(l'offre)en paramètre 
    p.on('signal', function(data){
        document.querySelector('#offer').textContent = JSON.stringify(data)
        //on met dans le textarea id = "Offer" le contenu de l'offre convertie en chaîne JSON par le methode JSON.stringify()
    })
    
    p.on('stream' , function (flux) {
        if (initiator == true) {
            let receveurVideo =  document.querySelector("#receveur-video") 
            receveurVideo.volume=0
            receveurVideo.srcObject = flux
            receveurVideo.play()

        } else {
            let receveurVideo =  document.querySelector("#emetteur-video") 
            receveurVideo.volume=0
            receveurVideo.srcObject = flux
            receveurVideo.play()
        }     
    })

    // on recupère l'offre 
    document.querySelector('#incoming').addEventListener('submit' ,function(e){
        e.preventDefault()
        p.signal(JSON.parse(e.target.querySelector('#answer').value))
      
    })
}

function startPeer(initiator){
    navigator.mediaDevices.getUserMedia({ //retourne une promesse
        video: true,  // on demande l'autorisation pour récupérer la video de la cam
        audio: true // on demande l'autorisation poour récupérer l'audio de la cam
        
    }

    ).then(function(flux) { // on récupére le flux(objet MediaStream) de la cam et on le traite
       
        // on crée une nouvelle communication simplePeer une api qui crée une WebRTC communication en peer to peer
         let p = new SimplePeer ({ // on passe des options disponibles dans la doc simmplePeer
            initiator: initiator , // c'est lui qui initie la communication
            stream: flux,// on passe le lux dans la communication
            trickle:false, // permet limiter le signal à l'envoi de loffre ( true si besoin de serveur STUN TURN car le signal contidendra les candidates (routes des serveurs)
            config:{} // c'est là qu'on passe les options comme  IceServers STUN et Turn pour des communications multiples , qui seront utilisées par RCTPeerConnection l'api javascript qui permet de démarrer la communication peer To peer , pour l'instant je me contente du serveur stun proposé par SimplePeer
        })
        // une fois qu'on a créé le flux et transmis le stream... on va greffer des évènements dessus
        Events(p) // simplePeer permet d"écouter les évenements ( comme des changements d'état) qui sont sur le flux de la communication  on va créer une  fonction séparée pour relier ces mêmes évenements sur plusieurs flux 
       
        let emetteurVideo =  document.querySelector('#emetteur-video') // on créé une variable qui représente le src de la balise  video de l'émetteur
        emetteurVideo.volume=1 // on met l'audio à 0 pour éviter le larsen pendant l'autotest
        emetteurVideo.srcObject = flux;// srcObject est une propriété de HTMLMediaElement qui  définit ou renvoie l'objet qui sert de source du média associé au HTMLMediaElement.  ça permet de lui affecter le flux 
       
        emetteurVideo.play()
      
      }).catch(function(err) {
        // on traite l'erreur
      });

}

document.querySelector('#start').addEventListener('click' , function(e){ 
    initiator = true;
    startPeer(true);
});

document.querySelector('#receive').addEventListener('click' , function(e){
    initiator = false;
    startPeer(false);
});


