<template>
  <div className="app-container">
    <!-- Filter -->
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
                value-format="yyyy-MM-dd hh:mm:ss"
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
                value-format="yyyy-MM-dd hh:mm:ss"
              />
            </el-col>
          </el-row>
        </div>
      </el-col>
    </el-row>
    <el-row>
      <el-col :span="3" class="wrap-button">
        <!-- BUTTON NEW MAIL -->
        <el-button
          type="primary"
          class="filter-item"
          @click="getMailList()"
        >
          {{ $t('btn.query') }}
        </el-button>
      </el-col>
      <el-col :span="3" class="wrap-button">
        <!-- BUTTON DELETE SELECT IDS -->
        <el-button
          type="primary"
          class="filter-item"
          @click="resetQuery()"
        >
          {{ $t('btn.reset') }}
        </el-button>
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
            <!-- title -->
            <div>
              <div><div class="table-title">{{ $t('table.title') }}</div></div>
              <div v-for="item in list" :key="item.userMailId">{{ item.title }}</div>
            </div>
            <!-- content -->
            <div>
              <div><div class="table-title">{{ $t('table.content') }}</div></div>
              <div v-for="item in list" :key="item.userMailId">{{ item.content }}</div>
            </div>
            <!-- goldCoin -->
            <div>
              <div><div class="table-title">{{ $t('table.goldCoin') }}</div></div>
              <div v-for="item in list" :key="item.userMailId">{{ item.goldCoin }}</div>
            </div>
            <!-- withdrawalStatus -->
            <div>
              <div><div class="table-title">{{ $t('table.withdrawalStandardPlus') }}</div></div>
              <div v-for="item in list" :key="item.userMailId">
                <span v-if="!item.withdrawalStandard" class="withdrawal-no-gold-coins">{{ $t('labels.noGoldCoins') }}</span>
                <span v-else>{{ item.withdrawalStandard }}</span>
              </div>
            </div>
            <div>
              <div><div class="table-title">{{ $t('table.operator') }}</div></div>
              <div v-for="item in list" :key="item.userMailId">{{ item.operatorId }}</div>
            </div>
            <!-- creationTime -->
            <div>
              <div><div class="table-title">{{ $t('table.creationTime') }}</div></div>
              <div v-for="item in list" :key="item.userMailId">{{ item.created }}</div>
            </div>
            <!-- updateTime -->
            <div>
              <div><div class="table-title">{{ $t('table.updateTime') }}</div></div>
              <div v-for="item in list" :key="item.userMailId">{{ item.updated }}</div>
            </div>
            <!-- operation -->
            <div>
              <div><div class="table-title">{{ $t('table.operation') }}</div></div>
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
const operatorsResource = new Resource('get-all-operators');
const mailingListResource = new Resource('mailing-list');

export default {
  name: 'BulkMailGenerationForm',
  components: { Pagination },
  data() {
    return {
      query: {
        page: 1,
        size: 10,
        operatorId: '',
        operatorName: '',
        type: '',
        fuzzy: '',
        from: '',
        to: '',
      },
      total: 0,
      loading: false,
      list: null,
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
        { id: 'ALL', name: this.$t('select.allTypes') },
        { id: 'GOLD_COIN', name: this.$t('select.thereAreGoldCoins') },
        { id: 'NO_GOLD_COIN', name: this.$t('select.noGoldCoins') },
      ],
      operators: null,
    };
  },
  created() {
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
                duration: 1 * 1000,
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
        page: this.query.page,
        size: this.query.size,
        operatorId: this.query.operatorId,
        operatorName: this.query.operatorName,
        type: '',
        fuzzy: '',
        from: '',
        to: '',
      };
      this.getMailList();
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
            const tHeader = ['Title', 'Content', 'Gold Coin', 'Withdrawal Standard+', 'Operator', 'Creation time', 'Update time'];
            const filterVal = ['title', 'content', 'goldCoin', 'withdrawalStandard', 'operatorId', 'created', 'updated'];
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
            const tHeader = ['Title', 'Content', 'Gold Coin', 'Withdrawal Standard+', 'Operator', 'Creation time', 'Update time'];
            const filterVal = ['title', 'content', 'goldCoin', 'withdrawalStandard', 'operatorId', 'created', 'updated'];
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
.withdrawal-no-gold-coins{
  background-color: #538db0;
  padding: 6px 10px;
  font-size: 12px;
  color: #fff;
  white-space: nowrap;
  text-align: center;
  border: none;
  border-radius: 2px;
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
</style>
