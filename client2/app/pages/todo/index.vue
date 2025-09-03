<script setup lang="ts">
import ProjectsView from "~/components/ProjectsView.vue";
const open = ref(false);
interface CommandItem {
  id: string;
  label: string;
  component: unknown;
}
const value = ref<CommandItem | null>(null);
const groups = [
  {
    id: "options",
    label: "Options",
    items: [
      { id: "projects", label: "View Projects", component: markRaw(ProjectsView) },
      { id: "unfinished", label: "View Unfinished Tasks", component: null },
      { id: "finished", label: "View Finished Tasks", component: null },
      { id: "archived", label: "View Archived Projects", component: null },
    ] as CommandItem[],
  },
];
const selectedComponent = computed(() => value.value?.component ?? null);

function handleKeydown(e: KeyboardEvent) {
  if (e.shiftKey && e.key.toLowerCase() === "p") {
    e.preventDefault();
    open.value = !open.value;
  }
}
onMounted(() => {window.addEventListener("keydown", handleKeydown);});
onBeforeUnmount(() => {window.removeEventListener("keydown", handleKeydown);});
</script>

<template>
  <main class="flex justify-center">
    <UButton
        label="Open Command Palette"
        color="neutral"
        variant="subtle"
        icon="i-lucide-search"
        class="bg-yellow-100/90 border-[6px] border-yellow-800 rounded-xl shadow-lg"
        @click="open = true"
    />
    <UModal v-model:open="open">
      <template #content>
        <UCommandPalette
            v-model="value"
            :groups="groups"
            close
            @update:open="open = $event"
        />
      </template>
    </UModal>
    <div class="mt-6">
      <component :is="selectedComponent" />
    </div>
  </main>
</template>
