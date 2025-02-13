<template>
  <div className="app-container">
    <!-- DIALOG ADD NEW MAIL  :visible.sync="dialogFormVisible" -->
    <el-dialog :title="$t('labels.newMail')" :visible.sync="dialogFormVisible">
      <el-form ref="formNewMail" :model="formNewMail">
        <!-- Player ID -->
        <el-form-item
          :label="$t('labels.playerId')"
          :label-width="formLabelWidth"
          prop="uids"
          :rules="{required: true, message: $t('placeholders.playerUIDs'), trigger: 'blur'}"
        >
          <el-input v-model="formNewMail.uids" autocomplete="off" :placeholder="$t('placeholders.playerUIDs')" @input="inputUids" />
        </el-form-item>
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
        <el-row>
          <el-col :span="12">
            <!-- Gold Coin -->
            <el-form-item
              :label="$t('labels.goldCoin')"
              :label-width="formLabelWidth"
            >
              <el-input-number v-model="formNewMail.goldCoin" :min="0" @change="changeGoldCoin" />
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <!-- Withdrawal Standard -->
            <el-form-item
              :label="$t('labels.withdrawalStandard')"
              :label-width="formLabelWidth"
            >
              <el-radio v-model="formNewMail.increaseWithdrawStandart" :label="false" @change="changeRadioIncreaseWithdrawStandart">{{ $t('labels.noIncrease') }}</el-radio>
              <el-radio v-model="formNewMail.increaseWithdrawStandart" :label="true" @change="changeRadioIncreaseWithdrawStandart">{{ $t('labels.increase') }}</el-radio>
            </el-form-item>
          </el-col>
        </el-row>
        <el-row v-if="visibleFormIncrease">
          <el-col :span="12">
            <!-- Increase multiple -->
            <el-form-item
              :label="$t('labels.increaseMultiple')"
              :label-width="formLabelWidth"
            >
              <el-input-number v-model="formNewMail.increaseCoef" :min="1" :max="30" :step="1" @change="changeIncreaseCoef" />
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <!-- Actual increase in value -->
            <el-form-item
              :label="$t('labels.actualIncreaseInValue')"
              :label-width="formLabelWidth"
            >
              <el-input v-model="formNewMail.increaseValue" :disabled="true" />
            </el-form-item>
          </el-col>
        </el-row>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button type="primary" @click="addNewMailList">{{ $t('btn.confirm') }}</el-button>
      </span>
    </el-dialog>
    <el-row>
      <el-col :span="24">
        <div class="grid-content bg-purple-dark bg-white filter-container">
          <el-row>
            <el-col :span="4">
              <!-- SELECT ALL TYPES -->
              <el-select
                v-model="query.type"
                class="filter-item mailing-list-filter-item"
                :placeholder="$t('placeholders.allTypes')"
                clearable
              >
                <el-option
                  v-for="item in typeList"
                  :key="item.id"
                  :label="language === 'en' ? item.nameEN : item.nameZH"
                  :value="item.id"
                />
              </el-select>
            </el-col>
            <el-col :span="4">
              <!-- SELECT OPERATOR -->
              <el-select
                v-model="query.operatorId"
                class="filter-item mailing-list-filter-item"
                :placeholder="$t('placeholders.pleaseSelectTheOperator')"
                clearable
              >
                <el-option
                  v-for="item in operators"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id"
                />
              </el-select>
            </el-col>
            <el-col :span="4">
              <!-- INPUT QUERY START TIME -->
              <el-date-picker
                v-model="query.from"
                class="filter-item mailing-list-filter-item"
                type="datetime"
                :placeholder="$t('placeholders.queryStartTime')"
                format="yyyy-MM-dd hh:mm:ss"
                value-format="yyyy-MM-ddThh:mm:ss"
              />
            </el-col>
            <el-col :span="4">
              <!-- INPUT QUERY END TIME -->
              <el-date-picker
                v-model="query.to"
                class="filter-item mailing-list-filter-item"
                type="datetime"
                :placeholder="$t('placeholders.queryEndTime')"
                format="yyyy-MM-dd hh:mm:ss"
                value-format="yyyy-MM-ddThh:mm:ss"
              />
            </el-col>
            <el-col :span="4">
              <!-- INPUT ACCOUNT NUMBER -->
              <el-input
                v-model="query.uid"
                :placeholder="$t('placeholders.accountNumber')"
                class="filter-item mailing-list-filter-item"
              />
            </el-col>
          </el-row>
        </div>
      </el-col>
    </el-row>
    <el-row>
      <el-col :span="24">
        <div class="grid-content bg-purple-dark bg-white filter-container">
          <el-row>
            <el-col :span="4">
              <!-- SELECT STATUS -->
              <el-select
                v-model="query.status"
                class="filter-item mailing-list-filter-item"
                :placeholder="$t('placeholders.viewStatus')"
                clearable
              >
                <el-option
                  v-for="item in status"
                  :key="item.id"
                  :label="language === 'en' ? item.nameEN : item.nameZH"
                  :value="item.id"
                />
              </el-select>
            </el-col>
            <el-col :span="4">
              <!-- SELECT RECEIVED STATUS -->
              <el-select
                v-model="query.receivedStatus"
                class="filter-item mailing-list-filter-item"
                :placeholder="$t('placeholders.receivedStatus')"
                clearable
              >
                <el-option
                  v-for="item in receivedStatus"
                  :key="item.id"
                  :label="language === 'en' ? item.nameEN : item.nameZH"
                  :value="item.id"
                />
              </el-select>
            </el-col>
            <el-col :span="4">
              <!-- INPUT FUZY CONDITION -->
              <el-input
                v-model="query.fuzzy"
                :placeholder="$t('placeholders.titleFuzzyCondition')"
                class="filter-item mailing-list-filter-item fuzzy-condition"
              />
            </el-col>
            <el-col :span="2" class="wrap-button">
              <!-- BUTTON QUERY -->
              <el-button
                type="primary"
                class="filter-item"
                @click="getMailList()"
              >
                {{ $t('btn.query') }}
              </el-button>
            </el-col>
            <el-col :span="2" class="wrap-button">
              <!-- BUTTON RESET -->
              <el-button
                type="primary"
                class="filter-item"
                @click="resetQuery()"
              >
                {{ $t('btn.reset') }}
              </el-button>
            </el-col>
          </el-row>
        </div>
      </el-col>
    </el-row>
    <el-row>
      <el-col :span="3" class="wrap-button">
        <!-- BUTTON NEW MAIL -->
        <PermissionChecker :permissions="[viewPermission.create]">
          <el-button
            type="primary"
            class="filter-item"
            @click="dialogFormVisible = true;"
          >
            {{ $t('btn.newMail') }}
          </el-button>
        </PermissionChecker>
      </el-col>
      <el-col :span="3" class="wrap-button">
        <!-- BUTTON DELETE SELECT IDS -->
        <PermissionChecker :permissions="[viewPermission.delete]">
          <el-button
            type="primary"
            class="filter-item"
            @click="deleteSelectIds()"
          >
            {{ $t('btn.deleteMail') }}
          </el-button>
        </PermissionChecker>
      </el-col>
    </el-row>
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
            <!-- updateTime -->
            <div>
              <div><div class="table-title">{{ $t('labels.updateTime') }}</div></div>
              <div v-for="item in list" :key="item.userMailId">{{ item.updated }}</div>
            </div>
            <!-- operation -->
            <div>
              <div><div class="table-title">{{ $t('labels.operation') }}</div></div>
              <div v-for="item in list" :key="item.userMailId"><el-button type="danger" @click="deleteRow(item.id, item.uid)">{{ $t('btn.delete') }}</el-button></div>
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
import PermissionChecker from '@/components/Permissions/index.vue';
import viewsPermissions from '@/viewsPermissions.js';

