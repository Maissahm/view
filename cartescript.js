<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>👀 Viewer - Game4Good</title>
  <script src="http://localhost:3000/socket.io/socket.io.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.10/vue.min.js"></script>
  <script src="https://unpkg.com/vue-the-mask@0.11.1/dist/vue-the-mask.js"></script>
  <link rel="stylesheet" href="https://public.codepenassets.com/css/reset-2.0.min.css">
  <link rel="stylesheet" href="assets/css/style-card.css">
  <style>
    body {
      background:#111;
      color:#fff;
      text-align:center;
      font-family:Arial,sans-serif;
    }
    video {
      width:80%;
      max-width:800px;
      border-radius:10px;
      margin-top:20px;
      border:3px solid #333;
    }
    #chat {
      width:70%;
      margin:30px auto;
      height:200px;
      background:#222;
      overflow-y:auto;
      padding:10px;
      border-radius:8px;
      text-align:left;
    }
    input {
      width:60%;
      padding:8px;
      border-radius:6px;
      border:none;
    }
    button {
      padding:10px 20px;
      font-size:16px;
      margin:10px;
      cursor:pointer;
      border-radius:6px;
      border:none;
      background:#3498db;
      color:#fff;
      transition:0.3s;
    }
    button:hover { background:#2980b9; }

    /* --- Section dons --- */
    #donationContainer {
      display:none;
      margin-top:40px;
    }

    #successMessage {
      display:none;
      background:#1a9f6e;
      color:#fff;
      padding:15px;
      border-radius:10px;
      width:60%;
      margin:20px auto;
    }
  </style>
