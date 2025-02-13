<template>
  <div class="app-container">
    <!-- FILTERS -->
    <div class="filter-container">
      <PermissionChecker :permissions="[viewPermission.selectUsers, viewPermission.selectActions, viewPermission.startData, viewPermission.endData, viewPermission.search]">
        <!-- Users lists -->
        <PermissionChecker :permissions="[viewPermission.selectUsers]">
          <el-select v-model="selected.name" class="filter-item" style="width: 177px;" :placeholder="$t('placeholders.operationLogSortInUser')" clearable>
            <el-option v-for="item in users" :key="item.id" :label="item.name" :value="item.name" />
          </el-select>
        </PermissionChecker>
        <!-- Action lists -->
        <PermissionChecker :permissions="[viewPermission.selectActions]">
          <el-select v-model="selected.action" class="filter-item" style="width: 177px;" :placeholder="$t('placeholders.operationLogAllOperations')" clearable>
            <el-option v-for="item in actions" :key="item.id" :label="language === 'en' ? item.name_en : item.name_zh" :value="item.name_en" />
          </el-select>
        </PermissionChecker>
        <!-- Start data -->
        <PermissionChecker :permissions="[viewPermission.startData]">
          <el-date-picker v-model="selected.start" class="filter-item" type="date" clearable format="yyyy-MM-dd" value-format="yyyy-MM-dd" :placeholder="$t('placeholders.operationLogStartingTime')" />
        </PermissionChecker>
        <!-- End data -->
        <PermissionChecker :permissions="[viewPermission.endData]">
          <el-date-picker v-model="selected.end" class="filter-item" type="date" clearable format="yyyy-MM-dd" value-format="yyyy-MM-dd" :placeholder="$t('placeholders.operationLogEndTime')" />
        </PermissionChecker>
        <!-- Search key -->
        <PermissionChecker :permissions="[viewPermission.search]">
          <el-input v-model="selected.searchParam" :placeholder="$t('placeholders.operationLogFuzzyParameters')" class="filter-item" prefix-icon="el-icon-search" style="width: 23%" />
          <!-- Query -->
          <el-button class="filter-item" type="success" plain @click="handleQuery">{{ $t('btn.query') }}</el-button>
          <!-- Reset -->
          <el-button class="filter-item" type="primary" plain @click="handleReset">{{ $t('btn.reset') }}</el-button>
        </PermissionChecker>
      </PermissionChecker>
    </div>
    <!-- TABLE -->
    <el-table v-loading="loading" :data="list" height="650" border fit highlight-current-row>
      <!-- ID -->
      <el-table-column align="center" :label="$t('table.id')" width="80" prop="id" sortable>
        <template slot-scope="scope">
          <span>{{ scope.row.id }}</span>
        </template>
      </el-table-column>
      <!-- NAME -->
      <el-table-column align="center" :label="$t('table.name')" prop="name" sortable>
        <template slot-scope="scope">
          <span>{{ scope.row.name }}</span>
        </template>
      </el-table-column>
      <!-- ACTION -->
      <el-table-column align="center" :label="$t('table.action')" prop="action" sortable>
        <template slot-scope="scope">
          <span>{{ language === 'en' ? scope.row.action_en : scope.row.action_zh }}</span>
        </template>
      </el-table-column>
      <!-- TYPE REQ -->
      <el-table-column align="center" :label="$t('table.typeRequest')" prop="type" sortable>
        <template slot-scope="scope">
          <span>{{ scope.row.type_req }}</span>
        </template>
      </el-table-column>
      <!-- PATH REQ -->
      <el-table-column align="center" :label="$t('table.pathRequest')" prop="route" sortable>
        <template slot-scope="scope">
          <span>{{ scope.row.path_req }}</span>
        </template>
      </el-table-column>
      <!-- REQ DATA -->
      <el-table-column align="center" :label="$t('table.pathData')" prop="data" sortable>
        <template slot-scope="scope">
          <span>{{ scope.row.path_data }}</span>
        </template>
      </el-table-column>
      <!-- IP ADDRESS -->
      <el-table-column align="center" :label="$t('table.ipAddress')" prop="ip" sortable>
        <template slot-scope="scope">
          <span>{{ scope.row.ip }}</span>
        </template>
      </el-table-column>
      <!-- CREATED DATE -->
      <el-table-column align="center" :label="$t('table.createTime')" prop="date" sortable>
        <template slot-scope="scope">
          <span>{{ scope.row.created }}</span>
        </template>
      </el-table-column>

    </el-table>
    <!-- PAGINATION -->
    <pagination
      v-show="total>0"
      :total="total"
      :page.sync="query.page"
      :limit.sync="query.limit"
      :page-sizes="[10, 15, 20, 30, 50, 70, 90, 110]"
      @pagination="getList"
    />
  </div>
