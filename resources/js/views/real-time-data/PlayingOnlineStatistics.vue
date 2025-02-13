<template>
  <div class="app-container">
    <h1>{{ $t('titles.playingOnlineStatistics') }}</h1>
    <RealTimeDataChart
      :title="$t('labels.playingOnline')"
      :chart-data="initialData"
      :chart-time-data="timeData"
      :get-new-item="fetchLatestItem"
    />
    <el-tag type="info">
      Total Online: {{ countPlayersTotalOnline }}
    </el-tag>
    <span v-for="(count, deviceName) in countPlayersByDevices" :key="deviceName" class="devices-info">
      <el-tag type="info">
        {{ deviceName + " : " + count }}
      </el-tag>
    </span>
    <div class="tabs-wrp">
      <el-tabs type="border-card" @tab-click="onTabChange">
        <el-tab-pane v-for="game in games" :key="game.id" :label="game.name">
          <el-table
            v-if="game.id === currentGameId && currentGameUsersInfo.length > 0"
            :data="currentGameUsersInfo"
            style="width: 100%"
          >
            <el-table-column
              v-for="(labelText, fieldName) in languageUserTableInfoLabels"
              :key="fieldName"
              :prop="fieldName"
              :label="labelText"
            />
          </el-table>
        </el-tab-pane>
      </el-tabs>
    </div>
  </div>
</template>

<script>
import RealTimeDataChart from '../../components/RealTimeDataChart';
import PlayersOnlineStatistsicsResource from '../../api/PlayersOnlineStatistsics';
import GameResource from '../../api/game';

const resource = new PlayersOnlineStatistsicsResource();
const gameResource = new GameResource();

export default {
  name: 'PlayingOnlineStatistics',
  components: { RealTimeDataChart: RealTimeDataChart },
  data() {
    return {
      initialData: [],
      timeData: [],
      countPlayersByDevices: {},
      countPlayersTotalOnline: 0,
      games: [],
      currentGameId: null,
      currentGameUsersInfo: [],
    };
  },
  computed: {
    languageUserTableInfoLabels: function() {
      return {
        uid: this.$t('table.id'),
        nickname: this.$t('table.nickname'),
        registrationTime: this.$t('table.registrationTime'),
        lastLoginTime: this.$t('table.lastLoginTime'),
        registrationChannel: this.$t('table.registration'),
        roomType: this.$t('table.roomType'),
        loginIp: this.$t('table.loginIp'),
        totalRecharge: this.$t('table.totalRecharge'),
        totalWithdraw: this.$t('table.totalWithdraw'),
        accountStatus: this.$t('table.accountStatus'),
        coins: this.$t('table.coins'),
      };
    },
  },
  created() {
    this.fetchStartItems();
    this.fetchGameList();
    this.setCurrentGameId('0');
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
          responses.forEach(responseData => {
            responseData.userOnlineInGameByDateData.forEach(one => {
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
          });
          this.initialData = chartData.sort((prev, next) => prev.name > next.name);
          this.timeData = timeData;
        });
      resource.usersOnlineCountByDevices().then(response => {
        this.countPlayersByDevices = response;
        this.countPlayersTotalOnline = Object.values(response).reduce((result, current) => result + current, 0);
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
    setCurrentGameId(gameId) {
      resource.userInfoByGame(gameId).then(response => {
        this.currentGameId = parseInt(gameId);
        this.currentGameUsersInfo = response.data;
      });
    },
    fetchGameList() {
      gameResource.list().then(response => {
        const gamesList = [{ id: 0, name: 'Lobby' }];
        gamesList.push(...response.data);
        this.games = gamesList;
      });
    },
    onTabChange(tab) {
      this.setCurrentGameId(tab.index);
    },
  },
};
</script>

<style scoped>
.devices-info {margin-right: 4px;}
.tabs-wrp {margin-top: 15px;}
</style>
