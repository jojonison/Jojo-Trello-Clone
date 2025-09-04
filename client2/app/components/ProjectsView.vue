<template>
  <div class="bg-blue-400 border-[6px] border-blue-950 rounded-xl shadow-lg m-2">
    <div
        v-for="project in projects" :key="project.id"
        class="bg-blue-200 border-[2px] border-blue-950 p-2 text-blue-950">
      {{ project.project_name }}
      <UButton
          class="border-[2px] border-blue-950 hover:bg-blue-400 bg-blue-300"
          @click="selectProject(project.id)">Select Project
      </UButton>
    </div>
  </div>
</template>

<script setup lang="ts">
import type {Project} from '~/utils/types/models/project'
import type {AxiosResponse} from "axios";
import type {UnwrapRef} from "vue";

const {$api} = useNuxtApp();
const projects = ref<Project[]>([])
const emit = defineEmits<{
  (e: 'projectSelected', project: Project): void
}>()

function selectProject(id: UnwrapRef<Project["id"]> | undefined) {
  $api.get(`/showProject/${id}`, {withCredentials: true})
      .then((response: AxiosResponse) => {
        emit('projectSelected', response.data)
      })
}

onMounted(() => {
  loadProjects();
});

function loadProjects() {
  $api.get('/listProjects', {withCredentials: true})
      .then((response: AxiosResponse) => {
        projects.value = response.data
        console.log(projects.value)
      })
}
</script>