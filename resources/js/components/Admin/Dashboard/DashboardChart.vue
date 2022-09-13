<template>
    <div class="dashboard-trading-chart mt-5">
        <div id="chart">
            <apexchart  type="area" height="350" :options="chartOptions" :series="series"></apexchart>
        </div>
    </div>
</template>

<script>

export default {
    name:"DashboardChart",

    data() {
      return {
          
          series: [{
            type:"line",
            name: 'Deposit',
            data: [10, 5, 5, 15, 10,18, 34,26,26, 15, 27, 20, 24, 25],
          },{
            type:"line",
            name: 'Withdraw',
            data: [5, 10, 8, 15, 20,18, 24,26,16, 25, 27, 20, 24, 25],
          }],

          chartOptions: {
             chart: {
              height: 350,
              type: 'area',
              zoom: {
                enabled: false
              }
            },
            dataLabels: {
              enabled: false
            },
            stroke: {
              curve: 'smooth'
            },
            title: {
            
              align: 'left'
            },
            grid: {
              show:false,
              row: {
                colors: [ 'transparent'], // takes an array which will be repeated on columns
                opacity: 0.5
              },
            },
            xaxis: {
              categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
            }
          }
            
        }             
          
     }
    

}
</script>

<style lang="scss">
    .dashboard-trading-chart{
      width:50%
    }
</style>