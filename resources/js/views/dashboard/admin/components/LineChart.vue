<template>
  <div>
    <el-form ref="platforms" :model="platforms" label-width="157px">
      <el-form-item :label="$t('charts.choosePlatform')">
        <el-select v-model="platforms.current" :placeholder="$t('charts.choosePlatform')">
          <el-option
            v-for="item in platforms.lists"
            :key="item.value"
            :label="item.label"
            :value="item.value"
          />
        </el-select>
        <el-button type="success" plain @click="handlerChangePlatform">{{ $t('btn.query') }}</el-button>
      </el-form-item>
    </el-form>
    <div :class="className" :style="{height:height, width:width}" />
  </div>
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
    lineBlue: {
      type: String,
      default: 'Bet amount',
    },
    lineRed: {
      type: String,
      default: 'Game profit and loss',
    },
    width: {
      type: String,
      default: '100%',
    },
    title: {
      type: String,
      default: '',
    },
    height: {
      type: String,
      default: '450px',
    },
    autoResize: {
      type: Boolean,
      default: true,
    },
    // chartData: {
    //   type: Object,
    //   required: true,
    // },
  },
  data() {
    return {
      platforms: {
        current: '',
        lists: [],
      },
      chart: null,
      sidebarElm: null,
      chartData: {
        expectedData: [],
        // expectedData: [100, 120, 161, 134, 105, 160, 165],
        actualData: [],
        // actualData: [120, 82, 91, 154, 162, 140, 145],
      },
      date: [],
    };
  },
  computed: {
    languageNoDate: function() {
      return this.$t('charts.noDate');
    },
    language: function() {
      return this.$store.getters.language;
    },
  },
  watch: {
    chartData: {
      deep: true,
      handler(val) {
        this.setOptions(val);
      },
    },
  },
  mounted() {
    this.handlerAmountPlatformLists();
    this.initChart();
    if (this.autoResize) {
      this.__resizeHandler = debounce(() => {
        if (this.chart) {
          this.chart.resize();
        }
      }, 100);
      window.addEventListener('resize', this.__resizeHandler);
    }

    // Monitor the sidebar changes
    this.sidebarElm = document.getElementsByClassName('sidebar-container')[0];
    this.sidebarElm && this.sidebarElm.addEventListener('transitionend', this.sidebarResizeHandler);
  },
  beforeDestroy() {
    if (!this.chart) {
      return;
    }
    if (this.autoResize) {
      window.removeEventListener('resize', this.__resizeHandler);
    }

    this.sidebarElm && this.sidebarElm.removeEventListener('transitionend', this.sidebarResizeHandler);

    this.chart.dispose();
    this.chart = null;
  },
  methods: {
    handlerChangePlatform() {
      this.handlerPlatformProfitLossPlayerBetting(this.platforms.current);
    },
    handlerAmountPlatformLists() {
      const platforms = [];
      Dashboard
        .betAmountPlatformLists()
        .then(response => {
          const { data } = response;
          data.forEach(function(item, index){
            platforms.push({ value: item.id, label: item.name });
          });
          this.platforms.lists = platforms;
          const id = this.platforms.lists[0].value;
          this.platforms.current = id;
          this.handlerPlatformProfitLossPlayerBetting(id);
        });
    },
    handlerPlatformProfitLossPlayerBetting(id) {
      const betAmount = [];
      const gameProfitAndLoss = [];
      const date = [];
      Dashboard
        .platformProfitLossPlayerBetting(id)
        .then(response => {
          const { data } = response;
          if (data.length > 0) {
            this.chartData.expectedData = [];
            this.chartData.actualData = [];
            this.date = [];
            data.forEach((item, index) => {
              betAmount.push(item.betAmount);
              gameProfitAndLoss.push(item.gameProfitAndLoss);
              date.push(item.date);
            });
            this.chartData.expectedData = gameProfitAndLoss;
            this.chartData.actualData = betAmount;
            this.date = date;
          } else {
            this.chartData.expectedData = [0, 0, 0, 0, 0];
            this.chartData.actualData = [0, 0, 0, 0, 0];
            this.date = [this.languageNoDate, this.languageNoDate, this.languageNoDate, this.languageNoDate, this.languageNoDate];
          }
        });
    },
    sidebarResizeHandler(e) {
      if (e.propertyName === 'width') {
        this.__resizeHandler();
      }
    },
    setOptions({ expectedData, actualData } = {}) {
      this.chart.setOption({
        title: {
          text: this.title,
          textStyle: {
            color: '#000',
            fontWeight: 'bold',
          },
        },
        xAxis: {
          type: 'category',
          boundaryGap: false,
          data: this.date,
          axisLabel: {
            rotate: 90,
          },
        },
        grid: {
          left: 10,
          right: 10,
          bottom: 20,
          top: 45,
          containLabel: true,
        },
        tooltip: {
          trigger: 'axis',
          axisPointer: {
            type: 'cross',
          },
          padding: [5, 10],
        },
        yAxis: {
          axisTick: {
            show: false,
          },
        },
        legend: {
          data: [this.lineBlue, this.lineRed],
        },
        series: [
          {
            name: this.lineRed,
            itemStyle: {
              normal: {
                color: '#FF005A',
                lineStyle: {
                  color: '#FF005A',
                  width: 2,
                },
                areaStyle: {
                  color: 'rgba(208,92,133,0.75)',
                },
              },
            },
            smooth: true,
            type: 'line',
            data: expectedData,
            animationDuration: 2800,
            animationEasing: 'cubicInOut',
          },
          {
            name: this.lineBlue,
            smooth: true,
            type: 'line',
            itemStyle: {
              normal: {
                color: '#3888fa',
                lineStyle: {
                  color: '#3888fa',
                  width: 2,
                },
                areaStyle: {
                  color: 'rgba(74,140,231,0.9)',
                },
              },
            },
            data: actualData,
            animationDuration: 2800,
            animationEasing: 'quadraticOut',
          },
        ],
      });
    },
    initChart() {
      this.chart = echarts.init(this.$el.children[1], 'macarons');
      this.setOptions(this.chartData);
    },
  },
};
</script>
