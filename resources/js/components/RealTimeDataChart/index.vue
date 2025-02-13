<template>
  <div class="app-container">
    <div ref="chart" :style="{height:'600px',width:'100%'}" />
    <div v-if="displayTable">
      <el-table :data="tableData" style="width: 100%" stripe border>
        <el-table-column
          v-for="itemLabel in tableDataLabels"
          :key="itemLabel"
          :label="itemLabel"
        >
          <template slot-scope="scope">
            {{ scope.row[itemLabel] }}
          </template>
        </el-table-column>
      </el-table>
    </div>
  </div>
</template>

<script>
const echarts = require('echarts');
const UPDATE_TIME_PERIOD = 5 * 60 * 1000; // 5 minutes

export default {
  name: 'RealTimeDataChart',
  props: {
    title: {
      required: true,
      type: String,
    },
    chartData: {
      required: true,
      type: Array,
    },
    chartTimeData: {
      required: true,
      type: Array,
    },
    displayTable: {
      type: Boolean,
      default: false,
    },
    getNewItem: {
      required: true,
      type: Function,
    },
  },
  data() {
    return {
      internalChartData: [],
      internalChartTimeData: [],
      tableData: [],
      tableDataLabels: [],
      options: null,
    };
  },
  watch: {
    chartData(val) {
      this.initChartData();
      this.setOptions();
    },
    chartTimeData(val) {
      this.initChartData();
      this.setOptions();
    },
  },
  mounted() {
    this.initChartData();
    this.initCharts();
    this.updateChartTimeout();
  },
  methods: {
    initChartData() {
      this.internalChartData = this.chartData.map(one => ({ type: 'line', ...one }));
      this.internalChartTimeData = this.chartTimeData;
    },
    initCharts() {
      this.chart = echarts.init(this.$refs.chart);
      this.setOptions();
      if (this.displayTable) {
        this.calculateTableData();
      }
    },
    setOptions() {
      this.options = {
        title: { text: this.title },
        tooltip: { trigger: 'axis' },
        legend: { data: this.internalChartData.map(one => one.name) },
        xAxis: { data: this.internalChartTimeData },
        yAxis: { type: 'value' },
        series: this.internalChartData,
        toolbox: {
          feature: {
            saveAsImage: {},
          },
        },
      };
      this.chart.setOption(this.options);
    },
    calculateTableData() {
      this.tableData = this.chartTimeData.map((date, ndx) => {
        const rowData = Object.assign({}, ...this.chartData.map(({ name, data }) => ({ [name]: data[ndx] })));
        return { date: date, ...rowData };
      });
      if (this.tableData.length) {
        this.tableDataLabels = Object.keys(this.tableData[0]);
      }
    },
    updateChartTimeout() {
      setInterval(this.fetchItem, UPDATE_TIME_PERIOD);
    },
    fetchItem() {
      this.getNewItem().then(response => {
        if (response.data.length === 0) {
          return;
        }
        const ndx = this.internalChartTimeData.findIndex((date) => date === response.date);
        if (ndx === -1) {
          this.internalChartTimeData.push(response.date);
          Object.keys(response.data).forEach((one) => {
            const ndx = this.internalChartData.findIndex((el) => el.name === one);
            if (ndx !== -1) {
              this.internalChartData[ndx].data.push(response.data[one]);
            }
          });
        } else {
          Object.keys(response.data).forEach((name) => {
            const ndxVal = this.internalChartData.findIndex((el) => el.name === name);
            if (ndxVal !== -1) {
              this.internalChartData[ndxVal].data[ndx] = response.data[name];
            }
          });
        }
        this.initCharts();
        this.updateChartTimeout();
      });
    },
  },
};
</script>
