<template>
  <div className="app-container">
    <el-row>
      <el-col :span="24">
        <div class="grid-content bg-purple-dark filter-container">
          <el-row :gutter="20">
            <el-col :span="20" class="label-title">
              <el-form
                ref="playerInformationQuery"
                :model="filterItems"
                style="width: 100%"
              >
                <el-form-item
                  class="filter-item-player"
                  prop="filtersValue"
                  :rules="{required: true, message: $t('placeholders.selectedItem'), trigger: 'blur'}"
                >
                  <!-- SELECT -->
                  <el-select
                    v-model="filterItems.filtersValue"
                    class="filter-item"
                    :placeholder="$t('placeholders.pleaseSelectedItemFilter')"
                    clearable
                    style="width: 250px;"
                  >
                    <el-option
                      v-for="option in options"
                      :key="option.value"
                      :label="option.label"
                      :value="option.value"
                    />
                  </el-select>
                </el-form-item>
                <el-form-item
                  class="filter-item-player"
                  prop="searchValue"
                  :rules="{required: true, message: $t('placeholders.selectedItem'), trigger: 'blur'}"
                >
                  <!-- INPUT -->
                  <el-input v-model="filterItems.searchValue" :placeholder="$t('labels.verificationCodeItem2')" class="filter-item" style="width: 250px;" />
                </el-form-item>
                <!-- BUTTON -->
                <el-button type="primary" class="filter-item" @click="getPlayerInformation">{{ $t('btn.query') }}</el-button>
              </el-form>
            </el-col>
          </el-row>
          <el-row :gutter="20">
            <el-col :span="16" class="table-wrp">
              <div v-for="item in playerInformationArr" :key="item.id" class="wrap-player-info">
                <el-row :gutter="24">
                  <el-col :span="8" class="wrap-player-info-label">
                    <div class="grid-content label-player-info">{{ item.name != "empty" ? $t('labels.' + item.label) : "" }}</div>
                  </el-col>
                  <el-col :span="16" class="wrap-player-info-value">
                    <div class="grid-content">
                      <div v-if="item.mode === 'read'">
                        {{ item.name != "empty" ? item.value : "" }}
                      </div>
                      <div v-else :class="[ item.name != 'empty' ? itemPlayerInfoClassInput : itemPlayerInfoEmptyClass ]">
                        <el-col :span="18" class="input-wrp">
                          <el-input v-model="item.value" />
                        </el-col>
                        <el-col :span="6" class="center">
                          <el-button @click="updateRemark">{{ $t('btn.save') }}</el-button>
                        </el-col>
                      </div>
                    </div>
                  </el-col>
                </el-row>
              </div>
            </el-col>
          </el-row>
        </div>
      </el-col>
    </el-row>
  </div>
</template>

<script>
import PlayerInformation from '@/api/playerInformation';

const resource = new PlayerInformation();