</head>
<body>
  <h1>👀 Viewer - Live WebRTC</h1>
  <video id="remoteVideo" autoplay playsinline></video>
  <p id="status">🔴 En attente du streamer...</p>

  <div>
    <h3>💬 Chat en direct</h3>
    <div id="chat"></div>
    <input id="msgInput" type="text" placeholder="Écris ton message...">
    <button id="sendBtn">Envoyer</button>
  </div>

  <!-- === Section Don animé === -->
  <div id="donationContainer">
    <div class="wrapper" id="app">
      <div class="card-form">
        <div class="card-list">
          <div class="card-item" v-bind:class="{ '-active' : isCardFlipped }">
            <div class="card-item__side -front">
              <div class="card-item__focus" v-bind:class="{'-active' : focusElementStyle }" v-bind:style="focusElementStyle" ref="focusElement"></div>
              <div class="card-item__cover">
                <img v-bind:src="'./assets/images/' + currentCardBackground + '.jpeg'" class="card-item__bg">
              </div>
              
              <div class="card-item__wrapper">
                <div class="card-item__top">
                  <img src="./assets/images/chip.png" class="card-item__chip">
                  <div class="card-item__type">
                    <transition name="slide-fade-up">
                      <img v-bind:src="'./assets/images/' + getCardType + '.png'" v-if="getCardType" v-bind:key="getCardType" alt="" class="card-item__typeImg">
                    </transition>
                  </div>
                </div>

                <label for="cardNumber" class="card-item__number" ref="cardNumber">
                  <span v-for="(n, $index) in otherCardMask" :key="$index">
                    <transition name="slide-fade-up">
                      <div class="card-item__numberItem" v-if="cardNumber.length > $index">{{cardNumber[$index]}}</div>
                      <div class="card-item__numberItem" v-else>{{n}}</div>
                    </transition>
                  </span>
                </label>

                <div class="card-item__content">
                  <label for="cardName" class="card-item__info" ref="cardName">
                    <div class="card-item__holder">Card Holder</div>
                    <div class="card-item__name">{{ cardName || 'FULL NAME' }}</div>
                  </label>
                  <div class="card-item__date" ref="cardDate">
                    <label class="card-item__dateTitle">Expires</label>
                    <span>{{ cardMonth || 'MM' }}/{{ cardYear ? String(cardYear).slice(2,4) : 'YY' }}</span>
                  </div>
                </div>
              </div>
            </div>

            <div class="card-item__side -back">
              <div class="card-item__cover">
                <img v-bind:src="'./assets/images/' + currentCardBackground + '.jpeg'" class="card-item__bg">
              </div>
              <div class="card-item__cvv">
                <div class="card-item__cvvTitle">CVV</div>
                <div class="card-item__cvvBand">
                  <span v-for="(n, $index) in cardCvv" :key="$index">*</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="card-form__inner">
          <div class="card-input">
            <label for="cardNumber" class="card-input__label">Card Number</label>
            <input type="text" id="cardNumber" class="card-input__input" v-mask="generateCardNumberMask" v-model="cardNumber" @focus="focusInput" @blur="blurInput" data-ref="cardNumber" autocomplete="off">
          </div>
          <div class="card-input">
            <label for="cardName" class="card-input__label">Card Holder</label>
            <input type="text" id="cardName" class="card-input__input" v-model="cardName" @focus="focusInput" @blur="blurInput" data-ref="cardName" autocomplete="off">
          </div>
          <div class="card-form__row">
            <div class="card-form__col">
              <label for="cardMonth" class="card-input__label">Exp. Month</label>
              <select id="cardMonth" class="card-input__input -select" v-model="cardMonth" data-ref="cardDate">
                <option value="">MM</option>
                <option v-for="n in 12" :value="n < 10 ? '0' + n : n">{{ n < 10 ? '0' + n : n }}</option>
              </select>
            </div>
            <div class="card-form__col">
              <label for="cardYear" class="card-input__label">Exp. Year</label>
              <select id="cardYear" class="card-input__input -select" v-model="cardYear" data-ref="cardDate">
                <option value="">YY</option>
                <option v-for="(n, $index) in 10" :value="minCardYear + $index">{{ minCardYear + $index }}</option>
              </select>
            </div>
            <div class="card-form__col -cvv">
              <label for="cardCvv" class="card-input__label">CVV</label>
              <input type="text" id="cardCvv" class="card-input__input" v-mask="'####'" maxlength="4" v-model="cardCvv" @focus="flipCard(true)" @blur="flipCard(false)" autocomplete="off">
            </div>
          </div>

          <div class="card-input">
            <label for="donAmount" class="card-input__label">Amount (€)</label>
            <input type="number" id="donAmount" class="card-input__input" v-model="donAmount" min="1" step="0.5" placeholder="5.00">
          </div>

          <button class="card-form__button" @click.prevent="submitDonation">Send Donation</button>
        </div>
      </div>
    </div>
  </div>

  <div id="successMessage">🎉 Merci pour votre don simulé !</div>

  <script>
    const socket = io("http://localhost:3000");
    socket.emit("register-viewer");

    let pc;
    const remoteVideo = document.getElementById("remoteVideo");
    const status = document.getElementById("status");
    const chatBox = document.getElementById("chat");
    const msgInput = document.getElementById("msgInput");
    const sendBtn = document.getElementById("sendBtn");
    const donationContainer = document.getElementById("donationContainer");
    const successMessage = document.getElementById("successMessage");

    // === WebRTC ===
    socket.on("offer", async (offer) => {
      status.innerText = "🟢 Stream en direct !";
      pc = new RTCPeerConnection();
      pc.ontrack = (event) => remoteVideo.srcObject = event.streams[0];
      pc.onicecandidate = (e) => e.candidate && socket.emit("ice-candidate", e.candidate);
      await pc.setRemoteDescription(new RTCSessionDescription(offer));
      const answer = await pc.createAnswer();
      await pc.setLocalDescription(answer);
      socket.emit("answer", answer);
    });
    socket.on("ice-candidate", async (c) => { if (c && pc) await pc.addIceCandidate(new RTCIceCandidate(c)); });
    socket.on("streamer-disconnected", () => { status.innerText = "🔴 Streamer déconnecté"; remoteVideo.srcObject = null; });

    // === CHAT ===
    sendBtn.onclick = () => {
      const msg = msgInput.value.trim();
      if (msg) socket.emit("chat-message", { from: "👀 Viewer", text: msg });
      msgInput.value = "";
    };
    socket.on("chat-message", (d) => {
      const div = document.createElement("div");
      div.innerHTML = `<strong>${d.from}:</strong> ${d.text}`;
      chatBox.appendChild(div);
      chatBox.scrollTop = chatBox.scrollHeight;
    });

    // === DONS ===
    socket.on("show-donation-form", () => {
      donationContainer.style.display = "block";
      window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
    });

    // === VUE COMPONENT ===
    new Vue({
      el: "#app",
      data() {
        return {
          currentCardBackground: Math.floor(Math.random()* 25 + 1),
          cardName: "", cardNumber: "", cardMonth: "", cardYear: "",
          cardCvv: "", donAmount: "", minCardYear: new Date().getFullYear(),
          otherCardMask: "#### #### #### ####",
          isCardFlipped: false, focusElementStyle: null, isInputFocused: false
        };
      },
      computed: {
        getCardType() {
          const n = this.cardNumber;
          if (/^4/.test(n)) return "visa";
          if (/^5[1-5]/.test(n)) return "mastercard";
          if (/^(34|37)/.test(n)) return "amex";
          return "visa";
        },
        generateCardNumberMask() { return this.otherCardMask; },
      },
      methods: {
        flipCard(s) { this.isCardFlipped = s; },
        focusInput(e) {
          let t = e.target.dataset.ref, ref = this.$refs[t];
          this.focusElementStyle = { width:`${ref.offsetWidth}px`, height:`${ref.offsetHeight}px`, transform:`translateX(${ref.offsetLeft}px) translateY(${ref.offsetTop}px)` };
        },
        blurInput() { this.focusElementStyle = null; },
        submitDonation() {
          const amount = parseFloat(this.donAmount);
          if (!amount || amount <= 0) { alert("⚠️ Montant invalide !"); return; }
          socket.emit("viewer-donation", amount);
          document.getElementById("donationContainer").style.display = "none";
          successMessage.style.display = "block";
          setTimeout(()=> successMessage.style.display="none", 4000);
        }
      }
    });
  </script>
</body>
</html>
