<template>
  <div className="app-container">
    <div class="filter-item" style="padding-bottom: 14px;">
      <el-button
        type="primary"
        class="filter-item"
        @click="dialogFormVisible = true;"
      >
        {{ $t('btn.newMail') }}
      </el-button>
      <el-button
        type="primary"
        class="filter-item"
        @click="deleteSelectIds()"
      >
        {{ $t('btn.deleteMail') }}
      </el-button>
    </div>
    <!-- LIST ITEMS -->
    <div>
      <!-- SELECT ALL TYPES -->
      <el-select
        v-model="query.type"
        class="filter-item mass-mailing-list-filter-item right_separate"
        :placeholder="$t('placeholders.allTypes')"
        clearable
      >
        <el-option
          v-for="item in typeList"
          :key="item.id"
          :label="item.name"
          :value="item.id"
        />
      </el-select>
      <!-- SELECT STATUS -->
      <el-select
        v-model="query.status"
        class="filter-item mass-mailing-list-filter-item right_separate"
        :placeholder="$t('placeholders.viewStatus')"
        clearable
      >
        <el-option
          v-for="item in status"
          :key="item.id"
          :label="item.name"
          :value="item.id"
        />
      </el-select>
      <!-- INPUT ACCOUNT NUMBER -->
      <el-input
        v-model="query.uid"
        :placeholder="$t('placeholders.accountNumber')"
        class="filter-item mass-mailing-list-filter-item static_width_input right_separate"
      />
      <!-- INPUT SEARCH IN FUZZY CONDITION TITLE -->
      <el-input
        v-model="query.fuzzy"
        :placeholder="$t('placeholders.blurrySearch')"
        class="filter-item mass-mailing-list-filter-item static_width_input right_separate"
      />
      <!-- INPUT QUERY START TIME -->
      <el-date-picker
        v-model="query.from"
        class="filter-item mass-mailing-list-filter-item static_width_input right_separate"
        type="datetime"
        :placeholder="$t('placeholders.queryStartTime')"
        format="yyyy-MM-dd hh:mm:ss"
        value-format="yyyy-MM-dd hh:mm:ss"
      />
      <!-- INPUT QUERY END TIME -->
      <el-date-picker
        v-model="query.to"
        class="filter-item mass-mailing-list-filter-item static_width_input right_separate"
        type="datetime"
        :placeholder="$t('placeholders.queryEndTime')"
        format="yyyy-MM-dd hh:mm:ss"
        value-format="yyyy-MM-dd hh:mm:ss"
      />
      <!-- BUTTON QUERY -->
      <el-button
        type="primary"
        class="filter-item"
        @click="getMailList()"
      >
        {{ $t('btn.query') }}
      </el-button>
      <!-- BUTTON RESET -->
      <el-button
        type="primary"
        class="filter-item"
        @click="resetQuery()"
      >
        {{ $t('btn.reset') }}
      </el-button>
    </div>
    <!-- DIALOG ADD NEW MAIL -->
    <el-dialog :title="$t('titles.groupMail')" :visible.sync="dialogFormVisible">
      <el-form ref="formNewMail" :model="formNewMail">
        <!-- Login section -->
        <el-row :gutter="20">
          <el-col :span="4">
            <h3>{{ $t('labels.loginSection') }}</h3>
          </el-col>
          <el-col :span="10">
            <el-form-item
              prop="loginStartTime"
              :rules="{required: true, message: $t('placeholders.lastLoginStartTime'), trigger: 'blur'}"
            >
              <el-date-picker
                v-model="formNewMail.loginStartTime"
                class="filter-item mass-mailing-list-filter-item"
                type="datetime"
                :placeholder="$t('placeholders.lastLoginStartTime')"
                format="yyyy-MM-dd hh:mm:ss"
                value-format="yyyy-MM-dd hh:mm:ss"
              />
            </el-form-item>
          </el-col>
          <el-col :span="10">
            <el-form-item
              prop="loginEndTime"
              :rules="{required: true, message: $t('placeholders.lastLoginEndTime'), trigger: 'blur'}"
            >
              <el-date-picker
                v-model="formNewMail.loginEndTime"
                class="filter-item mass-mailing-list-filter-item"
                type="datetime"
                :placeholder="$t('placeholders.lastLoginEndTime')"
                format="yyyy-MM-dd hh:mm:ss"
                value-format="yyyy-MM-dd hh:mm:ss"
              />
            </el-form-item>
          </el-col>
        </el-row>
        <!-- Recharge interval -->
        <el-row :gutter="20">
          <el-col :span="4">
            <h3>{{ $t('labels.rechargeInterval') }}</h3>
          </el-col>
          <el-col :span="10">
            <el-form-item
              prop="maxRecharge"
              :rules="{required: true, message: $t('placeholders.pleaseEnterMinimumRechargeAmount'), trigger: 'blur'}"
            >
              <el-input-number v-model="formNewMail.maxRecharge" autocomplete="off" :placeholder="$t('placeholders.pleaseEnterMinimumRechargeAmount')" :min="0" />
            </el-form-item>
          </el-col>
          <el-col :span="10">
            <el-form-item
              prop="minRecharge"
              :rules="{required: true, message: $t('placeholders.pleaseEnterMaximumRechargeAmount'), trigger: 'blur'}"
            >
              <el-input-number v-model="formNewMail.minRecharge" autocomplete="off" :placeholder="$t('placeholders.pleaseEnterMaximumRechargeAmount')" :min="0" />
            </el-form-item>
          </el-col>
        </el-row>
        <!-- Chinese title -->
        <el-form-item
          :label="$t('labels.chineseTitle')"
          :label-width="formLabelWidth"
          prop="titleLocal"
          :rules="{required: true, message: $t('placeholders.pleaseEnterTitle'), trigger: 'blur'}"
        >
          <el-input v-model="formNewMail.titleLocal" autocomplete="off" :placeholder="$t('placeholders.pleaseEnterTitle')" />
        </el-form-item>
        <!-- Chinese content -->
        <el-form-item
          :label="$t('labels.chineseContent')"
          :label-width="formLabelWidth"
          prop="contentLocal"
          :rules="{required: true, message: $t('placeholders.pleaseEnterContent'), trigger: 'blur'}"
        >
          <el-input v-model="formNewMail.contentLocal" type="textarea" :rows="2" :placeholder="$t('placeholders.pleaseEnterContent')" />
        </el-form-item>
        <!-- English title -->
        <el-form-item
          :label="$t('labels.englishTitle')"
          :label-width="formLabelWidth"
          prop="title"
          :rules="{required: true, message: $t('placeholders.pleaseEnterTitle'), trigger: 'blur'}"
        >
          <el-input v-model="formNewMail.title" autocomplete="off" :placeholder="$t('placeholders.pleaseEnterTitle')" />
        </el-form-item>
        <!-- English content -->
        <el-form-item
          :label="$t('labels.englishContent')"
          :label-width="formLabelWidth"
          prop="content"
          :rules="{required: true, message: $t('placeholders.pleaseEnterContent'), trigger: 'blur'}"
        >
          <el-input v-model="formNewMail.content" type="textarea" :rows="2" :placeholder="$t('placeholders.pleaseEnterContent')" />
        </el-form-item>
        <!-- Whether to top -->
        <el-form-item
          :label="$t('labels.whetherToTop')"
          :label-width="formLabelWidth"
        >
          <el-radio v-model="formNewMail.onTop" :label="true">{{ $t('labels.yes') }}</el-radio>
          <el-radio v-model="formNewMail.onTop" :label="false">{{ $t('labels.no') }}</el-radio>
        </el-form-item>
        <!-- Gold Coin -->
        <el-form-item
          :label="$t('labels.goldCoin')"
          :label-width="formLabelWidth"
        >
          <el-input-number v-model="formNewMail.goldCoin" :min="0" :placeholder="$t('placeholders.pleaseEnterGoldCoins')" />
        </el-form-item>
        <!-- Withdrawal Standard+ -->
        <el-form-item
          v-if="formNewMail.goldCoin > 0"
          :label="$t('labels.withdrawalStandardPlus')"
          :label-width="formLabelWidth"
        >
          <el-input-number v-model="formNewMail.withdrawalStandard" :min="0" :placeholder="$t('placeholders.pleaseEnterAddedValue')" />
        </el-form-item>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button type="primary" @click="addNewMailList">{{ $t('btn.confirm') }}</el-button>
      </span>
    </el-dialog>
    <!-- CONTROL BUTTON IN TOP -->
    <el-row>
      <el-col :span="3" class="wrap-button">
        <!-- BUTTON NEW MAIL -->
      </el-col>
      <el-col :span="3" class="wrap-button">
        <!-- BUTTON DELETE SELECT IDS -->
      </el-col>
    </el-row>
    <!-- Table -->
    <el-row>
      <!-- table top row icon -->
      <div id="wrap-table">
        <div class="source">
          <div align="right" class="wrap-icon">
            <i class="item-icon">
              <el-dropdown>
                <span class="el-dropdown-link">
                  <i class="el-icon-folder-opened" />
                </span>
                <el-dropdown-menu slot="dropdown">
                  <el-dropdown-item @click.native="getExcelFile">{{ $t('labels.downloadExcel') }}</el-dropdown-item>
                  <el-dropdown-item @click.native="getCSVFile">{{ $t('labels.downloadCSV') }}</el-dropdown-item>
                </el-dropdown-menu>
              </el-dropdown>
            </i>
            <i class="item-icon">
              <i v-print="printObj" class="el-icon-printer" />
            </i>
          </div>
        </div>
      </div>
      <!-- table div -->
      <div>
        <div id="soTest">
          <div class="soflex1">
            <!-- CheckBox -->
            <div>
              <div>
                <div class="table-title">
                  <el-checkbox v-model="toggleAllSelect" @change="chengeToggleAllSelect()" />
                </div>
              </div>
              <div v-for="item in list" :key="item.userMailId">
                <el-checkbox v-model="item.checked" />
              </div>
            </div>
            <!-- playerId -->
            <div>
              <div><div class="table-title">{{ $t('labels.playerId') }}</div></div>
              <div v-for="item in list" :key="item.userMailId">{{ item.uid }}</div>
            </div>
            <!-- title -->
            <div>
              <div><div class="table-title">{{ $t('labels.title') }}</div></div>
              <div v-for="item in list" :key="item.userMailId">{{ item.title }}</div>
            </div>
            <!-- content -->
            <div>
              <div><div class="table-title">{{ $t('labels.content') }}</div></div>
              <div v-for="item in list" :key="item.userMailId">{{ item.content }}</div>
            </div>
            <!-- goldCoin -->
            <div>
              <div><div class="table-title">{{ $t('labels.goldCoin') }}</div></div>
              <div v-for="item in list" :key="item.userMailId">{{ item.goldCoin }}</div>
            </div>
            <!-- viewStatus -->
            <div>
              <div><div class="table-title">{{ $t('labels.viewStatus') }}</div></div>
              <div v-for="item in list" :key="item.userMailId">{{ item.status ? $t('labels.yes') : $t('labels.no') }}</div>
            </div>
            <!-- receivedStatus -->
            <div>
              <div><div class="table-title">{{ $t('labels.receivedStatus') }}</div></div>
              <div v-for="item in list" :key="item.userMailId">{{ item.receivedStatus ? $t('labels.yes') : $t('labels.no') }}</div>
            </div>
            <!-- withdrawalStatus -->
            <div>
              <div><div class="table-title">{{ $t('labels.withdrawalStandardPlus') }}</div></div>
              <div v-for="item in list" :key="item.userMailId">{{ item.goldCoin }}</div>
            </div>
            <!-- operator -->
            <div>
              <div><div class="table-title">{{ $t('labels.operator') }}</div></div>
              <div v-for="item in list" :key="item.userMailId">{{ item.operatorId }}</div>
            </div>
            <!-- creationTime -->
            <div>
              <div><div class="table-title">{{ $t('labels.creationTime') }}</div></div>
              <div v-for="item in list" :key="item.userMailId">{{ item.created }}</div>
            </div>
          </div>
        </div>
      </div>
      <!-- PAGINATION -->
      <pagination v-show="total>0" :total="total" :page.sync="query.page" :limit.sync="query.size" @pagination="getMailList" />
    </el-row>
  </div>
