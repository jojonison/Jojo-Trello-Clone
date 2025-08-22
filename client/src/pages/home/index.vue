<script setup>
import frameSeq from "./rickroll.js";

const LINES_PER_FRAME = 36;
const FRAME_RATE = 15;

const lines = frameSeq.split('\n').map(line => line.trim());
const frames = [];

for (let i = 0; i < lines.length; i += LINES_PER_FRAME) {
  frames.push(lines.slice(i, i + LINES_PER_FRAME).join('\n'));
}

const totalFrames = frames.length;
let currentFrame = 0;

function renderFrame() {
  animation.textContent = frames[currentFrame];
}

function animate() {
  setInterval(() => {
    renderFrame();
    currentFrame = (currentFrame + 1) % totalFrames;
  }, 1000 / FRAME_RATE);
}

animate();
</script>

<template>
  <div class="flex justify-center items-center h-screen bg-[rgb(10,10,10)] m-0 p-0">
    <pre
        id="animation"
        class="text-[rgb(244,244,244)] font-mono whitespace-pre-wrap leading-[1.2] text-[1vw] max-[1500px]:text-[1.5vw] max-[1000px]:text-[2vw]"
    ></pre>

    <a
        href="https://github.com/juxtopposed/ascii-rickroll-js"
        target="_blank"
        class="fixed top-0 right-0 p-8 text-[rgba(255,255,255,0.5)] hover:text-white"
    >
      <svg
          xmlns="http://www.w3.org/2000/svg"
          height="24px"
          width="24px"
          viewBox="0 -960 960 960"
          fill="currentColor"
      >
        <path
            d="M480-280q17 0 28.5-11.5T520-320v-160q0-17-11.5-28.5T480-520q-17 0-28.5 11.5T440-480v160q0 17 11.5 28.5T480-280Zm0-320q17 0 28.5-11.5T520-640q0-17-11.5-28.5T480-680q-17 0-28.5 11.5T440-640q0 17 11.5 28.5T480-600Zm0 520q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Z"
        />
      </svg>
    </a>
  </div>
</template>
