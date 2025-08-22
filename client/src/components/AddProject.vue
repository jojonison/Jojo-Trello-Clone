<script setup lang="ts">
import * as z from 'zod';
import {inject, ref} from "vue";
import {AxiosInstance, AxiosResponse} from "axios";
import {Project} from "@/types/models/project";

const ProjectInput = z.object({
  projectName: z.string().min(1)
})
const emit = defineEmits<{
  (e: "cancelAdd"): void;
  (e: "projectAdded", project: Project): void;
}>();
const projName = ref<string>("");
const injectedApi = inject<AxiosInstance>("api");
if (!injectedApi) {
  throw new Error("API instance not provided");
}
const api = injectedApi;
function cancelAdding() {
  emit("cancelAdd");
}

function addProject(projectName: string) {
  const result = ProjectInput.safeParse({projectName: projectName})
  if (!result.success) {
    alert(result.error);
  } else {
    api
        .post("/createProject", { project_name: projectName })
        .then((response: AxiosResponse) => {
          const newProject = response.data;
          alert("Project created successfully");
          projName.value = "";
          emit("projectAdded", newProject);
          emit("cancelAdd");
        });
  }
}

</script>

<template>
  <main>
    <input v-model="projName" placeholder="Project name:" class="max-w-6xl max-h-9 border-2 border-yellow-700 rounded-md px-3 py-2 bg-yellow-100 text-pink-800 text-1xl" />
    <div>
      <button
          @click="addProject(projName)"
          class="max-w-6xl max-h-9 border-2 border-yellow-700 rounded-md px-3 py-2 bg-yellow-100 hover:bg-yellow-500 text-pink-800 text-1xl"
      >
        ➕ Add Project
      </button>
      <button
          @click="cancelAdding"
          class="max-w-6/12 max-h-9 border-2 border-yellow-700 rounded-md px-3 py-2 bg-yellow-100 hover:bg-yellow-500 text-pink-800 text-1xl"
      >
        🚫 Cancel
      </button>
    </div>
  </main>
</template>