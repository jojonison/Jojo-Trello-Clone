<script setup>
import { ref, onMounted } from "vue";
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

const animation = ref(null); // reference to the <pre>

function renderFrame() {
  if (animation.value) {
    animation.value.textContent = frames[currentFrame];
  }
}

function animate() {
  setInterval(() => {
    renderFrame();
    currentFrame = (currentFrame + 1) % totalFrames;
  }, 1000 / FRAME_RATE);
}

onMounted(() => {
  animate();
});
</script>

<template>
  <div class="flex justify-center items-center h-screen bg-[rgb(10,10,10)] m-0 p-0">
    <pre
        ref="animation"
        class="text-[rgb(244,244,244)] font-mono whitespace-pre-wrap leading-[1.2] text-[1vw] max-[1500px]:text-[1.5vw] max-[1000px]:text-[2vw]"
    />

    <!-- link button -->
    <a
        href="https://github.com/juxtopposed/ascii-rickroll-js"
        target="_blank"
        class="fixed top-0 right-0 p-8 text-[rgba(255,255,255,0.5)] hover:text-white"
    >
      <!-- SVG -->
    </a>
    <span class="font-extrabold">You must log in first</span>
  </div>
</template>
