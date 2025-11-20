const express = require("express");
const http = require("http");
const { Server } = require("socket.io");

const app = express();
const server = http.createServer(app);
const io = new Server(server, {
    cors: { origin: "*" }
});

let streamers = {};

io.on("connection", socket => {

    socket.on("register-streamer", ({ streamId }) => {
        streamers[streamId] = socket.id;
        socket.join("stream-" + streamId);
        console.log("🎙️ Streamer connecté :", streamId);
    });

    socket.on("register-viewer", ({ streamId }) => {
        socket.join("stream-" + streamId);
        console.log("👀 Viewer rejoint :", streamId);
    });

    socket.on("offer", ({ streamId, offer }) => {
        io.to("stream-" + streamId).emit("offer", { offer });
    });

    socket.on("answer", ({ streamId, answer }) => {
        io.to("stream-" + streamId).emit("answer", { answer });
    });

    socket.on("ice-candidate", data => {
        io.to("stream-" + data.streamId).emit("ice-candidate", data);
    });

    socket.on("chat-message", data => {
        io.to("stream-" + data.streamId).emit("chat-message", data);
    });

    // --- 🔥 STOP LIVE ----
    socket.on("stop-live", ({ streamId }) => {
        console.log("⛔ Stream stoppé par le streamer :", streamId);
        io.to("stream-" + streamId).emit("streamer-disconnected");
        delete streamers[streamId];
    });

    // --- 🔥 STREAMER QUITTE LE NAVIGATEUR ----
    socket.on("disconnect", () => {
        for (const id in streamers) {
            if (streamers[id] === socket.id) {
                console.log("❌ Streamer quitté :", id);
                io.to("stream-" + id).emit("streamer-disconnected");
                delete streamers[id];
            }
        }
    });

});

server.listen(3000, () =>
    console.log("✅ Serveur WebRTC/Socket.io lancé sur : http://localhost:3000")
);
