<template>
  <div class="app-container">
    <!-- FILTERS -->
    <div class="filter-container">
      <PermissionChecker :permissions="[viewPermission.create]">
        <div>
          <!-- Add -->
          <el-button class="filter-item" type="primary" @click="onAccountForm">{{ $t('btn.add') }}</el-button>
        </div>
      </PermissionChecker>
      <PermissionChecker :permissions="[viewPermission.search]">
        <div class="box-line">
          <!-- USER SELECT -->
          <el-select v-model="query.lastOperationUserId" class="filter-item" style="width: 233px;" :placeholder="$t('placeholders.acountSuspensionUser')">
            <el-option v-for="item in users" :key="item.id" :label="item.name | uppercaseFirst" :value="item.id" />
          </el-select>
          <!-- USER ID -->
          <el-input v-model="query.accountId" :placeholder="$t('placeholders.userId')" class="filter-item" style="width: 200px" />
          <!-- START DATE -->
          <el-date-picker
            v-model="query.dateStart"
            class="filter-item"
            type="datetime"
            :placeholder="$t('placeholders.accountSuspensionStartTime')"
            format="yyyy-MM-dd hh:mm:ss"
          />
          <!-- END DATE -->
          <el-date-picker
            v-model="query.dateEnd"
            class="filter-item"
            type="datetime"
            :placeholder="$t('placeholders.accountSuspensionEndTime')"
            format="yyyy-MM-dd hh:mm:ss"
          />
          <el-button class="filter-item" type="success" @click="getSuspension">{{ $t('btn.query') }}</el-button>
          <el-button class="filter-item" type="primary" @click="resetFilter">{{ $t('btn.reset') }}</el-button>
        </div>
      </PermissionChecker>
    </div>
    <!-- TABLE -->
    <el-table v-loading="loading" :data="suspension" height="650" border fit highlight-current-row>
      <!-- ID -->
      <el-table-column align="center" :label="$t('table.id')" width="80" prop="id" sortable>
        <template slot-scope="scope">
          <span>{{ scope.row.id }}</span>
        </template>
      </el-table-column>
      <!--  -->
      <el-table-column align="center" :label="$t('table.accountId')" prop="name" sortable>
        <template slot-scope="scope">
          <span>{{ scope.row.account_id }}</span>
        </template>
      </el-table-column>
      <!--  -->
      <el-table-column align="center" :label="$t('table.bannedDays')" prop="action" sortable>
        <template slot-scope="scope">
          <span>{{ scope.row.banned_days }}</span>
        </template>
      </el-table-column>
      <!--  -->
      <el-table-column align="center" :label="$t('table.reasonForSuspension')">
        <template slot-scope="scope">
          <span>{{ (getReasonNameById(scope.row.reason_for_suspension_id)!==undefined)?getReasonNameById(scope.row.reason_for_suspension_id).name:"Не найден" }}</span>
        </template>
      </el-table-column>
      <!--  -->
      <el-table-column align="center" :label="$t('table.suspendedNotes')" prop="route">
        <template slot-scope="scope">
          <span>{{ scope.row.suspension_notes }}</span>
        </template>
      </el-table-column>
      <!--  -->
      <el-table-column align="center" :label="$t('table.createTime')" prop="data">
        <template slot-scope="scope">
          <span>{{ scope.row.created_at }}</span>
        </template>
      </el-table-column>
      <!--  -->
      <el-table-column align="center" :label="$t('table.updateTime')" prop="ip">
        <template slot-scope="scope">
          <span>{{ scope.row.unbloking_time }}</span>
        </template>
      </el-table-column>
      <!--  -->
      <el-table-column align="center" :label="$t('table.status')" prop="date" sortable>
        <template slot-scope="scope">
          <span>{{ (getStatusNameById(scope.row.status_id)!==undefined)? ((language === 'en')? getStatusNameById(scope.row.status_id).name_en : getStatusNameById(scope.row.status_id).name_zh ) :"Не найден" }}</span>
        </template>
      </el-table-column>
      <!--  -->
      <el-table-column align="center" :label="$t('table.lastOperator')" prop="date" sortable>
        <template slot-scope="scope">
          <span>{{ (getUsersNameById(scope.row.last_operation_user_id)!==undefined)?getUsersNameById(scope.row.last_operation_user_id).name:"Не найден" }}</span>
        </template>
      </el-table-column>
      <!--  -->
      <PermissionChecker :permissions="[viewPermission.delete]">
        <el-table-column align="center" :label="$t('table.action')" prop="date" sortable>
          <template slot-scope="scope">
            <el-button
              lay-event="off"
              :class="[ scope.row.status_id === 1 ? 'el-button el-button--warning' : 'el-button el-button--danger']"
              @click="rowAction(scope.row.id,scope.row.status_id)"
            >{{ ( scope.row.status_id === 1 ) ? $t('btn.unblock') : $t('btn.delete') }}</el-button>
          </template>
        </el-table-column>
      </PermissionChecker>
    </el-table>
    <!-- PAGINATION -->
    <pagination v-show="total>0" :total="total" :page.sync="query.page" :limit.sync="query.limit" @pagination="getSuspension" />
    <!-- DIALOG -->
    <el-dialog :title="$t('dialog.accountSuspension')" :visible.sync="accountVisibleForm" width="57%">
      <!-- PRE LOADER -->
      <div class="form-container">
        <!-- FORM -->
        <el-form label-position="end" label-width="200px">
          <!-- -->
          <el-form-item :label="$t('labels.playerId')" label-width="200px">
            <el-input v-model="newSuspension.account_id" :placeholder="$t('placeholders.playerIds')" />
          </el-form-item>
          <!-- SELECT -->
          <el-form-item :label="$t('table.reasonForSuspension')" label-width="200px">
            <el-select v-model="newSuspension.reason_for_suspension_id" :placeholder="$t('placeholders.reasonForSuspension')" style="width: 220px;">
              <el-option v-for="item in reason" :key="item.id" :label=" (language === 'en') ? item.name_en : item.name_zh | uppercaseFirst" :value="item.id" />
            </el-select>
          </el-form-item>
          <!-- -->
          <el-form-item :label="$t('table.bannedDays')" label-width="200px">
            <el-input v-model="newSuspension.banned_days" :placeholder="$t('placeholders.bannedDays')" style="width: 220px" />
          </el-form-item>
          <!-- -->
          <el-form-item :label="$t('table.suspendedNotes')" label-width="200px">
            <el-input v-model="newSuspension.suspension_notes" type="textarea" :placeholder="$t('placeholders.pleaseEnterContent')" />
          </el-form-item>
        </el-form>
        <!-- FOOTER -->
        <div slot="footer" class="dialog-footer" align="left" style="margin-left: 130px;">
          <el-button type="success" icon="el-icon-plus" @click="addSuspension">
            {{ $t('btn.confirm') }}
          </el-button>
        </div>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import Pagination from '@/components/Pagination';
