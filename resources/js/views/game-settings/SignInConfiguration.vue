<template>
  <div v-loading="loading">
    <!-- -->
    <el-row :gutter="24">
      <!-- Extra gold rate -->
      <el-col :span="12">
        <div class="grid-content bg-purple">
          <!-- -->
          <div class="header-box">{{ $t('labels.extraGoldRate') }}</div>
          <!-- FORM -->
          <el-form :label-position="position" label-width="237px">
            <!-- 1 -->
            <el-form-item
              v-for="(value, key) in signInConfiguration['extraGold']"
              :key="key"
              :label="$t('labels.yesterdayBet') + showYesterdayBet(value)"
            >
              <el-input v-model="signInConfiguration['extraGold'][key]['goldRate']" maxlength="17" style="width: 200px" @input="verifyYesterdayBetInt(key)" />
            </el-form-item>
            <!-- 5 -->
            <el-form-item :label="$t('labels.TotalSingInGoldMaximumLimit')">
              <el-input v-model="maxLimit" style="width: 200px" maxlength="17" @input="verifyMaximumLimitInt" />
            </el-form-item>
          </el-form>
        </div>
      </el-col>
      <!-- sign-in gold -->
      <el-col :span="12">
        <div class="grid-content bg-purple">
          <!-- -->
          <div class="header-box">{{ $t('labels.signInGold') }}</div>
          <el-form
            :label-position="position"
            :label-width="labelWidth"
          >
            <!-- List Days -->
            <el-form-item
              v-for="(value, key) in signInConfiguration.signInActivity.reward"
              :key="key"
              :label="key + ' ' + $t('labels.day')"
            >
              <el-input
                v-model="signInConfiguration.signInActivity.reward[key]"
                style="width: 200px"
                maxlength="17"
                @input="verifyDayInt(key)"
              />
            </el-form-item>
          </el-form>
        </div>
      </el-col>
    </el-row>
    <hr>
    <br>
    <!-- CONFIRM MODIFICATION -->
    <div style="text-align: center;">
      <el-button type="primary" @click="updateSingInConfiguration">{{ $t('btn.confirmModification') }}</el-button>
    </div>
  </div>
</template>

<script>
import Resource from '@/api/resource';

const SingIn = new Resource('sing-in-gold');

export default {
  name: 'SignInConfiguration',
  props: {
    labelWidth: {
      type: String,
      default: '137px',
    },
    position: {
      type: String,
      default: 'right',
    },
  },
  data() {
    return {
      loading: false,
      maxLimit: null,
      // sends objects
      signInConfiguration: null,
    };
  },
  created() {
    this.getSignIn();
  },
  methods: {
    async getSignIn() {
      this.loading = true;
      const { data } = await SingIn.list({});
      // copy object
      this.signInConfiguration = data;
      this.signInConfiguration.signInActivity.reward = JSON.parse(data.signInActivity.reward);
      this.maxLimit = data.extraGold[0]['maxLimit'];
      this.loading = false;
    },
    // set max limit for all
    verifyDayInt(id) {
      if (this.signInConfiguration.signInActivity.reward[id]) {
        this.signInConfiguration.signInActivity.reward[id] = Number.parseFloat(this.signInConfiguration.signInActivity.reward[id].replace(/[^0-9.]/g, ''));
      }
    },
    verifyYesterdayBetInt(id) {
      if (this.signInConfiguration['extraGold'][id]['goldRate']) {
        this.signInConfiguration['extraGold'][id]['goldRate'] = Number.parseFloat(this.signInConfiguration.extraGold[id]['goldRate'].replace(/[^0-9.]/g, ''));
      }
    },
    verifyMaximumLimitInt() {
      if (this.maxLimit) {
        this.maxLimit = Number.parseInt(this.maxLimit.replace(/[^0-9.]/g, ''));
        // update max limit
        this.signInConfiguration['extraGold'].forEach((elem, index) => {
          elem.maxLimit = this.maxLimit;
        });
      }
    },
    showYesterdayBet(val) {
      const minBet = val.minBet;
      const maxBet = val.maxBet;
      if (minBet === 20000) {
        return ` ${minBet} +`;
      }
      return ` ${minBet} ~ ${maxBet}`;
    },
    updateSingInConfiguration() {
      this.$confirm(this.$t('messages.applyChangesMode'), 'Warning', {
        confirmButtonText: this.$t('btn.ok'),
        cancelButtonText: this.$t('btn.cancel'),
        type: 'warning',
      })
        .then(() => {
          console.log(this.signInConfiguration);
          SingIn
            .update(7, { data: this.signInConfiguration })
            .then(response => {
              this.$message({
                type: 'success',
                message: this.$t('messages.infHasUpdated'),
                duration: 1 * 1000,
              });
            });
          // .catch(error => {
          // this.$message({
          //   type: 'error',
          //   message: error,
          //   duration: 1 * 1000,
          // });
          // });
        });
    },
  },
};
</script>

<style lang="scss">
.wrapper-label {
  padding-right: 20px;
}
.right-padding {
  padding-right: 20px;
}
.margin-bottom {
  margin-bottom: 15px;
}
.header-box {
  font-size: 1.3em;
  font-weight: bold;
  border-bottom: 1px solid #f6f6f6;
  padding-bottom: 20px;
  margin-bottom: 23px;
}
.box-right {
  padding-right: 10px;
}
</style>
