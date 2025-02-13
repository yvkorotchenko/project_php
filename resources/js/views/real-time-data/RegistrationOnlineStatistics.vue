<template>
  <div class="app-container">
    <h1>{{ $t('titles.RegistrationOnlineStatistics') }}</h1>
    <RealTimeDataChart
      :title="$t('labels.playingOnline')"
      :chart-data="initialData"
      :chart-time-data="timeData"
      :get-new-item="fetchLatestItem"
    />
  </div>
</template>

<script>
import RealTimeDataChart from '../../components/RealTimeDataChart';
import RegistrationStatisticsResource from '@/api/registrationStatistics';

const resource = new RegistrationStatisticsResource();

export default {
  name: 'RegistrationOnlineStatistics',
  components: { RealTimeDataChart: RealTimeDataChart },
  data() {
    return {
      initialData: [],
      timeData: [],
    };
  },
  created() {
    this.fetchStartItems();
  },
  methods: {
    fetchStartItems() {
      const now = new Date();
      const todayEnd = new Date(now.getFullYear(), now.getMonth(), now.getDate(), 23, 59, 59);
      const todayStart = new Date(now.getFullYear(), now.getMonth(), now.getDate(), 0, 0, 0);

      const yesterdayEndDay = new Date();
      yesterdayEndDay.setDate(now.getDate() - 1);
      yesterdayEndDay.setHours(23);
      yesterdayEndDay.setMinutes(59);
      yesterdayEndDay.setSeconds(59);
      const yesterdayStartDay = new Date(yesterdayEndDay.getFullYear(), yesterdayEndDay.getMonth(), yesterdayEndDay.getDate(), 0, 0, 0);

      const oneWeekAgoEndDay = new Date();
      oneWeekAgoEndDay.setDate(now.getDate() - 7);
      oneWeekAgoEndDay.setHours(23);
      oneWeekAgoEndDay.setMinutes(59);
      oneWeekAgoEndDay.setSeconds(59);
      const oneWeekAgoStartDay = new Date(oneWeekAgoEndDay.getFullYear(), oneWeekAgoEndDay.getMonth(), oneWeekAgoEndDay.getDate(), 0, 0, 0);

      const chartData = [];
      let timeData = [];

      Promise.all([
        this.fetchItems(todayStart, todayEnd),
        this.fetchItems(yesterdayStartDay, yesterdayEndDay),
        this.fetchItems(oneWeekAgoStartDay, oneWeekAgoEndDay),
      ])
        .then(responses => {
          responses.forEach(one => {
            const data = Object.keys(one.data).sort().reduce((result, key) => {
              result[key] = one.data[key];
              return result;
            }, {});
            const dateStr = this.truncateDateFormatted(Object.keys(data)[0]);
            chartData.push({ data: Object.values(data), name: this.truncateDateFormatted(dateStr) + ' ' + one.name });
            if (timeData.length === 0) {
              timeData = Object.keys(data).map(one => this.truncateToTimeFromStringDate(one));
            }
          });
          this.initialData = chartData.sort((prev, next) => prev.name > next.name);
          this.timeData = timeData;
        });
    },
    fetchLatestItem() {
      return resource.latest();
    },
    fetchItems(from, to) {
      return resource.list(this.formatDate(from), this.formatDate(to));
    },
    formatDate(date) {
      const mounth = (date.getMonth() + 1).toString();
      const day = date.getDate().toString();
      const hours = date.getHours().toString();
      const minutes = date.getMinutes().toString();
      const seconds = date.getSeconds().toString();
      return `${date.getFullYear()}-${mounth[1] ? mounth : '0' + mounth[0]}-${day[1] ? day : '0' + day[0]} ${hours[1] ? hours : '0' + hours[0]}:${minutes[1] ? minutes : '0' + minutes[0]}:${seconds[1] ? seconds : '0' + seconds[0]}`;
    },
    truncateDateFormatted(dateStr) {
      return dateStr.split(' ')[0];
    },
    truncateToTimeFromStringDate(dateStr) {
      return dateStr.split(' ')[1];
    },
  },
};
</script>
