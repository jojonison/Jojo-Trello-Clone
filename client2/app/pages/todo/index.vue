<script setup lang="ts">
import ProjectsView from "~/components/ProjectsView.vue";
import ViewUnfinishedTasks from "~/components/ViewUnfinishedTasks.vue";
import ViewFinishedTasks from "~/components/ViewFinishedTasks.vue";
import ViewArchivedProjects from "~/components/ViewArchivedProjects.vue";
import type {Project} from "~/utils/types/models/project";
const open = ref(false);
interface CommandItem {
  id: string;
  label: string;
  component: unknown;
}
const selectedOption = ref<CommandItem | null>(null);
const options = [
  {
    id: "options",
    label: "Options",
    items: [
      { id: "projects", label: "View Projects", component: markRaw(ProjectsView) },
      { id: "unfinished", label: "View Unfinished Tasks", component: markRaw(ViewUnfinishedTasks) },
      { id: "finished", label: "View Finished Tasks", component: markRaw(ViewFinishedTasks) },
      { id: "archived", label: "View Archived Projects", component: markRaw(ViewArchivedProjects) },
    ] as CommandItem[],
  },
];
const selectedComponent = computed(() => selectedOption.value?.component ?? null);
const selectedProject = ref<Project>();

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
  <main>
    <div
        v-if="selectedProject"
        class="flex justify-center rounded-xl shadow-lg m-2 text-blue-400 font-extrabold text-4xl">
      Selected project: {{ selectedProject.project_name }}
    </div>
    <div class="flex flex-row justify-center">
      <div>
        <UButton
            label="Open Command Palette"
            color="neutral"
            variant="subtle"
            icon="i-lucide-search"
            class="bg-blue-400 border-[6px] border-b-blue-950 rounded-xl shadow-lg"
            @click="open = true"
        />
      </div>
      <div>
        <UModal v-model:open="open" class="flex flex-row justify-center max-w-fit">
          <template #content>
            <UCommandPalette
                v-model="selectedOption"
                :groups="options"
                close
                class="bg-blue-400 border-[6px] border-b-blue-950 rounded-xl shadow-lg w-dvw"
                @update:open="open = $event"
            />
          </template>
        </UModal>
      </div>
    </div>
    <div class="flex flex-row justify-center">
      <component
          :is="selectedComponent"
          :selected-project="selectedProject"
          @project-selected="selectedProject = $event"
      />
    </div>
  </main>
</template>
