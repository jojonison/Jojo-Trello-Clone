<script setup lang="ts">
import type {AxiosResponse} from "axios";
import type {Project} from '~/utils/types/models/project';
const {$api} = useNuxtApp();
const archivedProjects = ref<Project[]>();
onMounted(() => {
  loadArchivedProjects();
});
function loadArchivedProjects() {
  $api.get(`/listArchivedProjects`)
      .then((response: AxiosResponse) => {
        archivedProjects.value = response.data;
      })
}
</script>

<template>
  <main class="bg-blue-400 border-[6px] border-b-blue-950 rounded-xl shadow-lg">
    <div v-for="project in archivedProjects" :key="project.id" class="bg-blue-200 border-[2px] border-blue-950 p-2 text-blue-950">
      {{ project.project_name }}
    </div>
  </main>
</template>