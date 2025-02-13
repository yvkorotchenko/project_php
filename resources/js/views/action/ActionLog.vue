<template>
  <div class="app-container">
    <!-- FILTERS -->
    <!-- <div v-if="handleHavePermission(['query', 'reset', 'select users', 'select actions', 'operation log search'])" class="filter-container"> -->
    <div class="filter-container">
      <!-- Users lists -->
      <PermissionChecker :permissions="['ui action log select users']">.</PermissionChecker>
      <el-select
        v-model="selectedUsers"
        class="filter-item"
        style="width: 177px;"
        :placeholder="$t('placeholders.operationLogSortInUser')"
        clearable
      >
        <el-option v-for="item in users" :key="item.id" :label="item.id" :value="item.id" />
      </el-select>
      <!-- </PermissionChecker> -->
      <!-- Action lists -->
      <!-- <PermissionChecker :permissions="['ui action log select actions']"> -->
      <el-select
        v-model="selectedActionLog"
        class="filter-item"
        style="width: 177px;"
        :placeholder="$t('placeholders.operationLogAllOperations')"
        clearable
      >
        <el-option v-for="item in actionsLog" :key="item.id" :label="language==='en' ? item.en : item.zh" :value="item.en" />
      </el-select>
      <!-- </PermissionChecker> -->
      <!-- Start data -->
      <!-- <PermissionChecker :permissions="['start data']"> -->
      <el-date-picker v-model="selectedStartDate" class="filter-item" type="date" clearable format="yyyy-MM-dd" value-format="yyyy-MM-dd" :placeholder="$t('placeholders.operationLogStartingTime')" />
      <!-- </PermissionChecker> -->
      <!-- End data -->
      <!-- <PermissionChecker :permissions="['end data']"> -->
      <el-date-picker v-model="selectedEndDate" class="filter-item" type="date" clearable format="yyyy-MM-dd" value-format="yyyy-MM-dd" :placeholder="$t('placeholders.operationLogEndTime')" />
      <!-- </PermissionChecker> -->
      <!-- Query -->
      <!-- <PermissionChecker :permissions="['query']"> -->
      <el-button class="filter-item" type="success" plain @click="handlerQuery">{{ $t('btn.query') }}</el-button>
      <!-- </PermissionChecker> -->
      <!-- Reset -->
      <!-- <PermissionChecker :permissions="['reset']"> -->
      <el-button class="filter-item" type="primary" plain @click="handlerReset">{{ $t('btn.reset') }}</el-button>
      <!-- </PermissionChecker> -->
    </div>
    <el-table
      v-loading="loading"
      :data="list"
      style="width: 100%"
      height="650"
      border
      fit
      highlight-current-row
    >
      <el-table-column
        v-for="(itemName, itemLabel) in itemsLabels"
        :key="itemLabel"
        align="center"
        :prop="itemLabel"
        :label="itemName"
      >
        <template slot-scope="scope">
          {{ scope.row[itemLabel] }}
        </template>
      </el-table-column>
    </el-table>
    <div>
      <Pagination
        v-show="total>0"
        :total="total"
        :page.sync="query.page"
        :limit.sync="query.limit"
        :page-sizes="[10, 15, 20, 30, 50, 70, 90, 110]"
        @pagination="getActionLog"
      />
    </div>
  </div>
</template>

<script>
import Pagination from '@/components/Pagination';
import Resource from '@/api/resource';
import PermissionChecker from '@/components/Permissions/index';
import havePermission from '@/utils/permission';

const actionLog = new Resource('action-log');
const listUsers = new Resource('users');
const actionsLists = new Resource('actions');

export default {
  name: 'ActionLog',
  components: {
    Pagination,
    PermissionChecker,
  },
  data() {
    const itemsLabels = {
      user_id: this.$t('labels.userId'),
      action_en: this.$t('labels.actionEN'),
      action_zh: this.$t('labels.actionZH'),
      created_at: this.$t('labels.startDate'),
    };
    return {
      loading: false,
      list: [],
      query: {
        limit: 20,
        page: 1,
        search: {},
      },
      total: 0,
      itemsLabels: itemsLabels,
      selectedUsers: '',
      users: [],
      selectedActionLog: '',
      actionsLog: [],
      selectedStartDate: '',
      selectedEndDate: '',
      search: {},
    };
  },
  computed: {
    language: function() {
      return this.$store.getters.language;
    },
  },
  created() {
    this.getActionLog();
    this.getActionsList();
    this.getAllUsers();
  },
  methods: {
    async getActionLog() {
      const { limit, page } = this.query;
      this.loading = true;
      const { data } = await actionLog.list(this.query);
      this.list = data.data;
      this.list.forEach((item, index) => {
        item['index'] = (page - 1) * limit + index + 1;
        item['created_at'] = item['created_at'].replace(/T|.000000Z$/g, ' ');
      });
      this.total = data.total;
      this.loading = false;
    },
    async getActionsList() {
      const { data } = await actionsLists.list({});
      const tempData = {};
      for (const prop in data) {
        const value = data[prop].split(',');
        tempData[prop] = {
          id: parseInt(prop),
          en: value[0],
          zh: value[1],
        };
      }
      this.actionsLog = tempData;
    },
    async getAllUsers() {
      const { data } = await listUsers.list({});
      this.users = data;
    },
    handlerQuery() {
      if (this.selectedUsers !== '') {
        this.search.userId = this.selectedUsers;
      }
      if (this.selectedActionLog !== '') {
        this.search.action = this.selectedActionLog;
      }
      if (this.selectedStartDate !== '') {
        this.search.dateStart = this.selectedStartDate;
      }
      if (this.selectedEndDate !== '') {
        this.search.dateEnd = this.selectedEndDate;
      }
      //
      this.query.search = this.search;
      //
      this.getActionLog();
    },
    handlerReset() {
      this.selectedUsers = '';
      this.selectedActionLog = '';
      this.selectedStartDate = '';
      this.selectedEndDate = '';
      this.search = {};
      this.getActionLog();
    },
    handleHavePermission(permission) {
      return havePermission(permission);
    },
  },
};
</script>

<style scoped>

</style>
