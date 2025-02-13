<template>
  <div className="app-container">
    <el-row>
      <el-col :span="24">
        <PermissionChecker :permissions="['member information modification search']">
          <el-row :gutter="20">
            <el-col :span="20" class="label-title">
              <!-- SELECT -->
              <el-select
                v-model="filtersValue"
                class="filter-item"
                :placeholder="$t('placeholders.pleaseSelectedItemFilter')"
                style="width: 250px;"
                @change="changeFieldName"
              >
                <el-option
                  v-for="option in [{
                                      value: 'id',
                                      label: this.$t('labels.playerId'),
                                    },
                                    {
                                      value: 'phone',
                                      label: this.$t('labels.mobileNumber'),
                                    },
                                    {
                                      value: 'email',
                                      label: this.$t('labels.mailbox'),
                                    },
                                    {
                                      value: 'nickname',
                                      label: this.$t('labels.gameNickname'),
                                    }]"
                  :key="option.value"
                  :label="option.label"
                  :value="option.value"
                />
              </el-select>
              <!-- INPUT -->
              <el-input
                v-model="query.fieldValue"
                :class="[isEmptyValue ? 'filterItemValue' : '', 'filter-item']"
                style="width: 250px;"
              />
              <!-- BUTTON -->
              <el-button type="primary" class="filter-item" @click="getMemberInfoNotification">{{ $t('btn.query') }}</el-button>
            </el-col>
          </el-row>
        </PermissionChecker>
        <!-- fore column layout -->
        <el-row class="wrap-row">
          <el-col :span="4" class="label-title celsItems">
            <span class="demo-input-label">{{ $t('labels.memberInformationModificationPayerID') }}</span>
          </el-col>
          <el-col :span="4" class="label-title">
            <el-input v-model="player.id" :disabled="true" />
          </el-col>
          <el-col :span="4" class="label-title celsItems">
            <span class="demo-input-label">{{ $t('labels.memberInformationModificationPlayerNickname') }}</span>
          </el-col>
          <el-col :span="4" class="label-title">
            <el-input v-model="player.nickname" :disabled="true" />
          </el-col>
        </el-row>
        <el-row class="wrap-row">
          <el-col :span="4" class="label-title celsItems">
            <span class="demo-input-label">{{ $t('labels.countryCode') }}</span>
          </el-col>
          <el-col :span="4" class="label-title">
            <el-input v-model="player.countryCode" :class="[emptyCountryCode ? 'filterItemValue' : '']" />
          </el-col>
          <el-col :span="4" class="label-title celsItems">
            <span class="demo-input-label">{{ $t('labels.memberInformationModificationMobilePhoneNumber') }}</span>
          </el-col>
          <el-col :span="4" class="label-title">
            <el-input v-model="player.phone" :class="[emptyPhone ? 'filterItemValue' : '']" />
          </el-col>
        </el-row>
        <el-row class="playerBankHidden wrap-row">
          <el-col :span="4" class="label-title celsItems">
            <span class="demo-input-label">{{ $t('labels.memberInformationModificationAccountBank') }}</span>
          </el-col>
          <el-col :span="4" class="label-title">
            <el-input />
          </el-col>
          <el-col :span="4" class="label-title celsItems">
            <span class="demo-input-label">{{ $t('labels.memberInformationModificationBankCardNumber') }}</span>
          </el-col>
          <el-col :span="4" class="label-title">
            <el-input />
          </el-col>
          <el-col :span="4" class="label-title celsItems">
            <span class="demo-input-label">{{ $t('labels.memberInformationModificationCardholderName') }}</span>
          </el-col>
          <el-col :span="4" class="label-title">
            <el-input />
          </el-col>
        </el-row>
        <el-row class="wrap-row">
          <el-col :span="4" class="label-title celsItems">
            <span class="demo-input-label">{{ $t('labels.memberInformationModificationEmailAccount') }}</span>
          </el-col>
          <el-col :span="4" class="label-title">
            <el-input v-model="player.email" :class="[emptyEmail ? 'filterItemValue' : '']" />
          </el-col>
        </el-row>
        <el-row class="wrap-row">
          <el-col :span="4" class="label-title celsItems">
            <span class="demo-input-label">{{ $t('labels.memberInformationModificationEffectiveCoding') }}</span>
          </el-col>
          <el-col :span="4" class="label-title">
            <el-input v-model="player.effectiveBet" :disabled="true" />
          </el-col>
          <el-col :span="4" class="label-title celsItems">
            <span class="demo-input-label">{{ $t('labels.memberInformationModificationWhithdrawalStandard') }}</span>
          </el-col>
          <el-col :span="4" class="label-title">
            <el-input v-model="player.withdrawStandard" :disabled="true" />
          </el-col>
        </el-row>
        <PermissionChecker :permissions="['member information modification modify information']">
          <el-row class="wrap-row">
            <el-col :offset="4" :span="8" class="label-title">
              <el-button type="primary" class="filter-item" @click="modifyInformation">{{ $t('btn.modifyInformation') }}</el-button>
            </el-col>
          </el-row>
        </PermissionChecker>
      </el-col>
    </el-row>
  </div>
