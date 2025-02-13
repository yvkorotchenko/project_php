<template>
  <div class="app-container">
    <h1>{{ $t('titles.manualCreateWithdrawOrder') }}</h1>
    <div>
      <el-form
        ref="formResult"
        :model="formResult"
        label-position="right"
        class="padding-form"
        :rules="formValidationRules"
      >
        <el-form-item :label="$t('labels.userId')" prop="uid">
          <el-input v-model.number="formResult.uid" @change="onUserIdUpdate" />
          <span v-if="userAlreadyHasNotCompletedWithdrawal" class="el-form-item__error">
            {{ userAlreadyHasNotCompletedWithdrawalMessage }}
          </span>
        </el-form-item>
        <el-form-item :label="$t('labels.withdrawalStandard')">
          <span v-if="withdrawalStandard !== null && minWihtdrawalStandard !== null && withdrawalStandard < minWihtdrawalStandard" class="red-warning">
            {{ formatFloat(withdrawalStandard) }} &lt; {{ formatFloat(minWihtdrawalStandard) }}
          </span>
          <span v-else>
            {{ formatFloat(withdrawalStandard) }}
          </span>
        </el-form-item>
        <el-form-item :label="$t('labels.effectiveBet')">
          <span v-if="effectiveBet !== null && minEffectiveBet !== null && effectiveBet < minEffectiveBet" class="red-warning">
            {{ formatFloat(effectiveBet) }} &lt; {{ formatFloat(minEffectiveBet) }}
          </span>
          <span v-else>
            {{ formatFloat(effectiveBet) }}
          </span>
        </el-form-item>
        <el-form-item :label="$t('labels.balance')">
          {{ userBalance }}
        </el-form-item>
        <el-form-item :label="$t('labels.currency')">
          <el-select v-model="formResult.currencyId" value-key="id" prop="currency" @change="onCurrencyChange">
            <el-option v-for="currency in currencies" :key="currency.id" :value="currency.id" :label="currency.name">{{ currency.name }}</el-option>
          </el-select>
        </el-form-item>
        <el-form-item :label="$t('labels.withdrawalAmount')" prop="withdrawAmount">
          <el-input v-model.number="formResult.withdrawAmount" type="number" min="1" />
        </el-form-item>
        <el-form-item :label="$t('labels.receiveAddress')" prop="receiveAddress">
          <el-input v-model="formResult.receiveAddress" />
        </el-form-item>
        <el-form-item :label="$t('labels.notice')">
          <el-input v-model="formResult.notice" type="textarea" :autosize="{ minRows: 12}" />
        </el-form-item>
        <el-form-item>
          <PermissionChecker :permissions="['view vip withdraw order create']">
            <el-button type="primary" @click.prevent="showAlert">{{ $t('labels.create') }}</el-button>
          </PermissionChecker>
        </el-form-item>
      </el-form>
    </div>
    <el-dialog
      title="Warning"
      :visible.sync="centerDialogVisible"
      width="30%"
      center
    >
      <span>It should be noted that the content will not be aligned in center by default</span>
      <span slot="footer" class="dialog-footer">
        <el-button @click="centerDialogVisible = false">Cancel</el-button>
        <el-button type="primary" @click="onSubmit">Confirm</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
import FinanceRequest from '@/api/finance';
import PermissionChecker from '@/components/Permissions/index.vue';
const financeRequest = new FinanceRequest();

export default {
  name: 'VipWithdrawOrder',
  components: {
    PermissionChecker,
  },
  data(){
    return {
      centerDialogVisible: false,
      withdrawalStandard: null,
      effectiveBet: null,
      currencies: null,
      minEffectiveBet: null,
      minWihtdrawalStandard: null,
      userBalance: null,
      userAlreadyHasNotCompletedWithdrawal: false,
      userAlreadyHasNotCompletedWithdrawalMessage: '*still have uncompleted withdrawal orders',
      formResult: {
        uid: null,
        currencyId: null,
        withdrawAmount: null,
        receiveAddress: null,
        notice: null,
      },
      formValidationRules: {
        uid: [
          { required: true, message: this.$t('messages.warningRequiredField', { fieldName: 'User ID' }), trigger: 'blur' },
          { type: 'integer', trigger: 'change' },
        ],
        currency: [
          { required: true, message: this.$t('messages.warningRequiredField', { fieldName: 'Currency' }), trigger: 'change' },
        ],
        withdrawAmount: [
          { required: true, message: this.$t('messages.warningRequiredField', { fieldName: 'Withdrawal Amount' }), trigger: 'blur' },
          { type: 'number', trigger: 'change' },
        ],
        receiveAddress: [{ required: true, message: this.$t('messages.warningRequiredField', { fieldName: 'Receive address' }), trigger: 'blur' }],
      },
    };
  },
  created() {
    financeRequest.currencyList().then((response) => {
      this.currencies = response.data;
    });
  },
  methods: {
    onUserIdUpdate(uid) {
      this.withdrawalStandard = null;
      this.effectiveBet = null;
      this.userAlreadyHasNotCompletedWithdrawal = false;
      if (uid !== '') {
        this.getUserFinanceInfo(uid).then((response) => {
          if (response !== null && response.data !== null) {
            const data = response.data;
            this.withdrawalStandard = data.withdrawStandard;
            this.effectiveBet = data.effectiveBet;
            this.userAlreadyHasNotCompletedWithdrawal = data.hasNotCompletedWithdraw;
            this.userBalance = data.balance;
            this.minEffectiveBet = data.withdrawStandard;
          }
        });
      }
    },
    onCurrencyChange(id) {
      this.updateWithdrawRequirements(id);
    },
    getUserFinanceInfo(uid) {
      if (uid) {
        return financeRequest.userInfo(uid);
      }
    },
    updateWithdrawRequirements(currencyId) {
      if (!currencyId) {
        return;
      }

      financeRequest.currencyWithdraw(currencyId).then((response) => {
        if (response.data !== null && response.data.min !== null) {
          this.minWihtdrawalStandard = response.data.min;
        }
      });
    },
    showAlert(){
      this.$refs['formResult'].validate((valid) => {
        if (valid) {
          this.centerDialogVisible = true;
        }
      });
    },
    onSubmit() {
      this.$refs['formResult'].validate((isValid) => {
        if (!isValid) {
          return;
        }
        financeRequest.createWithdraw(this.formResult)
          .then((response) => {
            this.formResult.uid = null;
            this.formResult.currencyId = null;
            this.formResult.withdrawAmount = null;
            this.formResult.receiveAddress = null;
          });
      });
    },
    formatFloat(floatNumber) {
      if (typeof floatNumber === 'number') {
        return floatNumber.toFixed(2);
      }
      return null;
    },
  },
};
</script>

<style scoped>
.red-warning {color: #ff4949;}
</style>
