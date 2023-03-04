<script setup lang="ts">
import PageLoader from '@/components/PageLoader.vue';
import WeatherModal from '@/components/WeatherModal.vue';
import { ref } from 'vue';

const users = ref([]);
const isLoading = ref(false);

const getUsers = async () => {
  const url = 'http://localhost/users';
  isLoading.value = true;
  let response = await (await fetch(url)).json()

  if (['array', 'object'].includes(typeof response)) {
    users.value = response
  };

  isLoading.value = false;
};

getUsers();
</script>

<template>
  <PageLoader :load="isLoading"></PageLoader>
  <main v-if="!isLoading">
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
      <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase dark:text-gray-400">
          <tr>
            <th scope="col" class="px-6 py-3 bg-gray-50 dark:bg-gray-800">
              Name
            </th>
            <th scope="col" class="px-6 py-3">
              Email
            </th>
            <th scope="col" class="px-6 py-3 bg-gray-50 dark:bg-gray-800">
              Options
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(user, index) in users" :key="index" class="border-b border-gray-200 dark:border-gray-700">
            <th scope="row"
              class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap bg-gray-50 dark:text-white dark:bg-gray-800">
              {{ user?.name }}
            </th>
            <td class="px-6 py-4">
              {{ user?.email }}
            </td>
            <td class="px-6 py-4 bg-gray-50 dark:bg-gray-800">
              <WeatherModal :user="user"></WeatherModal>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </main>
</template>