</template>
<script>
import Resource from '@/api/resource';
import PermissionChecker from '@/components/Permissions/index.vue';
const MemberResource = new Resource('member-info');
const MemberUpdateResource = new Resource('member');

export default {
  name: 'MemberInformationModification',
  components: {
    PermissionChecker,
  },
  data() {
    return {
      loading: false,
      query: {
        fieldName: '',
        fieldValue: '',
      },
      player: {
        id: '',
        phone: '',
        nickname: '',
        email: '',
        effectiveBet: '',
        withdrawStandard: '',
        countryCode: '',
      },
      filtersValue: '',
      isEmptyValue: false,
      emptyPhone: false,
      emptyEmail: false,
      emptyCountryCode: false,
    };
  },
  created() {
    this.filtersValue = this.$t('labels.playerId');
    this.query.fieldName = 'id';
  },
  methods: {
    getMemberInfoNotification() {
      if (this.query.fieldValue === '') {
        this.isEmptyValue = true;
      } else {
        this.loading = true;
        this.isEmptyValue = false;
        MemberResource.list(this.query)
          .then(response => {
            this.emptyPhone = false;
            this.emptyEmail = false;
            this.emptyCountryCode = false;
            this.loading = false;
            this.player = response.data[0];
          });
      }
    },
    modifyInformation() {
      if (this.player.phone === '') {
        this.emptyPhone = true;
      } else {
        this.emptyPhone = false;
      }
      if (this.player.email === '') {
        this.emptyEmail = true;
      } else {
        this.emptyEmail = false;
      }
      if (this.player.countryCode === '') {
        this.emptyCountryCode = true;
      } else {
        this.emptyCountryCode = false;
      }
      if (this.player.phone === '' || this.player.email === '' || this.player.countryCode === '') {
        // this.$message({
        //   type: 'error',
        //   message: this.$t('messages.memberInformationModificationEmptyFields'),
        //   duration: 1 * 1000,
        // });
      } else {
        this.loading = true;
        this.isEmptyValue = false;
        MemberUpdateResource.update(this.player.id, this.player)
          .then(response => {
            this.$message({
              type: 'success',
              message: this.$t('messages.taskManagementStatusSuccess'),
              duration: 1 * 1000,
            });
            this.loading = false;
          });
      }
    },
    changeFieldName() {
      this.query.fieldName = this.filtersValue;
    },
  },
};
</script>

<style lang="scss" scoped>
  .filterItemValue{
    border: 1px solid red;
  }
  .playerBankHidden{
    display: none;
  }
  .celsItems{
    display: flex;
    justify-content: flex-end;
    align-items: center;
    height: 100%;
  }
  .celsItems span{
    line-height: 36.8px;
    font-size: 13px;
    padding-right: 10px;
  }
  .wrap-row{
    margin: 20px;
  }
</style>
