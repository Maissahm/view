const express = require("express");
const http = require("http");
const { Server } = require("socket.io");

const app = express();
const server = http.createServer(app);
const io = new Server(server, {
  cors: { origin: "*" }
});

let streamers = {};
let viewerCounts = {};

io.on("connection", (socket) => {
  console.log("✅ Client connecté :", socket.id);

  // STREAMER
  socket.on("register-streamer", ({ streamId }) => {
    streamers[streamId] = socket.id;
    socket.join("stream-" + streamId);
    socket.data.role = "streamer";
    socket.data.streamId = streamId;

    viewerCounts[streamId] ??= 0;
  });

  // VIEWER
  socket.on("register-viewer", ({ streamId }) => {
    socket.join("stream-" + streamId);
    socket.data.role = "viewer";
    socket.data.streamId = streamId;

    viewerCounts[streamId] ??= 0;
    viewerCounts[streamId]++;

    io.to("stream-" + streamId).emit("viewer-count", viewerCounts[streamId]);
  });

  // WEBRTC signaling
  socket.on("offer", ({ streamId, offer }) => {
    io.to("stream-" + streamId).emit("offer", { offer });
  });

  socket.on("answer", ({ streamId, answer }) => {
    io.to("stream-" + streamId).emit("answer", { answer });
  });

  socket.on("ice-candidate", (data) => {
    io.to("stream-" + data.streamId).emit("ice-candidate", data);
  });

  // CHAT
  socket.on("chat-message", (data) => {
    io.to("stream-" + data.streamId).emit("chat-message", data);
  });


  socket.on("send-gift", (data) => {

    console.log("🎁 Gift reçu :", data);

    io.to("stream-" + data.streamId).emit("receive-gift", {
      senderName: data.senderName,
      giftName: data.giftName,
      amount: data.amount,
      logo: data.logo
    });
  });

  // STOP LIVE
  socket.on("stop-live", ({ streamId }) => {
    io.to("stream-" + streamId).emit("streamer-disconnected");
    delete streamers[streamId];
    viewerCounts[streamId] = 0;
  });

  // DISCONNECT
  socket.on("disconnect", () => {
    const role = socket.data.role;
    const streamId = socket.data.streamId;

    if (!streamId) return;

    if (role === "viewer") {
      viewerCounts[streamId]--;
      io.to("stream-" + streamId).emit("viewer-count", viewerCounts[streamId]);
    }

    if (role === "streamer") {
      io.to("stream-" + streamId).emit("streamer-disconnected");
      delete streamers[streamId];
      viewerCounts[streamId] = 0;
    }
  });
});

server.listen(3000, () => {
  console.log("🚀 Serveur Socket.IO : http://localhost:3000");
});