const operatorsResource = new Resource('get-all-operators');
const mailingListResource = new Resource('mailing-list');

export default {
  name: 'MailingList',
  components: {
    Pagination,
    PermissionChecker,
  },
  data() {
    return {
      viewPermission: viewsPermissions.mailingList.permissions.controls,
      formNewMail: {
        content: '',
        contentLocal: '',
        goldCoin: '',
        increaseCoef: 1,
        increaseValue: '',
        increaseWithdrawStandart: true,
        onTop: true,
        operatorId: '',
        operatorName: '',
        title: '',
        titleLocal: '',
        uids: '',
      },
      visibleFormIncrease: true,
      formLabelWidth: '100px',
      dialogFormVisible: false,
      total: 0,
      loading: false,
      list: null,
      toggleAllSelect: false,
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
      listIds: [],
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
      typeList: [
        { id: 'ALL', nameEN: this.$t('select.mailingListAllTypes', 'en'), nameZH: this.$t('select.mailingListAllTypes', 'zh') },
        { id: 'GOLD_COIN', nameEN: this.$t('select.mailingListThereAreGoldCoins', 'en'), nameZH: this.$t('select.mailingListThereAreGoldCoins', 'zh') },
        { id: 'NO_GOLD_COIN', nameEN: this.$t('select.mailingListNoGoldCoins', 'en'), nameZH: this.$t('select.mailingListNoGoldCoins', 'zh') },
      ],
      operators: null,
      status: [
        { id: 'ALL', nameEN: this.$t('select.mailingListViewStatus', 'en'), nameZH: this.$t('select.mailingListViewStatus', 'zh') },
        { id: 'VIEW', nameEN: this.$t('select.mailingListViewed', 'en'), nameZH: this.$t('select.mailingListViewed', 'zh') },
        { id: 'NOT_VIEW', nameEN: this.$t('select.mailingListUnviewed', 'en'), nameZH: this.$t('select.mailingListUnviewed', 'zh') },
      ],
      receivedStatus: [
        { id: 'ALL', nameEN: this.$t('select.mailingListReceivedStatus', 'en'), nameZH: this.$t('select.mailingListReceivedStatus', 'zh') },
        { id: 'UNCLAIMED', nameEN: this.$t('select.mailingListUnclaimed', 'en'), nameZH: this.$t('select.mailingListUnclaimed', 'zh') },
        { id: 'RECEIVED', nameEN: this.$t('select.mailingListReceived', 'en'), nameZH: this.$t('select.mailingListReceived', 'zh') },
        { id: 'NO_GOLD', nameEN: this.$t('select.mailingListResNoGoldCoins', 'en'), nameZH: this.$t('select.mailingListResNoGoldCoins', 'zh') },
      ],
    };
  },
  computed: {
    language: function() {
      return this.$store.getters.language;
    },
  },
  created() {
    this.formNewMail.operatorId = this.$store.getters.userId;
    this.getAllOperator();
  },
  methods: {
    inputUids(){
      this.formNewMail.uids = this.formNewMail.uids.replace(/[^\d,]+/g, '');
    },
    changeIncreaseCoef(){
      this.formNewMail.increaseValue = (this.formNewMail.goldCoin > 0 && this.formNewMail.increaseCoef) ? this.formNewMail.goldCoin * this.formNewMail.increaseCoef : null;
    },
    changeGoldCoin(){
      this.formNewMail.increaseValue = (this.formNewMail.goldCoin > 0 && this.formNewMail.increaseCoef) ? this.formNewMail.goldCoin * this.formNewMail.increaseCoef : null;
    },
    changeRadioIncreaseWithdrawStandart(){
      if (this.formNewMail.increaseWithdrawStandart) {
        this.visibleFormIncrease = true;
        this.formNewMail.increaseCoef = 1;
        if (this.formNewMail.goldCoin > 0) {
          this.formNewMail.increaseValue = this.formNewMail.goldCoin * this.formNewMail.increaseCoef;
        }
      } else {
        this.visibleFormIncrease = false;
        this.formNewMail.increaseValue = null;
        this.formNewMail.increaseCoef = null;
      }
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
    async deleteRow(messageId, uid) {
      await mailingListResource.del(messageId, { messageId: messageId, uid: uid })
        .then(response => {
          this.getMailList();
          this.$message({
            type: 'success',
            message: this.$t('messages.success'),
            duration: 1 * 1000,
          });
        });
    },
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
            this.$message({
              type: 'success',
              message: this.$t('messages.success'),
              duration: 1 * 1000,
            });
          });
      }
    },
    async addNewMailList(){
      this.$refs['formNewMail'].validate((valid) => {
        if (valid) {
          this.formNewMail.goldCoin = parseInt(this.formNewMail.goldCoin);
          this.formNewMail.increaseCoef = parseInt(this.formNewMail.increaseCoef);
          this.formNewMail.increaseValue = parseInt(this.formNewMail.increaseValue);
          mailingListResource.store(this.formNewMail)
            .then(response => {
              this.$message({
                type: 'success',
                message: this.$t('messages.messageSent'),
                duration: 2 * 1000,
              });
              this.resetFormNewMail();
              this.dialogFormVisible = false;
            });
        } else {
          return false;
        }
      });
    },
    resetQuery(){
      this.query = {
        type: '',
        operatorId: '',
        operatorName: '',
        from: '',
        to: '',
        status: '',
        receivedStatus: '',
        accoundNumber: '',
      };
    },
    resetFormNewMail(){
      this.formNewMail = {
        content: '',
        contentLocal: '',
        goldCoin: '',
        increaseCoef: 1,
        increaseValue: '',
        increaseWithdrawStandart: true,
        onTop: true,
        operatorId: this.formNewMail.operatorId,
        operatorName: '',
        title: '',
        titleLocal: '',
        uids: '',
      };
    },
    chengeToggleAllSelect(){
      this.list.map((val) => {
        val.checked = this.toggleAllSelect;
      });
    },
    getExcelFile() {
      this.loading = true;
      this.downloading = true;
      const query = {};
      Object.assign(query, this.query);
      query.page = 1;
      query.size = this.total;
      mailingListResource.list(query)
        .then(response => {
          this.loading = false;
          import('@/vendor/Export2Excel').then(excel => {
            const tHeader = ['Player ID', 'Title', 'Content', 'Gold Coin', 'View status', 'Received status', 'Withdrawal standard+', 'Operator', 'Creation time', 'Update time'];
            const filterVal = ['uid', 'title', 'content', 'goldCoin', 'status', 'receivedStatus', 'withdrawalStandard', 'operatorId', 'created', 'updated'];
            const data = this.formatJson(filterVal, response.data);
            excel.export_json_to_excel({
              header: tHeader,
              data,
              filename: 'IpQuery',
              bookType: 'xlsx',
            });
            this.downloading = false;
          });
        });
    },
    getCSVFile() {
      this.loading = true;
      this.downloading = true;
      const query = {};
      Object.assign(query, this.query);
      query.page = 1;
      query.size = this.total;
      mailingListResource.list(query)
        .then(response => {
          this.loading = false;
          import('@/vendor/Export2Excel').then(excel => {
            const tHeader = ['Player ID', 'Title', 'Content', 'Gold Coin', 'View status', 'Received status', 'Withdrawal standard+', 'Operator', 'Creation time', 'Update time'];
            const filterVal = ['uid', 'title', 'content', 'goldCoin', 'status', 'receivedStatus', 'withdrawalStandard', 'operatorId', 'created', 'updated'];
            const data = this.formatJson(filterVal, response.data);
            excel.export_json_to_excel({
              header: tHeader,
              data,
              filename: 'IpQuery',
              bookType: 'csv',
            });
            this.downloading = false;
          });
        });
    },
    formatJson(filterVal, jsonData) {
      return jsonData.map(v => filterVal.map(j => v[j]));
    },
  },
};
</script>

<style lang="scss" scoped>
  .mailing-list-filter-item{
    width: 100%;
  }
  .wrap-button{
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
  .dialog-footer{
    display: flex;
    flex-direction: row;
  }
  label.el-form-item__label {
    font-size: 12px;
  }
  .fuzzy-condition{
    display: none;
  }
</style>