</template>

<script>
import Pagination from '@/components/Pagination';
import Resource from '@/api/resource';
const operatorsResource = new Resource('get-all-operators');
const mailingListResource = new Resource('mailing-list');
const massMailingListResource = new Resource('mass-mailing-list');

export default {
  name: 'MassMailingList',
  components: { Pagination },
  data() {
    return {
      dialogFormVisible: false,
      formLabelWidth: '100px',
      formNewMail: {
        content: '',
        contentLocal: '',
        goldCoin: '',
        loginEndTime: '',
        loginStartTime: '',
        maxRecharge: '',
        minRecharge: '',
        onTop: true,
        operatorId: '',
        operatorName: '',
        title: '',
        titleLocal: '',
        withdrawalStandard: '',
      },
      query: {
        page: 1,
        size: 10,
        type: '',
        operatorId: '',
        operatorName: '',
        from: '',
        to: '',
        status: '',
        receivedStatus: '',
        uid: '',
        fuzzy: '',
      },
      total: 0,
      loading: false,
      list: null,
      typeList: [
        { id: 'ALL', name: 'All types' },
        { id: 'GOLD_COIN', name: 'There are gold coins' },
        { id: 'NO_GOLD_COIN', name: 'No gold coins' },
      ],
      operators: null,
      status: [
        { id: 'ALL', name: 'View status' },
        { id: 'VIEW', name: 'Viewed' },
        { id: 'NOT_VIEW', name: 'Unviewed' },
      ],
      toggleAllSelect: false,
      printObj: {
        type: 'html',
        scanStyles: false,
        id: 'soTest',
        popTitle: 'Ip Query print',
        beforeOpenCallback(vue) {
          vue.printLoading = true;
        },
        openCallback(vue) {
          vue.printLoading = false;
        },
        closeCallback(vue) {
        },
      },
    };
  },
  created() {
    this.formNewMail.operatorId = this.$store.getters.userId;
    this.getAllOperator();
  },
  methods: {
    async deleteSelectIds(){
      const selectIds = [];
      this.list.map((val) => {
        if (val.checked) {
          selectIds.push({ messageId: val.id, uid: val.uid });
        }
      });
      if (selectIds.length > 0) {
        await mailingListResource.del(1, selectIds)
          .then(response => {
            this.getMailList();
          });
      }
    },
    async addNewMailList(){
      this.$refs['formNewMail'].validate((valid) => {
        if (valid) {
          this.formNewMail.goldCoin = parseInt(this.formNewMail.goldCoin);
          this.formNewMail.maxRecharge = parseInt(this.formNewMail.maxRecharge);
          this.formNewMail.minRecharge = parseInt(this.formNewMail.minRecharge);
          console.log(this.formNewMail.withdrawalStandard);
          this.formNewMail.withdrawalStandard = (this.formNewMail.withdrawalStandard === null) ? 0 : parseInt(this.formNewMail.withdrawalStandard);
          massMailingListResource.store(this.formNewMail)
            .then(response => {
              this.$message({
                type: 'success',
                message: this.$t('messages.messageSent'),
                duration: 1 * 1000,
              });
              // this.resetFormNewMail();
              // this.dialogFormVisible = false;
            });
        } else {
          return false;
        }
      });
    },
    async getMailList() {
      if (this.query.operatorId !== '') {
        this.query.operatorName = (this.operators.find((item) => item.id === this.query.operatorId)).name;
      }
      await mailingListResource.list(this.query)
        .then(response => {
          this.list = response.data.map((val) => {
            val.checked = false;
            return val;
          });
          this.total = response.meta.total;
        });
    },
    resetQuery(){
      this.query = {
        page: 1,
        size: 10,
        type: '',
        operatorId: '',
        operatorName: '',
        from: '',
        to: '',
        status: '',
        receivedStatus: '',
        uid: '',
        fuzzy: '',
      };
    },
    chengeToggleAllSelect(){
      this.list.map((val) => {
        val.checked = this.toggleAllSelect;
      });
    },
    async getAllOperator() {
      this.loading = true;
      await operatorsResource.list()
        .then(response => {
          this.loading = false;
          this.operators = response;
          this.formNewMail.operatorName = (this.operators.find((item) => item.id === this.formNewMail.operatorId)).name;
        });
    },
  },
};
</script>

