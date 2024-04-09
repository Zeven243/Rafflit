<template>
    <nav aria-label="Page navigation">
      <ul class="pagination">
        <li v-for="link in links" :key="link.label" :class="{ 'disabled': link.url === null }">
          <a v-if="link.url" :href="link.url" v-html="link.label"></a>
          <span v-else v-html="link.label"></span>
        </li>
      </ul>
    </nav>
  </template>
  
  <script setup>
  import { defineProps } from 'vue';
  
  const props = defineProps({
    links: Array,
  });
  </script>
  
  <style scoped>
  /* Add your pagination styles here */
  .pagination {
    display: flex;
    list-style: none;
    padding: 0;
  }
  .pagination li {
    margin: 0 2px;
  }
  .pagination li a {
    color: #007bff;
    text-decoration: none;
  }
  .pagination li.disabled a {
    color: #6c757d;
    pointer-events: none;
  }
  </style>