<script setup lang="ts">
import { inject, onMounted, ref } from "vue";
import { Project } from "@/types/models/project";
import { useAuthStore } from '@/store/auth';
import { AxiosInstance, AxiosResponse } from "axios";
import AddProject from "@/components/AddProject.vue";
import { router } from '@/routes';
import RenameProject from "@/components/RenameProject.vue";

const projects = ref<Project[]>([])
const loading = ref<boolean>(false);
const selectedProject = ref<Project | null>(null);
const injectedApi = inject<AxiosInstance>("api");
if (!injectedApi) {
  throw new Error("API instance not provided");
}
const api = injectedApi;
const auth = useAuthStore();
const emitSelectedProject = defineEmits<{
  (e: "selectedProject", project: Project): void;
}>();
const isAdding = ref<boolean>(false);
const isRenaming = ref<boolean>(false);

onMounted(() => {
  loadProjects();
});

function loadProjects() {
  if (!auth.isLoggedIn) {return;}
  loading.value = true
  api
      .get('/listProjects', {})
      .then((response: AxiosResponse) => {
        projects.value = response.data
        // Auto-select the first project
        if (projects.value.length > 0) {
          selectedProject.value = projects.value[0]
          selectProject();
        }
      })
      .finally(() => {
        loading.value = false;
      });
}

function selectProject() {
  if (selectedProject.value) {
    emitSelectedProject("selectedProject", selectedProject.value);
  }
}

function toggleAdd() {
  isAdding.value = !isAdding.value;
}

function cancelAdd() {
  isAdding.value = false;
}

function toggleRenameProject() {
  isRenaming.value = !isRenaming.value;
}

function cancelRename() {
  isRenaming.value = false;
}

function handleProjectAdded(project: Project) {
  projects.value.push(project);
  selectedProject.value = project;
  selectProject();
  isAdding.value = false;
}

function archiveProject() {
  api.delete(`/archiveProject/${selectedProject.value?.id}`).then(loadProjects);
}

function goToArchives() {
  router.push({name:'archives'})
}

function handleProjectRenamed(updatedProject: Project) {
  const idx = projects.value.findIndex(p => p.id === updatedProject.id);
  if (idx !== -1) {
    projects.value[idx] = updatedProject;
  }
  selectedProject.value = updatedProject;
}
</script>

<template>
  <main class="flex flex-col gap-6 border-[8px] border-yellow-800 bg-amber-100 p-6">
    <div class="flex flex-wrap gap-4 ">
      <button
          @click="toggleAdd"
          class="border-3 border-yellow-700 rounded-md px-3 py-2 bg-yellow-100 hover:bg-yellow-500 text-pink-800 text-xl"
      >
        ➕ Add Project
      </button>

      <AddProject
          v-if="isAdding"
          @cancelAdd="cancelAdd"
          @projectAdded="handleProjectAdded"
      />

      <button
          @click="archiveProject"
          class="border-3 border-yellow-700 rounded-md px-3 py-2 bg-yellow-100 hover:bg-yellow-500 text-pink-800 text-xl"
      >
        🗄️ Archive Current Project
      </button>

      <button
          @click="goToArchives"
          class="border-3 border-yellow-700 rounded-md px-3 py-2 bg-yellow-100 hover:bg-yellow-500 text-pink-800 text-xl"
      >
        🗃️ View Archives
      </button>

      <div class="flex wrap-anywhere">
        <button
            @click="toggleRenameProject"
            class="border-3 border-yellow-700 rounded-md px-3 py-2 bg-yellow-100 hover:bg-yellow-500 text-pink-800 text-xl"
        >
          ✏️ Rename Current Project
        </button>

        <RenameProject
            v-if="isRenaming"
            @cancelRename="cancelRename"
            @projectRenamed="handleProjectRenamed"
            :selectedProject="selectedProject"
        />
      </div>

    </div>

    <div class="flex flex-col gap-2">
      <span v-if="projects.length > 0" class="text-pink-800 text-2xl">
        Current Project: {{ selectedProject?.project_name }}
      </span>
      <span v-else class="text-lg text-pink-800">You have no projects</span>

      <select
          v-model="selectedProject"
          @change="selectProject"
          class="border-3 border-yellow-700 rounded-md px-3 py-2 bg-yellow-100 text-pink-800 text-xl"
      >
        <option
            v-for="project in projects"
            :key="project.id"
            :value="project"
            class="text-lg"
        >
          {{ project.project_name }}
        </option>
      </select>
    </div>
  </main>
</template>
