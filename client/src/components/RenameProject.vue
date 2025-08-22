<script setup lang="ts">

import {Project} from "@/types/models/project";
import {inject, ref} from "vue";
import {AxiosInstance} from "axios";
import * as z from 'zod';
const injectedApi = inject<AxiosInstance>("api");
if (!injectedApi) {throw new Error("API instance not provided");}
const api = injectedApi;
const emit = defineEmits<{
  (e: "cancelRename"): void;
  (e: "projectAdded", project: Project): void;
  (e: "projectRenamed", project: Project): void;
}>();
const currentProject = defineProps<{
  selectedProject: Project | null
}>()
const newName = ref<string>('');
const ProjectInput = z.object({
  newName: z.string().min(1),
})

function rename(renamed: string) {
  const result = ProjectInput.safeParse({newName: renamed})
  if (!result.success) {
    alert(result.error);
  } else {
    if (currentProject.selectedProject) {
      api.put(`/updateProject/${currentProject.selectedProject.id}`, {
        project_name: renamed
      }).then((res) => {
        emit("projectRenamed", res.data);
        emit("cancelRename");
      });
    }
  }
}

function cancel() {
  emit("cancelRename");
}
</script>

<template>
  <div>
    <input v-model="newName" placeholder="New Project Name:" class="max-w-6xl max-h-9 border-2 border-yellow-700 rounded-md px-3 py-2 bg-yellow-100 text-pink-800 text-1xl">
    <button @click="rename(newName)" class="max-w-6/12 max-h-9 border-2 border-yellow-700 rounded-md px-3 py-2 bg-yellow-100 hover:bg-yellow-500 text-pink-800 text-1xl">📝 Rename</button>
    <button @click="cancel" class="max-w-6/12 max-h-9 border-2 border-yellow-700 rounded-md px-3 py-2 bg-yellow-100 hover:bg-yellow-500 text-pink-800 text-1xl">🚫 Cancel</button>
  </div>
</template>