<style lang="scss" scoped>
  .filter-item.mass-mailing-list-filter-item.el-date-editor.el-input{
    width: 100%;
  }
  .wrap-button {
    display: grid;
    justify-content: center;
  }
  .el-dropdown-link {
    cursor: pointer;
    color: #409EFF;
  }
  .wrap-icon{
    padding: 6px;
  }
  .wrap-icon .item-icon{
    line-height: 16px;
    text-align: center;
    color: #333;
    cursor: pointer;
    margin: 0 10px 0 5px;;
    width: 26px;
    height: 26px;
    padding: 5px 5px 2px 7px;
    line-height: 16px;
    color: #409eff;
    border: 1px solid #ccc;
  }
  .soflex1 div div {
    width: 100%;
    text-align: center;
    line-height: 39px;
    height: 39px;
    font-size: 14px;
    color: #606266;
    text-rendering: optimizeLegibility;
    overflow: hidden;
    white-space: nowrap;
    font-family: Helvetica Neue, Helvetica, PingFang SC, Hiragino Sans GB, Microsoft YaHei, Arial, sans-serif;
  }
  .soflex1 div {
    border-bottom: 1px solid #dfe6ec;
    width: 20%;
  }
  .soflex1 {
    flex-direction: row;
    display: flex;
  }
  .table-title{
    overflow: hidden;
    white-space: nowrap;
  }
  .right_separate {
    padding-right: 7px;
  }
  .static_width_input {
    width: 217px!important;
  }
</style>