import AccountSuspensions from '@/api/accountSuspensions';
import AccountSuspensionStatuses from '@/api/accountSuspensionStatuses';
import ReasonForSuspensions from '@/api/reasonForSuspensions';
import User from '@/api/user';
import PermissionChecker from '@/components/Permissions/index.vue';
import viewsPermissions from '@/viewsPermissions.js';
const accountSuspensionsResource = new AccountSuspensions();
const accountSuspensionStatusesResource = new AccountSuspensionStatuses();
const reasonForSuspensionsResource = new ReasonForSuspensions();
const userResource = new User();

export default {
  name: 'OperationLog',
  components: {
    Pagination,
    PermissionChecker,
  },
  data() {
    return {
      viewPermission: viewsPermissions.accountSuspension.permissions.controls,
      total: 0,
      loading: true,
      accountVisibleForm: false,
      status: [],
      reason: [],
      suspension: [],
      users: [],
      query: {
        page: 1,
        limit: 10,
        lastOperationUserId: '',
        accountId: '',
        dateStart: '',
        dateEnd: '',
      },
      newSuspension: {
        account_id: '',
        reason_for_suspension_id: '',
        banned_days: '',
        suspension_notes: '',
        status_id: 1,
      },
    };
  },
  computed: {
    language: function() {
      return this.$store.getters.language;
    },
  },
  created() {
    this.getStatus();
    this.getReason();
    this.getSuspension();
    this.getUsers();
  },
  methods: {
    async getStatus() {
      const status = await accountSuspensionStatusesResource.list();
      this.status = status;
    },
    async getReason() {
      const reason = await reasonForSuspensionsResource.list();
      this.reason = reason;
    },
    async getSuspension() {
      const query = {
        page: this.query.page,
        limit: this.query.limit,
      };
      if (this.query.lastOperationUserId !== '') {
        query.lastOperationUserId = this.query.lastOperationUserId;
      }
      if (this.query.accountId !== '') {
        query.accountId = this.query.accountId;
      }
      if (this.query.dateStart !== '') {
        query.dateStart = this.query.dateStart;
      }
      if (this.query.dateEnd !== '') {
        query.dateEnd = this.query.dateEnd;
      }
      const suspension = await accountSuspensionsResource.list(query);
      this.total = suspension.total;
      this.suspension = suspension.data;
      this.loading = false;
    },
    async addSuspension() {
      const msg = await accountSuspensionsResource.store(this.newSuspension);
      if (msg.message !== '') {
        this.offAccountForm();
        this.resetFormNewBanned();
      }
      this.getSuspension();
    },
    async getUsers() {
      const users = await userResource.list();
      this.users = users.data;
    },
    async rowAction(id, status) {
      if (status === 1) {
        const updateStatus = { 'status_id': 3 };
        const msg = await accountSuspensionsResource.update(id, updateStatus);
        this.getSuspension();
        if (msg.message !== '') {
          this.$message({
            type: 'success',
            message: msg.message,
            duration: 1 * 1000,
          });
          this.offAccountForm();
          this.resetFormNewBanned();
        } else {
          // this.$message({
          //   type: 'error',
          //   message: msg.error,
          //   duration: 1 * 1000,
          // });
        }
      } else {
        const msg = await accountSuspensionsResource.destroy(id);
        this.getSuspension();
        if (msg.message !== '') {
          this.$message({
            type: 'success',
            message: msg.message,
            duration: 1 * 1000,
          });
          this.offAccountForm();
          this.resetFormNewBanned();
        } else {
          // this.$message({
          //   type: 'error',
          //   message: msg.error,
          //   duration: 1 * 1000,
          // });
        }
      }
    },
    getStatusNameById(id) {
      if (Object.keys(this.status).length !== 0) {
        return this.status.find((item) => item.id === id);
      }
    },
    getReasonNameById(id) {
      if (Object.keys(this.reason).length !== 0) {
        return this.reason.find((item) => item.id === id);
      }
    },
    getUsersNameById(id) {
      if (Object.keys(this.users).length !== 0) {
        return this.users.find((item) => item.id === id);
      }
    },
    resetFilter() {
      this.query = {
        lastOperationUserId: '',
        accountId: '',
        dateStart: '',
        dateEnd: '',
      };
      this.getSuspension();
    },
    getAccountVisibleForm() {
      return this.accountVisibleForm;
    },
    dateEnd(val) {
      this.accountVisibleForm = val;
    },
    onAccountForm() {
      this.accountVisibleForm = true;
    },
    offAccountForm() {
      this.accountVisibleForm = false;
    },
    resetFormNewBanned(){
      this.newSuspension = {
        account_id: '',
        reason_for_suspension_id: '',
        banned_days: '',
        suspension_notes: '',
        status_id: 1,
      };
    },
  },
};
</script>

<style lang="scss" scoped>
.app-container {
  padding: 10px;
}
.box-line {
  border: 1px solid rgb(246, 246, 246);
  border-width: 1px 0;
  padding: 21px 10px 10px 10px;
  margin: 10px 0;
}
input::placeholder{
  color: #858585 !important;
}
.el-form--label-left .el-form-item__label {
    text-align: end;
}
</style>
