<template>
  <div />
</template>

<script>
import DashboardResource from '@/api/dashboard';
import echarts from 'echarts';
require('echarts/theme/macarons'); // echarts theme
import { debounce } from '@/utils';
const Dashboard = new DashboardResource();

export default {
  props: {
    chartType: {
      type: String,
      default: 'none', // none | login | registration
    },
    className: {
      type: String,
      default: 'chart',
    },
    width: {
      type: String,
      default: '100%',
    },
    height: {
      type: String,
      default: '300px',
    },
    title: {
      type: String,
      default: '',
    },
    labels: {
      type: Array,
      default: () => [],
    },
    values: {
      type: Array,
      default: () => [],
    },
    animationDuration: {
      type: Number,
      default: 7000,
    },
    infoName: {
      type: String,
      default: '',
    },
    markInside: {
      type: String,
      default: '',
    },
    boxLabel: {
      type: String,
      default: '',
    },
  },
  data() {
    return {
      chart: null,
      // chart Registration login
      labelsRegistration: [],
      valuesRegistration: [],
    };
  },
  mounted() {
    if (this.chartType === 'registration') {
      this.handleUserRegistration();
    } else if (this.chartType === 'login') {
      this.handleUserLogin();
    } else {
      this.initChart();
    }
    this.__resizeHandler = debounce(() => {
      if (this.chart) {
        this.chart.resize();
      }
    }, 100);
    window.addEventListener('resize', this.__resizeHandler);
  },
  beforeDestroy() {
    if (!this.chart) {
      return;
    }
    window.removeEventListener('resize', this.__resizeHandler);
    this.chart.dispose();
    this.chart = null;
  },
  methods: {
    initChart() {
      this.chart = echarts.init(this.$el, 'macarons');
      const option = {
        title: {
          text: this.title,
          textStyle: {
            color: '#000',
            fontWeight: 'bold',
          },
        },
        tooltip: {
          trigger: 'axis',
          axisPointer: {
            type: 'shadow',
          },
        },
        grid: {
          left: '3%',
          right: '4%',
          bottom: '3%',
          containLabel: true,
        },
        xAxis: [
          {
            type: 'category',
            data: (this.labels[0]) ? this.labels : this.labelsRegistration,
            axisTick: {
              alignWithLabel: true,
            },
          },
        ],
        yAxis: [
          {
            type: 'value',
          },
        ],
        series: [
          {
            name: (this.boxLabel === '') ? this.$t('charts.users') : null,
            type: 'bar',
            barWidth: '60%',
            label: (this.markInside === 'inside') ? {
              show: true,
              position: 'inside',
            } : {},
            data: (this.values[0]) ? this.values : this.valuesRegistration,
          },
        ],
      };
      this.chart.setOption(option);
    },
    handleUserRegistration() {
      Dashboard
        .usersCountsRegistration()
        .then(response => {
          const { data } = response;
          const labelsRegistration = [];
          const valuesRegistration = [];
          data.forEach((elem, index) => {
            if (index !== 5) {
              labelsRegistration.push(elem.date);
              valuesRegistration.push(elem.count);
            }
          });
          this.labelsRegistration = labelsRegistration;
          this.valuesRegistration = valuesRegistration;
          // chart
          this.initChart();
        });
    },
    handleUserLogin() {
      Dashboard
        .usersCountsLogin()
        .then(response => {
          const { data } = response;

          const labelsRegistration = [];
          const valuesRegistration = [];
          data.forEach((elem, index) => {
            if (index !== 5) {
              labelsRegistration.push(elem.date);
              valuesRegistration.push(elem.count);
            }
          });
          this.labelsRegistration = labelsRegistration;
          this.valuesRegistration = valuesRegistration;
          // chart
          this.initChart();
        });
    },
  },
};
</script>
