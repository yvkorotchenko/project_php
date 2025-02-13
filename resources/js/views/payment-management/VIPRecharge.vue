<template>
  <div class="app-container">
    <el-row>
      <el-col :span="24">
        <p>{{ $t('messages.vipRechargeNote') }}</p>
        <el-form
          ref="dataValidateForm"
          label-position="right"
          label-width="170px"
          class="padding-form"
          :model="dataValidateForm"
        >
          <el-form-item :label="$t('labels.vipRechargeType')">
            <!-- RADIO BUTTON -->
            <el-radio
              v-model="radio"
              :label="$t('labels.vipRechargeType')"
            >
              {{ $t('labels.vipRechargeVIPRecharge') }}
            </el-radio>
          </el-form-item>
          <el-form-item
            prop="playerID"
            :label="$t('labels.vipRechargePlayerID')"
            :rules="[{ required: true, message: $t('placeholders.pleaseInputPlayerID'), trigger: 'blur' },]"
          >
            <!-- INPUT Player ID -->
            <el-input
              v-model="dataValidateForm.playerID"
              class="filter-item"
              style="width: 250px;"
            />
          </el-form-item>
          <el-form-item
            prop="playerID"
            :label="$t('labels.vipRechargeRechargeAmount')"
            :rules="[{ required: true, message: $t('placeholders.pleaseInputRechargeAmount'), trigger: 'blur' },]"
          >
            <!-- INPUT Recharge amount -->
            <el-input
              v-model="dataValidateForm.amount"
              class="filter-item"
              style="width: 250px;"
            />
          </el-form-item>
          <el-form-item>
            <!-- BUTTON -->
            <PermissionChecker :permissions="['view menu vip recharge show']">
              <el-button
                type="primary"
                class="filter-item"
                @click="showAlert('dataValidateForm')"
              >
                {{ $t('labels.vipRechargeConfirmRecharge') }}
              </el-button>
            </PermissionChecker>
          </el-form-item>
        </el-form>
      </el-col>
    </el-row>
    <el-dialog
      title="Warning"
      :visible.sync="centerDialogVisible"
      width="30%"
      center
    >
      <span slot="footer" class="dialog-footer">
        <el-button @click="centerDialogVisible = false">Cancel</el-button>
        <PermissionChecker :permissions="['view menu vip recharge create']">
          <el-button type="primary" @click="createRecharge('dataValidateForm')">Confirm</el-button>
        </PermissionChecker>
      </span>
    </el-dialog>
  </div>
</template>

<script>
import VIPRecharge from '@/api/VIPRecharge';
import PermissionChecker from '@/components/Permissions/index.vue';
const resource = new VIPRecharge();
export default {
  name: 'VIPRecharge',
  components: {
    PermissionChecker,
  },
  data() {
    return {
      alert: false,
      radio: this.$t('labels.vipRechargeType'),
      dataValidateForm: {
        amount: '',
        created: '',
        userId: '',
        status: 'PROCESSING',
        playerID: '',
      },
      centerDialogVisible: false,
    };
  },
  methods: {
    showAlert(formName){
      this.$refs[formName].validate((valid) => {
        if (valid && this.dataValidateForm.playerID !== '' && this.dataValidateForm.amount !== '') {
          this.centerDialogVisible = true;
        }
      });
    },
    createRecharge(formName) {
      this.$refs[formName].validate((valid) => {
        if (valid && this.dataValidateForm.playerID !== '' && this.dataValidateForm.amount !== '') {
          this.loading = true;
          resource.post(this.dataValidateForm)
            .then(response => {
              this.verificationCode = response.data;
              this.$message({
                type: 'success',
                message: this.$t('messages.dataReceivedSuccessfully'),
                duration: 1 * 1000,
              });
              this.dataValidateForm.amount = '';
              this.dataValidateForm.playerID = '';
            });
          this.loading = false;
        } else {
          console.log('error submit!!');
          return false;
        }
      });
      this.centerDialogVisible = false;
    },
  },
};
</script>

<style lang="scss" scoped>
  .button-right{
    display: flex;
    justify-content: flex-end;
  }
  .button-left{
    display: flex;
    justify-content: flex-start;
  }
  .button-left button,
  .button-right button{
    margin: 0px 5px;
  }
  .h2center{
    text-align: center;
  }
</style>
