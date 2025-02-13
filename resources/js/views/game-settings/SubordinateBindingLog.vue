<template>
  <div class="app-container">
    <!-- TABLE -->
    <el-table ref="multipleTable" v-loading="loading" :data="list" border fit highlight-current-row style="width: 100%" height="650">
      <!-- -ID- -->
      <el-table-column align="center" :label="$t('table.userId')">
        <template slot-scope="scope">
          <span>{{ scope.row.userId }}</span>
        </template>
      </el-table-column>
      <!-- -USER_NAME- -->
      <el-table-column align="center" :label="$t('table.upperId')">
        <template slot-scope="scope">
          <span>{{ scope.row.upperId }}</span>
        </template>
      </el-table-column>
      <!-- -USER_NIK- -->
      <el-table-column align="center" :label="$t('table.operator')">
        <template slot-scope="scope">
          <span>{{ scope.row.operator }}</span>
        </template>
      </el-table-column>
      <!--  -->
      <el-table-column align="center" :label="$t('table.createTime')">
        <template slot-scope="scope">
          <span>{{ scope.row.createTime }}</span>
        </template>
      </el-table-column>
    </el-table>
    <!-- PAGINATION -->
    <pagination v-show="total>0" :total="total" :page.sync="query.page" :limit.sync="query.limit" @pagination="getList" />
  </div>
</template>

<script>
import Pagination from '@/components/Pagination'; // Secondary package based on el-pagination
// import Resource from '@/api/resource';
// Waves directive
// import waves from '@/directive/waves';
// Permission directive
// import permission from '@/directive/permission';
// Permission checking
// import checkPermission from '@/utils/permission';

// const userResource = new UserResource();
// const permissionResource = new Resource('permissions');
// const roles = new Resource('roles');

export default {
  name: 'SubordinateBindingLog',
  components: { Pagination },
  data() {
    return {
      loading: false,
      // selected user to deleted
      selectedToDelete: [],
      // weher slected all list
      selectedAllList: false,
      list: null,
      total: 0,
      query: {
        page: 1,
        limit: 15,
      },
    };
  },
  // CREATED
  created() {
    // get list user
    this.getList();
  },
  // METHODS
  methods: {
    async getList() {
      this.list = [
        {
          userId: '80001061',
          upperId: '80001003',
          operator: 'huster',
          createTime: '2020-10-09 13:22:07',
        },
        {
          userId: '80001061',
          upperId: '80001003',
          operator: 'huster',
          createTime: '2020-10-09 13:22:07',
        },
        {
          userId: '80001061',
          upperId: '80001003',
          operator: 'huster',
          createTime: '2020-10-09 13:22:07',
        },
        {
          userId: '80001061',
          upperId: '80001003',
          operator: 'huster',
          createTime: '2020-10-09 13:22:07',
        },
        {
          userId: '80001061',
          upperId: '80001003',
          operator: 'huster',
          createTime: '2020-10-09 13:22:07',
        },
      ];
    },
  },
};
</script>

<style lang="scss">
.button-group {
  background-color: #009688;
  border-color: #009688;

  &:hover, &:focus {
    background-color: #00bfad;
    border-color: #00d4c0;
  }

  &:active {
    background-color: #008275;
    border-color: #008275;
  }
}
.list-chackbox-group {
  margin-bottom: 2.1rem;
}
.roles-list {
  .is-checked {
    span {
      color: #fff !important;
      background-color: #5FB878 !important;
      border-color: #5fb878 !important;
      -webkit-box-shadow: -1px 0 0 0 #4e8d5f;
      box-shadow: -1px 0 0 0 #4e8d5f;
    }
  }
}
.wrapper-tree {
  height: 500px;
  overflow: auto;
}
</style>
