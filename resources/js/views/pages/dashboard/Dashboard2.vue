<script>
/**
 * Dashboard 2 Component
 */
export default {
  props: {
    data: {type: Array},
  },
  data() {
    return {
      options: {
        xaxis: {
          categories: [
            "January", "Febuary", "March", "April", "May", "June", "July", 
            "August", "September", "October", "November", "December"
          ]
        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '45%',
            borderRadius: 3
          },
        },
        theme: { mode: 'light' },
        dataLabels: { enabled: false },
      }
    };
  }
};
</script>

<template>
  <div class="card">
    <div class="card-body">
      <apexchart
        class="apex-charts"
        height="350"
        type="bar"
        dir="ltr"
        :series="data"
        :options="options"
      ></apexchart>
    </div>
  </div>
  <!-- end card -->
</template>