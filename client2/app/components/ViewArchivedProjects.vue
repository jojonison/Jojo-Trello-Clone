<script setup lang="ts">
import type {AxiosResponse} from "axios";
import type {Project} from '~/utils/types/models/project';
import type {UnwrapRef} from "vue";
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

function unarchiveProject(id: UnwrapRef<Project["id"]>) {
  $api.post(`/unarchiveProject/${id}`)
      .then(() => {
        archivedProjects.value = archivedProjects.value?.filter(project => project.id !== id);
      })
}
</script>

<template>
  <main class="bg-blue-400 border-[6px] border-b-blue-950 rounded-xl shadow-lg">
    <div v-for="project in archivedProjects" :key="project.id" class="bg-blue-200 border-[2px] border-blue-950 p-2 text-blue-950">
      {{ project.project_name }}
      <UButton
          color="neutral"
          class="border-[2px] border-blue-950 hover:bg-blue-400 bg-blue-300"
          @click="unarchiveProject(project.id)"
      >
        Unarchive Project
      </UButton>
    </div>
  </main>
</template>