</template>

<script>
import Pagination from '@/components/Pagination';
import UserResource from '@/api/user';
import Resource from '@/api/resource';
import PermissionChecker from '@/components/Permissions/index.vue';
import havePermission from '@/utils/permission';
import viewsPermissions from '@/viewsPermissions.js';
const operationLogResource = new Resource('operation-log');
const userResource = new UserResource();
const actionsLists = new Resource('actions');

export default {
  name: 'OperationLog',
  components: {
    Pagination,
    PermissionChecker,
  },
  data() {
    return {
      viewPermission: viewsPermissions.operationLog.permissions.controls,
      total: 0,
      list: null,
      loading: true,
      query: {
        page: 1,
        limit: 15,
      },
      users: [],
      actions: [],
      selected: {
        name: '',
        action: '',
        start: '',
        end: '',
        search: '',
      },
      filters: [],
    };
  },
  computed: {
    language: function() {
      return this.$store.getters.language;
    },
  },
  created() {
    this.getUserList();
    this.getList();
    actionsLists.list().then(response => {
      const data = Object.keys(response.data).map(key => ({ id: key, name: response.data[key] }));
      const tempData = {};
      for (const prop in data) {
        const value = data[prop].name.split(',');
        tempData[prop] = {
          id: parseInt(prop),
          name_en: value[0],
          name_zh: value[1],
        };
      }
      this.actions = tempData;
    });
  },
  methods: {
    async getList() {
      const { limit, page } = this.query;
      this.loading = true;
      const { data, meta } = await operationLogResource.list(this.query);
      this.list = data;
      this.list.forEach((element, index) => {
        element['index'] = (page - 1) * limit + index + 1;
      });
      this.total = meta.total;
      this.loading = false;
    },
    // get list users name
    async getUserList() {
      const { data } = await userResource.list({});
      // if (data.isArray()) {
      data.forEach((user, index) => {
        this.users.push({ id: user.id, name: user.name });
      });
      // }
    },
    handleFilter() {
      this.query.page = 1;
      this.getList();
    },
    // use have permission
    handleHavePermission(permission) {
      return havePermission(permission);
    },
    // clear all filters
    handleReset() {
      this.selected.name = '';
      this.selected.action = '';
      this.selected.start = '';
      this.selected.end = '';
      this.selected.search = '';
      // clear query
      delete this.query.name;
      delete this.query.action;
      delete this.query.start;
      delete this.query.end;
      delete this.query.search;
      // get list
      this.getList();
    },
    handleAddQuery(list = []) {
      list.forEach((option, index) => {
        this.query[option.key] = option.value;
      });
    },
    handleAddItemSelectedFilters(key, value) {
      return { 'key': key, 'value': value };
    },
    handleAddItemQuery(key, value) {
      this.query[key] = value;
    },
    handleDeleteItemQuery(key) {
      delete this.query[key];
    },
    handleQuery() {
      this.handleSetQuery();
      // get result list
      this.getList();
    },
    handleSetQuery() {
      const selected = this.selected;
      for (const key in selected) {
        if (selected[key] !== '') {
          this.query[key] = selected[key];
        }
      }
    },
  },
};
</script>

<style scoped>

</style>
