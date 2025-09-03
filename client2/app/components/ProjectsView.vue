<template>
  <div>
    <div v-for="project in projects" :key="project.id">
      {{project.project_name}}
    </div>
  </div>
</template>

<script setup lang="ts">
import type {Project} from '~/utils/types/models/project'
import type {AxiosResponse} from "axios";
const {$api} = useNuxtApp();
const projects = ref<Project[]>([])
// const selectedProject = ref<Project | null>(null);
onMounted(() => {
  loadProjects();
});
function loadProjects() {
  $api.get('/listProjects', { withCredentials: true })
      .then((response: AxiosResponse) => {
        projects.value = response.data
        console.log(projects.value)
        // Auto-select the first project
        // if (projects.value.length > 0) {
        //   selectedProject.value = projects.value[0]
        //   selectProject();
        // }
      })
}
</script>