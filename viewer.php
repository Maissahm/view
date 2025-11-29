<?php
session_start();
require_once '../Controller/DonController.php';
require_once '../Controller/ProjetController.php';
$projetctrl = new ProjetController();
$projetsInfos = $projetctrl->getAllProjetsInfos();




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
<style>
  body {
  
  width: 95%;
    transform: scale(0.85);
    transform-origin: top center;

}
.popup-content {
    transform: scale(0.85);
    transform-origin: top center;
}


</style>

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

<!-- 🎁 AJOUT : Bouton Gift -->
<button id="giftBtn" style="font-size:15px; margin-top:10px;">🎁 Cadeaux</button>

<!-- 💳 Faire un don RGB -->
<div>
 <div>
  <input type="number" id="donMontant" placeholder="Montant (€)" style="padding:6px; width:200px;">
  
 <select id="donProjet" style="padding:6px; width:220px;">
    <option disabled selected>-- Choisir un projet --</option>

    <?php foreach ($projetsInfos as $p): ?>
        <?php if ($p['montant_collecte'] < $p['montant_estimé']): ?>
            <option value="<?= $p['id_projet'] ?>">
                <?= htmlspecialchars($p['titre_projet']) ?>
            </option>
        <?php endif; ?>
    <?php endforeach; ?>
</select>

  <input type="hidden" id="donStream" value="<?= $id_stream ?>">
  <input type="hidden" id="donUser" value="<?= $_SESSION['id_user'] ?? 0 ?>">

  <button id="btnPay" class="rgb-btn">💳 Faire un don</button>
</div>

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

// Afficher "Passer" après 10s
setTimeout(() => {
    skipText.style.display = "block";
}, 10);

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

<!-- 🎁 AJOUT : POPUP GIFT SHOP -->
<div id="giftShop" style="
    position:fixed;
    top:0; left:0;
    width:100%; height:100%;
    background:rgba(0,0,0,0.7);
    display:none;
    justify-content:center;
    align-items:center;
    z-index:99999;
">
  <div style="background:black; padding:20px; border-radius:10px; width:320px;">
    <h3>Cadeaux Tunisiens 🎁</h3>

    <div id="giftList" style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-top:40px;"></div>

    <button id="closeGiftShop" style="margin-top:20px; width:100%;">Fermer</button>
  </div>
</div>


<script>
const giftBtn = document.getElementById("giftBtn");
const giftShop = document.getElementById("giftShop");
const giftList = document.getElementById("giftList");
const closeGiftShop = document.getElementById("closeGiftShop");

// Charger les cadeaux
let gifts = [];
fetch("api/get_gifts.php")
    .then(res => res.json())
    .then(data => {
        gifts = data;
        console.log("Gifts chargés :", gifts);
    });

// Ouvrir la boutique
giftBtn.onclick = () => {
    giftList.innerHTML = "";
    gifts.forEach(g => {
        giftList.innerHTML += `
            <div onclick="sendGift(${g.id})"
                style="border:1px solid #ddd; padding:8px; border-radius:8px; cursor:pointer; text-align:center; background:white;">
                <img src="${g.photog}" style="width:120px; height:120px; object-fit:contain;"><br>
                <p style="color:black; font-weight:bold;">🎁 ${g.nameg}</p>
                <p style="color:black;"><strong>${g.priceg} DT</strong></p>
            </div>
        `;
    });
    giftShop.style.display = "flex";
};

// Fermer popup
closeGiftShop.onclick = () => {
    giftShop.style.display = "none";
};

// ENVOYER GIFT
function sendGift(id) {
    const gift = gifts.find(g => g.id == id);
    if (!gift) return;

    // 1) Envoyer via WebSocket
    socket.emit("send-gift", {
        streamId,
        giftId: gift.id,
        giftName: gift.nameg,
        amount: parseFloat(gift.priceg),
        logo: gift.photog,
        senderId: <?= json_encode($_SESSION['id_user']) ?>,
        senderName: <?= json_encode($_SESSION['user_name'] ?? "Inconnu") ?>,
        receiverId: <?= json_encode($stream['id_user']) ?>
    });

    // 2) Mettre à jour le portefeuille côté serveur
    fetch("api/add_revenue.php", {
        method: "POST",
        headers: {"Content-Type": "application/json"},
        body: JSON.stringify({
            receiverId: <?= json_encode($stream['id_user']) ?>,
            amount: parseFloat(gift.priceg)
        })
    });

    giftShop.style.display = "none";
}

// RECEVOIR GIFT
socket.on("receive-gift", (data) => {
    const div = document.createElement("div");
    div.innerHTML = `
        <strong>${data.senderName}</strong> a envoyé 🎁 
        <strong>${data.giftName}</strong>
        <span style="color:green;">(${data.amount.toFixed(2)} DT)</span>
    `;
    chat.appendChild(div);
    chat.scrollTop = chat.scrollHeight;
});
</script>
<script>
document.getElementById("btnPay").addEventListener("click", async () => {

    const montant = document.getElementById("donMontant").value;
    const projet  = document.getElementById("donProjet").value;
    const stream  = document.getElementById("donStream").value;
    const user    = document.getElementById("donUser").value;

    if (montant <= 0) {
        alert("Veuillez entrer un montant valide.");
        return;
    }

    const res = await fetch("api/create_checkout_session.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            montant: montant,
            id_projet: projet,
            id_stream: stream,
            id_user: user
        })
    });

    const data = await res.json();

    if (data.error) {
        alert("Erreur Stripe : " + data.error);
    }
    else if (data.url) {
       window.open(data.url, "_blank"); // 🌟 OUVERTURE NOUVEL ONGLET

    }
});
</script>



</body>
</html>
