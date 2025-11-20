<?php
require_once "../Controller/StreamController.php";

if (!isset($_GET['id'])) {
    die("❌ Aucun stream sélectionné.");
}

$id_stream = intval($_GET['id']);
$controller = new StreamController();
$stream = $controller->getStreamById($id_stream);

if (!$stream) {
    die("❌ Stream introuvable.");
}

$controller->incrementViewers($id_stream);

$titre = htmlspecialchars($stream['titre']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>👀 Viewer - <?= $titre ?></title>

  <script src="http://localhost:3000/socket.io/socket.io.js"></script>

  
  <link rel="stylesheet" href="assets/css/viewer.css">
</head>

<body>

<!-- 🔙 Retour RGB -->
<a href="../view/index.php" id="backBtn">⬅ Retour</a>

<h1>👀 Viewer - <?= $titre ?></h1>

<!-- 🎥 Vidéo RGB -->
<video id="remoteVideo" autoplay playsinline></video>

<p id="status">🔴 En attente du streamer...</p>

<!-- 💬 CHAT -->
<h3 style="margin-top:25px; text-shadow:0 0 12px #6a5df9;">💬 Chat en direct</h3>
<div id="chat"></div>

<input id="msgInput" type="text" placeholder="Écris ton message...">
<br>
<button id="sendBtn">Envoyer</button>

<!-- 💳 Faire un don RGB -->
<div>
  <a class="rgb-btn" href="dons.php>" target="_blank">💳 Faire un don</a>
</div>
<!-- Overlay publicité -->
<div id="adContainer" style="
    position: fixed;
    top:0; left:0;
    width:100%; height:100%;
    background:black;
    display:flex; 
    justify-content:center; 
    align-items:center; 
    flex-direction:column;
    z-index:9999;
    color:white;
">
    <video id="adVideo" width="80%" autoplay playsinline>
        <source src="assets/pub/pub.mp4" type="video/mp4">
        Votre navigateur ne supporte pas la lecture vidéo.
    </video>

    <button id="soundBtn" style="
        margin-top:10px;
        padding:10px 18px;
        border-radius:10px;
        font-weight:bold;
        cursor:pointer;
    ">🔊 Activer le son</button>

    <p id="skipText" style="margin-top:10px; font-size:18px; display:none; cursor:pointer;">
        Passer la publicité »
    </p>
</div>



<script>
  const streamId = <?= json_encode($id_stream) ?>;
  const socket = io("http://localhost:3000");

  socket.emit("register-viewer", { streamId });

  const videoEl = document.getElementById("remoteVideo");
  const statusEl = document.getElementById("status");

  const config = {
    iceServers: [
      { urls: "stun:stun.l.google.com:19302" }
    ]
  };

  let pc = null;

  function ensurePc() {
    if (pc) return pc;

    pc = new RTCPeerConnection(config);

    pc.ontrack = (event) => {
      videoEl.srcObject = event.streams[0];
    };

    pc.onicecandidate = (event) => {
      if (event.candidate) {
        socket.emit("ice-candidate", {
          streamId,
          from: "viewer",
          candidate: event.candidate
        });
      }
    };

    pc.onconnectionstatechange = () => {
      if (pc.connectionState === "connected") statusEl.textContent = "🟢 Stream en direct !";
      if (pc.connectionState === "disconnected") statusEl.textContent = "🟠 Connexion instable...";
      if (pc.connectionState === "closed") statusEl.textContent = "🔴 Live terminé";
    };

    return pc;
  }

  function closePc() {
    if (pc) {
      try { pc.close(); } catch {}
    }
    pc = null;
    videoEl.srcObject = null;
  }

  socket.on("offer", async ({ offer }) => {
    closePc();
    const peer = ensurePc();

    await peer.setRemoteDescription(offer);
    const answer = await peer.createAnswer();
    await peer.setLocalDescription(answer);

    socket.emit("answer", { streamId, answer });
  });

  socket.on("ice-candidate", async ({ candidate }) => {
    if (candidate && pc) {
      try { await pc.addIceCandidate(candidate); } catch {}
    }
  });

  socket.on("streamer-disconnected", () => {
    closePc();
    statusEl.textContent = "🔴 Le streamer a arrêté le live";
  });

  // ENVOYER MESSAGE
  document.getElementById("sendBtn").onclick = () => {
    const msg = msgInput.value.trim();
    if (msg === "") return;

    socket.emit("chat-message", {
      streamId,
      from: "👀 Viewer",
      text: msg
    });

    msgInput.value = "";
  };

  // RECEVOIR MESSAGE
  socket.on("chat-message", ({ from, text }) => {
    const div = document.createElement("div");
    div.innerHTML = `<strong>${from} :</strong> ${text}`;
    chat.appendChild(div);
    chat.scrollTop = chat.scrollHeight;
  });
</script>
<script>
const adContainer = document.getElementById("adContainer");
const adVideo = document.getElementById("adVideo");
const soundBtn = document.getElementById("soundBtn");
const skipText = document.getElementById("skipText");
const ads = [
    "assets/pub/pub1.mp4",
    "assets/pub/pub2.mp4",
      "assets/pub/pub3.mp4"
];
// Choisir une vidéo aléatoire
const randomIndex = Math.floor(Math.random() * ads.length);
adVideo.src = ads[randomIndex];

// Activer le son
soundBtn.addEventListener("click", () => {
    adVideo.muted = false;
    adVideo.volume = 1;
    adVideo.play();
    soundBtn.style.display = "none";
});

// Afficher "Passer" après 5s
setTimeout(() => {
    skipText.style.display = "block";
}, 10000);

// Fin automatique
adVideo.onended = () => {
    adContainer.style.display = "none";
};

// Skip manuel
skipText.addEventListener("click", () => {
    adContainer.style.display = "none";
    adVideo.pause();
});



</script>


</body>
</html>
