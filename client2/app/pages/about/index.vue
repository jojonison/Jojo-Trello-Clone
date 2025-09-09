<script setup lang="ts">

</script>
<template>
  <main class="flex flex-col justify-center items-center min-h-screen ">
    <span class="m-5">
      This is the about page. Let me just load my profile below
    </span>
    <USkeleton class="h-12 w-12 rounded-full m-3" />
    <div class="flex items-center gap-4">
      <div class="grid gap-2">
        <USkeleton class="h-4 w-[300px]" />
        <USkeleton class="h-4 w-[300px]" />
        <USkeleton class="h-4 w-[300px]" />
        <USkeleton class="h-4 w-[300px]" />
        <USkeleton class="h-4 w-[300px]" />
        <USkeleton class="h-4 w-[300px]" />
        <USkeleton class="h-4 w-[300px]" />
      </div>
    </div>
  </main>
</template>