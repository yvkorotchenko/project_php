<template>
  <div>
    <div class="filter-item">
      <PermissionChecker :permissions="[viewPermission.edit]">
        <el-button v-if="editing === false" type="primary" size="small" plain @click="handlerUpdateLeague">{{ $t('btn.edit') }}</el-button>
      </PermissionChecker>
      <span v-if="editing">
        <el-button type="success" plain size="small" @click="handlerConfirmUpdateLeague">{{ $t('btn.confirm') }}</el-button>
        <el-button type="primary" plain size="small" @click="handlerCancelUpdateLeague">{{ $t('btn.cancel') }}</el-button>
      </span>
    </div>
    <!-- Show days -->
    <div v-if="editing">
      <div class="separate-line" />
      <div class="line-text">
        {{ $t('text.showTheGameWithin') }} <el-input v-model="showDays" class="width-77" maxlength="5" /> {{ $t('text.days') }}
        <span class="vertical-separate" />
        {{ $t('text.AssignJumpGame') }}
        <el-select
          v-model="selectedGame"
          :placeholder="$t('placeholders.selectedGame')"
          @change="handlerChangeSelectGame"
        >
          <el-option
            v-for="item in listGame"
            :key="item.value"
            :label="item.label"
            :value="item.value"
          />
        </el-select>
      </div>
      <div class="separate-line" />
    </div>
    <div class="separate-line">
      <h4 style="margin-bottom: 0;">{{ $t('labels.recommendLeague') }}</h4>
    </div>
    <!-- List ligs -->
    <div v-loading="loading" class="wrapper-league parent-flex">
      <div v-for="item in leagues" :key="item.id" class="item-league">
        <div class="wrapper-input">
          <input
            v-if="editing"
            :id="item.id"
            v-model="checkList"
            :value="item.id"
            type="checkbox"
          >
          <label :for="item.id">{{ item['name_' + lang] }}</label>
        </div>
      </div>
    </div>
    <!-- PAGINATION -->
    <pagination
      v-show="total>0"
      :total="total"
      :page.sync="query.page"
      :limit.sync="query.limit"
      :page-sizes="[10, 15, 20, 30, 50, 70, 90, 100, 150, 200, 300, 400]"
      @pagination="updatePage"
    />
  </div>
</template>

<script>
import Pagination from '@/components/Pagination';
import PermissionChecker from '@/components/Permissions/index.vue';
import havePermission from '@/utils/permission';
import Resource from '@/api/resource';
import viewsPermissions from '@/viewsPermissions.js';

const leagues = new Resource('hot-management');
const updateLeagues = new Resource('hot-management/update');
const platformsGames = new Resource('platforms-games');
const previousLeagueSelection = new Resource('previous-league-selection');

