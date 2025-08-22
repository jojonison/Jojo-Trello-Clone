<script setup lang="ts">
import { router } from '@/routes';
import {inject, onMounted, ref} from "vue";
import {Project} from "@/types/models/project";
import {AxiosInstance, AxiosResponse} from "axios";
import {useAuthStore} from "@/store/auth";

const injectedApi = inject<AxiosInstance>("api");
if (!injectedApi) {
  throw new Error("API instance not provided");
}
const api = injectedApi;
const auth = useAuthStore();
const archivedProjects = ref<Project[]>([])
const loading = ref<boolean>(false)

function goBackToMain() {
  router.push({name:'todo'})
}

onMounted(() => {
    getArchivedProjects();
}
)

function getArchivedProjects() {
  if (!auth.isLoggedIn) {return;}
  loading.value = true
  api
      .get('/listArchivedProjects', {})
      .then((response: AxiosResponse) => {
        archivedProjects.value = response.data
      })
      .finally(() => {
        loading.value = false;
      });
}

function unarchiveProject(id: number | string | undefined) {
  api.post(`/unarchiveProject/${id}`).then(() => {
    const index = archivedProjects.value.findIndex(project => project.id === id);
    if (index !== -1) {
      archivedProjects.value.splice(index, 1);
    }
  })
}
</script>

<template>
  <main class="w-full p-4 bg-yellow-100">
    <div class="mb-4">
      <button
          @click="goBackToMain"
          class="border-2 border-yellow-700 rounded-md px-3 py-2 bg-yellow-100 hover:bg-yellow-200 text-pink-800 text-xl"
      >
        ↩️ Go back to To-Do List
      </button>
    </div>
    <div class="w-full border-4 border-yellow-700 rounded-md px-4 py-3 bg-yellow-100/90 text-yellow-900">
      <label class="block mb-2 text-yellow-800 font-bold">Archived Projects:</label>
      <div v-if="archivedProjects.length === 0" class="text-yellow-800 italic">
        No archived projects
      </div>
      <div
          v-for="project in archivedProjects"
          :key="project.id"
          class="w-full border-4 border-yellow-700 rounded-md px-3 py-2 bg-yellow-50 text-yellow-900 mt-2"
      >
        <div class="flex flex-col gap-2">
          <span class="font-bold">Project:</span> {{ project.project_name }}
          <div class="flex flex-wrap gap-2 mt-2">
            <button
                @click="unarchiveProject(project.id)"
                class="border-2 border-yellow-700 rounded-md px-3 py-2 bg-yellow-100 hover:bg-yellow-200 text-pink-800"
            >
              ♻️ Unarchive Project
            </button>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>

