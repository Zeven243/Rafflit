<template>
    <div>
      <canvas ref="chart"></canvas>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted, watch } from 'vue';
  import { Chart, registerables } from 'chart.js';
  
  const props = defineProps({
    data: {
      type: Object,
      required: true,
    },
  });
  
  const chart = ref(null);
  
  onMounted(() => {
    Chart.register(...registerables);
    renderChart();
  });
  
  watch(() => props.data, () => {
    renderChart();
  });
  
  const renderChart = () => {
    if (chart.value) {
      chart.value.destroy();
    }
  
    chart.value = new Chart(chart.value.getContext('2d'), {
      type: 'pie',
      data: props.data,
      options: {
        responsive: true,
        maintainAspectRatio: false,
      },
    });
  };
  </script>
  