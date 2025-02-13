<template>
  <div
    :class="className"
    :style="{height:height,width:width}"
  />
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
      data: {
        'recharge amount': [],
        'withdrawal amount': [],
        names: [],
      },
    };
  },
  mounted() {
    this.handlerRechargeWithdrawal();
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
    handlerRechargeWithdrawal() {
      const list = {
        'recharge amount': [],
        'withdrawal amount': [],
        names: [],
      };
      Dashboard
        .rechargeWithdrawal()
        .then(response => {
          const { data } = response;
          data.forEach(function(item, index) {
            list['recharge amount'].push(item.recharge);
            list['withdrawal amount'].push(item.withdrawal);
            list['names'].push(item.date);
          });
          this.data = list;
          this.initChart();
        });
    },
    initChart() {
      this.chart = echarts.init(this.$el, 'macarons');
      const options = {
        title: {
          text: this.title,
          left: 'left',
          textStyle: {
            color: '#000',
            fontWeight: 'bold',
          },
        },
        tooltip: {
          trigger: 'axis',
          axisPointer: {
            type: 'shadow',
            label: {
              show: true,
            },
          },
        },
        toolbox: {
          show: true,
          feature: {
            magicType: {
              type: ['line', 'bar'],
            },
            saveAsImage: {},
          },
        },
        legend: {
          data: [this.$t('charts.rechargeAmount'), this.$t('charts.withdrawalAmount')],
          itemGap: 5,
          padding: [0, 0, 0, 480],
        },
        xAxis: [
          {
            type: 'category',
            data: this.data.names,
          },
        ],
        yAxis: [
          {
            type: 'value',
            axisLabel: {
              formatter: function(a) {
                a = +a;
                return isFinite(a) ? echarts.format.addCommas(+a / 1000) : '';
              },
            },
          },
        ],

        series: [
          {
            name: this.$t('charts.rechargeAmount'),
            type: 'bar',
            data: this.data['recharge amount'],
          },
          {
            name: this.$t('charts.withdrawalAmount'),
            type: 'bar',
            data: this.data['withdrawal amount'],
          },
        ],
      };

      // {
      //   tooltip: {
      //     trigger: 'item',
      //       formatter: '{a} <br/>{b} : {c} ({d}%)',
      //   },
      //   legend: {
      //     left: 'center',
      //       bottom: '10',
      //       data: ['Industries', 'Technology', 'Forex', 'Gold', 'Forecasts'],
      //   },
      //   calculable: true,
      //     series: [
      //   {
      //     name: 'WEEKLY WRITE ARTICLES',
      //     type: 'pie',
      //     roseType: 'radius',
      //     radius: [15, 95],
      //     center: ['50%', '38%'],
      //     data: [
      //       { value: 320, name: 'Industries' },
      //       { value: 240, name: 'Technology' },
      //       { value: 149, name: 'Forex' },
      //       { value: 100, name: 'Gold' },
      //       { value: 59, name: 'Forecasts' },
      //     ],
      //     animationEasing: 'cubicInOut',
      //     animationDuration: 2600,
      //   },
      // ],
      // }

      this.chart.setOption(options);
    },
  },
};
</script>
