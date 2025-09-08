<template>
  <div class="bg-blue-400 border-[6px] border-blue-950 rounded-xl shadow-lg m-2">
    <AddProject @project-added="projects?.push($event)"/>
    <div
        v-for="project in projects" :key="project.id"
        class="bg-blue-200 border-[2px] border-blue-950 p-2 text-blue-950">
      {{ project.project_name }}
      <UButton
          color="neutral"
          class="border-[2px] border-blue-950 hover:bg-blue-400 bg-blue-300"
          @click="selectProject(project.id)"
      >
        Select Project
      </UButton>
      <UButton
          color="neutral"
          class="border-[2px] border-blue-950 hover:bg-blue-400 bg-blue-300"
          @click="archiveProject(project.id)"
      >
        📥 Archive Project
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
  (e: 'projectSelected', project: Project | undefined): void
}>()
const selectedProject = ref<Project | undefined>(undefined);
const toast = useToast()
function selectProject(id: Project["id"] | undefined) {
  $api.get(`/showProject/${id}`, { withCredentials: true })
      .then((response: AxiosResponse) => {
        // find the matching project in the list
        const found = projects.value.find(project => project.id === id);
        if (found) {
          Object.assign(found, response.data); // merge fields into existing object
          selectedProject.value = found;       // point directly to that object
        } else {
          selectedProject.value = response.data;
        }
        emit('projectSelected', selectedProject.value);
        toast.add({
          title: 'Selected a Project',
          description: `Current Project: ${selectedProject.value?.project_name}`,
          color: 'success'
        })
      })
}

function archiveProject(id: UnwrapRef<Project["id"]> | undefined) {
  $api.delete(`/archiveProject/${id}`)
      .then(() => {
        projects.value = projects.value.filter(project => project.id !== id);
        if (selectedProject.value?.id === id) {
          selectedProject.value = undefined
          emit('projectSelected', undefined)
          toast.add({
            title: 'Project has been archived',
            description: `Successfully Archived Project`,
            color: 'success'
          })
        }
      })
}

onMounted(() => {
  loadProjects();
});

function loadProjects() {
  $api.get('/listProjects', {withCredentials: true})
      .then((response: AxiosResponse) => {
        projects.value = response.data;
        console.log(projects.value);
      })
}
</script>