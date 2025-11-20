
<?php
require_once '../Controller/StreamController.php';
$streamCtrl = new StreamController();

if (!isset($_GET['id'])) {
    die("❌ Aucun stream sélectionné.");
}

$streamId = intval($_GET['id']);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>🎥 G4S - Streamer Live</title>

  <script src="http://localhost:3000/socket.io/socket.io.js"></script>

  <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/css/fontawesome.css">
  <link rel="stylesheet" href="assets/css/templatemo-cyborg-gaming.css">
  <link rel="stylesheet" href="assets/css/owl.css">
  <link rel="stylesheet" href="assets/css/animate.css">

 
  <link rel="stylesheet" href="assets/css/streamer.css">
</head>

<body>

<div id="notifPanel"></div>

<header>
  <nav class="main-nav">
    <a href="streams.html" class="logo">
      <img src="images/page.png">
      <span>G4S</span>
    </a>

    <div style="display:flex;gap:12px;">
      <button id="openFormBtn">🎁 Formulaire</button>
      <button id="shareBtn">🖥️ Partager écran</button>
      <button id="stopShareBtn">🛑 Stop Partage</button>
      <button id="stopBtn" class="stop-btn">⛔ Stop Live</button>
    </div>
  </nav>
</header>

<div class="live-wrapper">

  <div class="live-zone">
    <h4 id="liveStatus">🔴 Live non démarré</h4>

    <button id="startBtn">🚀 Démarrer le live</button>

    <video id="localVideo" autoplay muted playsinline></video>


  </div>

  <div class="chat-zone">
    <div class="chat-messages" id="chat">
      <p><strong>Système :</strong> Bienvenue dans le chat !</p>
    </div>

    <div class="chat-input">
      <input id="msgInput" type="text" placeholder="Écrire un message...">
      <button id="sendBtn">Envoyer</button>
    </div>
  </div>

</div>

<footer>
  © 2025 G4S Streaming Platform — Tous droits réservés.
</footer>

<script>
const streamId = <?= json_encode($streamId) ?>;
const socket = io("http://localhost:3000");

// REGISTER STREAMER
socket.emit("register-streamer", { streamId });

const videoEl = document.getElementById("localVideo");
const startBtn = document.getElementById("startBtn");
const stopBtn = document.getElementById("stopBtn");
const shareBtn = document.getElementById("shareBtn");
const stopShareBtn = document.getElementById("stopShareBtn");
const openFormBtn = document.getElementById("openFormBtn");

let pc = null;
let localStream = null;
let screenTrack = null;

const config = { iceServers: [{ urls: "stun:stun.l.google.com:19302" }] };

function ensurePc() {
  if (pc) return pc;

  pc = new RTCPeerConnection(config);

  pc.onicecandidate = (event) => {
    if (event.candidate) {
      socket.emit("ice-candidate", {
        streamId,
        from: "streamer",
        candidate: event.candidate
      });
    }
  };
  return pc;
}

// START LIVE
startBtn.onclick = async () => {
  localStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
  videoEl.srcObject = localStream;
  videoEl.style.display = "block";

  pc = ensurePc();
  localStream.getTracks().forEach(track => pc.addTrack(track, localStream));

  const offer = await pc.createOffer();
  await pc.setLocalDescription(offer);

  socket.emit("offer", { streamId, offer });

  startBtn.style.display = "none";
  liveStatus.innerText = "🟢 Live en cours";
};

// SCREEN SHARE
shareBtn.onclick = async () => {
  const screenStream = await navigator.mediaDevices.getDisplayMedia({ video: true });
  screenTrack = screenStream.getVideoTracks()[0];

  const sender = pc.getSenders().find(s => s.track.kind === "video");
  sender.replaceTrack(screenTrack);

  pushNotif("📺 Partage d’écran activé");

  screenTrack.onended = () => {
    sender.replaceTrack(localStream.getVideoTracks()[0]);
    pushNotif("🛑 Partage d’écran stoppé");
  };
};

// STOP SHARE BUTTON
stopShareBtn.onclick = () => {
  if (!screenTrack) return pushNotif("⚠️ Aucun partage actif");
  screenTrack.stop();
  screenTrack = null;
  pushNotif("🛑 Partage d’écran stoppé");
};

// OPEN DONATION FORM
openFormBtn.onclick = () => {
  window.open("dons.html", "_blank");
  socket.emit("launch-donation");
  pushNotif("🎁 Formulaire de don ouvert");
};

// STOP LIVE
stopBtn.onclick = () => {
  // 🔴 INFORMER LES VIEWERS
  socket.emit("streamer-disconnected", { streamId });

  if (pc) pc.close();
  if (localStream) localStream.getTracks().forEach(t => t.stop());

  pushNotif("⛔ Live stoppé");
  setTimeout(() => window.location.href = "index.php", 1000);
};


// ANSWER
socket.on("answer", async ({ answer }) => pc.setRemoteDescription(answer));
socket.on("ice-candidate", async ({ candidate }) => pc.addIceCandidate(candidate));

socket.on("viewer-count", (count) => {
  document.getElementById("viewerCount").textContent = count;
});

// CHAT SEND
sendBtn.onclick = () => {
  const msg = msgInput.value.trim();
  if (!msg) return;

  socket.emit("chat-message", {
    streamId,
    from: "🎙️ Streamer",
    text: msg
  });

  msgInput.value = "";
};

// CHAT RECEIVE
socket.on("chat-message", ({ from, text }) => {
  const div = document.createElement("div");
  div.innerHTML = `<strong>${from} :</strong> ${text}`;
  chat.appendChild(div);
  chat.scrollTop = chat.scrollHeight;
});

// NOTIFS
function pushNotif(text) {
  const box = document.getElementById("notifPanel");
  const div = document.createElement("div");
  div.textContent = text;
  div.style.padding = "12px";
  div.style.background = "#232A45";
  div.style.borderRadius = "10px";
  div.style.boxShadow = "0 0 12px #6A5DF9";
  box.prepend(div);

  setTimeout(() => {
    div.style.opacity = 0;
    setTimeout(() => div.remove(), 300);
  }, 3000);
}
</script>

</body>
</html>