export default {
  name: 'HotManagement',
  components: {
    Pagination,
    PermissionChecker,
  },
  data() {
    return {
      viewPermission: viewsPermissions.hotManagement.permissions.controls,
      loading: false,
      total: 0,
      query: {
        page: 1,
        limit: 20,
      },
      //
      lang: this.$store.getters.language,
      dialogVisible: false,
      leagues: [],
      checkList: [],
      // edit
      editing: false,
      showDays: 30,
      listGame: [],
      selectedGame: [],
      //
      selectedHotManagement: {
        ids: [],
        gameId: 0,
        days: 3,
      },
    };
  },
  created() {
    this.getPlatformsGames();
    this.getLeagues();
  },
  methods: {
    async getLeagues() {
      const { limit, page } = this.query;
      this.loading = true;
      const { data, meta } = await leagues.list(this.query);
      this.leagues = data;
      this.leagues.forEach((item, index) => {
        item['index'] = (page - 1) * limit + index + 1;
      });
      this.total = meta.total;
      this.loading = false;
    },
    async getPlatformsGames() {
      const { data } = await platformsGames.list({ page: 1, limit: 333 });
      const options = [];
      data.forEach((item, index) => {
        options.push({
          value: item.id,
          label: item.name,
        });
      });
      this.listGame = options;
    },
    // get selected game
    getHotManagemetLeague() {
      previousLeagueSelection.list({})
        .then(response => {
          const { data } = response.data;
          // set data
          this.checkList = data.ids;
          this.showDays = data.matchShowDays;
          // reset selected ids
          this.selectedHotManagement.ids = [];
        });
    },
    handlerSetHotManagement(gameId, ids, days) {
      this.selectedHotManagement.days = days;
      this.selectedHotManagement.ids = ids;
      this.selectedHotManagement.gameId = gameId;
    },
    // use have permission
    handleHavePermission(permission) {
      return havePermission(permission);
    },
    updatePage() {
      this.leagues = null;
      this.getLeagues();
    },
    handlerUpdateLeague() {
      this.editing = true;
    },
    handlerCancelUpdateLeague() {
      this.editing = false;
      // reset to default value
      this.checkList = [];
      this.showDays = 30;
      // reset selected ids
      this.selectedHotManagement.ids = [];
      this.selectedGame = [];
    },
    handlerConfirmUpdateLeague() {
      let error = false;
      // game not selected
      if (this.selectedGame.length === 0) {
        // this.$message({
        //   type: 'error',
        //   message: this.$t('messages.noGameSelectedForTransit'),
        //   duration: 1 * 1000,
        // });
        error = true;
      }
      // no leagues selected
      if (this.checkList.length === 0) {
        setTimeout(() => {
          // this.$message({
          //   type: 'error',
          //   message: this.$t('messages.noLeaguesAllocated'),
          //   duration: 1 * 1000,
          // });
        }, 50);
        error = true;
      }
      // day zero or empty
      if (this.showDays === '0' || this.showDays === '') {
        setTimeout(() => {
          // this.$message({
          //   type: 'error',
          //   message: this.$t('messages.theNumberOfDaysShowingNotSpecified'),
          //   duration: 1 * 1000,
          // });
        }, 50);
      }
      if (error === false) {
        this.handlerSetHotManagement(this.selectedGame, this.checkList, this.showDays);
        // save
        updateLeagues
          .update(0, this.selectedHotManagement)
          .then(response => {
            const { data } = response;
            this.$message({
              type: 'success',
              message: data.message,
              duration: 1 * 1000,
            });
          })
          .catch(error => {
            console.log(error);
            // this.$message({
            //   type: 'error',
            //   message: error,
            //   duration: 1 * 1000,
            // });
          })
          .finally(() => {
            // hide editing button
            this.editing = false;
            // reset to default value
            this.checkList = [];
            this.showDays = 30;
            // reset selected ids
            this.selectedHotManagement.ids = [];
            this.selectedGame = [];
          });
      }
    },
    handlerChangeSelectGame() {
      this.getHotManagemetLeague();
    },
  },
};
</script>

<style lang="scss" scoped>
  .vertical-separate {
    margin-right: 107px;
  }
  .controlls {
    padding: 10px 0 0 0;
    text-align: center;
  }
  .line-text {
    font-size: 1.1rem;
    padding: 17px 0 5px 0;
  }
  .width-77 {
    width: 77px;
    input {
      margin: 0 7px;
    }
  }
  .wrapper-table {
    padding: 15px 0;
  }
  .separate-line {
    border-bottom: 1px solid #ccc;
    padding-bottom: 12px;
  }
  .wrapper-league {
    overflow-y: auto;
    margin-top: 20px;
    min-height: 300px;
  }
  .parent-flex {
    display: flex;
    align-items: flex-start;
    width: 100%;
    flex-wrap: wrap;
    justify-content: space-between;
    align-content: flex-start;
  }
  .item-league {
    display: flex;
    width: 25%;
    height: 50px;
  }
  .wrapper-input {
    input {
      margin-right: 7px;
    }
  }
</style>