export default {
  name: 'PlayerInformation',
  data() {
    return {
      playerInformationArr: [
        { id: 'id', name: 'id', label: 'playerInformationId', value: '', mode: 'read' },
        { id: 'nickname', name: 'nickname', label: 'gameNickname', value: '', mode: 'read' },
        { id: 'typ', name: 'typ', label: 'accountStatus', value: '', mode: 'read' },
        { id: 'phone', name: 'phone', label: 'mobilePhoneNumber', value: '', mode: 'read' },
        { id: 'regIp', name: 'regIp', label: 'registeredIp', value: '', mode: 'read' },
        { id: 'created', name: 'created', label: 'registrationTime', value: '', mode: 'read' },
        { id: 'lastLoginIp', name: 'lastLoginIp', label: 'lastIp', value: '', mode: 'read' },
        { id: 'lastLoginTime', name: 'lastLoginTime', label: 'lastTime', value: '', mode: 'read' },
        { id: 'loginType', name: 'loginType', label: 'loginType', value: '', mode: 'read' },
        { id: 'loginChannel', name: 'loginChannel', label: 'loginChannel', value: '', mode: 'read' },
        { id: 'channel', name: 'channel', label: 'sourceChannel', value: '', mode: 'read' },
        { id: 'sourceChannel', name: 'sourceChannel', label: 'registrationType', value: '', mode: 'read' },
        { id: 'effectiveBet', name: 'effectiveBet', label: 'effectiveBet', value: '', mode: 'read' },
        { id: 'withdrawStandard', name: 'withdrawStandard', label: 'withdrawalStandard', value: '', mode: 'read' },
        { id: 'todayBet', name: 'todayBet', label: 'todaysTotalBet', value: '', mode: 'read' },
        { id: 'yesterdayBet', name: 'yesterdayBet', label: 'yesterdaysTotalBet', value: '', mode: 'read' },
        { id: 'totalBet', name: 'totalBet', label: 'historyAlwaysBet', value: '', mode: 'read' },
        { id: 'unsettledAmount', name: 'unsettledAmount', label: 'unsettledAmount', value: '', mode: 'read' },

        { id: 'bankAccount', name: 'bankAccount', label: 'accountBank', value: '', mode: 'read' },
        { id: 'bankName', name: 'bankName', label: 'bankCardNumber', value: '', mode: 'read' },
        { id: 'bank', name: 'bank', label: 'cardholderName', value: '', mode: 'read' },
        { id: 'empty3', name: 'empty', label: '', value: '', mode: 'read' },
        { id: 'sumRecharge', name: 'sumRecharge', label: 'rechargeAmount', value: '', mode: 'read' },
        { id: 'totalRecharge', name: 'totalRecharge', label: 'numberOfRecharges', value: '', mode: 'read' },
        { id: 'balance', name: 'balance', label: 'balance', value: '', mode: 'read' },
        { id: 'limitAmount', name: 'limitAmount', label: 'limitAmount', value: '', mode: 'read' },
        { id: 'sumWithdraw', name: 'sumWithdraw', label: 'successfulWithdrawalAmount', value: '', mode: 'read' },
        { id: 'totalWith', name: 'totalWith', label: 'numberOfSuccessfulWithdrawals', value: '', mode: 'read' },
        { id: 'sumWithFair', name: 'sumWithFair', label: 'withdrawalFailedAmount', value: '', mode: 'read' },
        { id: 'totalWithFair', name: 'totalWithFair', label: 'numberOfFailedWithdrawals', value: '', mode: 'read' },
        { id: 'ipLimit', name: 'ipLimit', label: 'whitelistInformation', value: '', mode: 'read' },
        { id: 'vipLevel', name: 'vipLevel', label: 'userLevel', value: '', mode: 'read' },
        { id: 'email', name: 'email', label: 'emailAccount', value: '', mode: 'read' },
        { id: 'deviceId', name: 'deviceId', label: 'deviceNumber', value: '', mode: 'read' },
        { id: 'remarks', name: 'remarks', label: 'remarks', value: '', mode: 'write' },
        // { id: 'appleAccount', name: 'appleAccount', label: 'appleAccount', value: '', mode: 'read' },
        // { id: 'facebookAccount', name: 'facebookAccount', label: 'facebookAccount', value: '', mode: 'read' },
        // { id: 'googleAccount', name: 'googleAccount', label: 'googleAccount', value: '', mode: 'read' },
      ],
      itemPlayerInfoClass: 'item-player-info',
      itemPlayerInfoClassInput: '',
      itemPlayerInfoEmptyClass: 'item-player-info-empty',
      loading: false,
      options: [
        { value: 'id', label: this.$t('labels.playerId') },
        { value: 'phone', label: this.$t('labels.mobileNumber') },
        { value: 'email', label: this.$t('labels.mailbox') },
        { value: 'nickname', label: this.$t('labels.gameNickname') },
        // { value: 'appleAccount', label: this.$t('labels.appleAccount') },
        // { value: 'facebookAccount', label: this.$t('labels.facebookAccount') },
        // { value: 'googleAccount', label: this.$t('labels.googleAccount') },
      ],
      filterItems: {
        filtersValue: 'phone',
        searchValue: '',
      },
      member: '',
    };
  },
  methods: {
    getPlayerInformation() {
      this.$refs['playerInformationQuery'].validate((valid) => {
        if (valid) {
          this.loading = true;
          resource.get(this.filterItems.filtersValue, this.filterItems.searchValue)
            .then(response => {
              this.member = response.data[0];
              for (const key in this.member) {
                this.playerInformationArr.forEach((item, index) => {
                  if (item.name === key) {
                    item.value = this.member[key];
                  }
                });
              }
              this.loading = false;
            });
        } else {
          return false;
        }
      });
    },
    updateRemark() {
      const idElement = this.playerInformationArr.find(one => one.id === 'id');
      const remarkElement = this.playerInformationArr.find(one => one.id === 'remarks');
      // console.log(idElement, remarkElement);
      if (idElement === '' || idElement === undefined || remarkElement === undefined) {
        return;
      }
      const id = parseInt(idElement.value, 10);
      resource.updateRemark(id, remarkElement.value);
    },
  },
};
</script>

<style lang="scss" scoped>
.el-row {
  margin: 0px 0px;
}
.item-player-info {
  display: flex;
  align-items: center;
  border: 1px solid #E6E6E6;
  padding: 4px 15px 0px 15px;
  height: 38px;
  overflow: hidden;
}
.item-player-info-input {
  display: flex;
  align-items: center;
  border: 1px solid #E6E6E6;
  padding: 4px 15px 0px 15px;
  height: 38px;
  overflow: hidden;
}
.item-player-info-empty {
  display: flex;
  align-items: center;
  border: 1px solid transparent;
  padding: 4px 15px 0px 15px;
  height: 38px;
  overflow: hidden;
}
.label-player-info {
  color: #666666;
}
.label-title {
  padding: 20px 20px 11px;
  border-bottom: 1px solid #f6f6f6;
  color: #333;
  border-radius: 2px 2px 0 0;
  font-size: 1.1rem;
}
.white-box {
  background-color: #fff;
  box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1);
}
.filter-item-player{
  width: 250px;
  float: left;
  margin: 0 20px;
}
.wrap-player-info:not(:first-child) { border-top: 1px solid #f6f6f6;}
.wrap-player-info {padding: 10px 0;}
.wrap-player-info:hover {background: #fbfbfb;}
.table-wrp {border-left: 1px solid #f6f6f6;border-right: 1px solid #f6f6f6;}
.center {text-align: center;}
.input-wrp {padding-left: 0!important;}
</style>
