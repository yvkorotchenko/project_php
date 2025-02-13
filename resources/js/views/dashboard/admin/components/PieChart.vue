<template>
  <div :class="className" :style="{height:height,width:width}" />
</template>

<script>
import echarts from 'echarts';
require('echarts/theme/macarons'); // echarts theme
import { debounce } from '@/utils';
import DashboardResource from '@/api/dashboard';
const Dashboard = new DashboardResource();

export default {
  props: {
    className: {
      type: String,
      default: 'chart',
    },
    title: {
      type: String,
      default: '',
    },
    width: {
      type: String,
      default: '100%',
    },
    height: {
      type: String,
      default: '300px',
    },
  },
  data() {
    return {
      chart: null,
      data: [],
    };
  },
  computed: {
    languageNoDate: function() {
      return this.$t('charts.noDate');
    },
  },
  mounted() {
    this.handlerAmountPlatform();
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
    handlerAmountPlatform() {
      const platforms = [];
      Dashboard
        .betAmountPlatform()
        .then(response => {
          const { data } = response;
          if (data.length > 0) {
            data.forEach(function(item, index){
              platforms.push({ value: item.totalBetAmount, name: item.companyName });
            });
          } else {
            platforms.push({ value: 0, name: this.languageNoDate });
          }
          // build chart
          this.initChart();
        });
      this.data = platforms;
    },
    initChart() {
      this.chart = echarts.init(this.$el, 'macarons');
      const options = {
        title: {
          text: this.title,
          left: 'center',
          textStyle: {
            color: '#000',
            fontWeight: 'bold',
          },
        },
        tooltip: {
          trigger: 'item',
          formatter: '{b} : {c} ({d}%)',
        },
        series: [
          {
            type: 'pie',
            radius: '70%',
            data: this.data,
          },
        ],
      };

      this.chart.setOption(options);
    },
  },
};
</script>
