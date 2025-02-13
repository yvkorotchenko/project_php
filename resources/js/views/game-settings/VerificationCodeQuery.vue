<template>
  <div className="app-container">
    <el-row>
      <el-col :span="24"><div class="grid-content bg-purple-dark white-box filter-container">
        <!-- Label -->
        <div class="label-title">{{ $t('labels.verificationCodeQuery') }}</div>
        <!-- FORMS -->
        <el-form label-position="right" label-width="170px" class="padding-form">
          <el-form-item :label="$t('labels.verificationCodeQuery')">
            <!-- SELECT -->
            <el-select
              v-model="selectForGetName"
              class="filter-item"
              :placeholder="$t('placeholders.pleaseSelectedItemFilter')"
              clearable
              style="width: 250px;"
            >
              <el-option
                v-for="option in optionsForGet"
                :key="option.value"
                :label="option.label"
                :value="option.value"
              />
            </el-select>
            <!-- SELECT -->
            <el-select
              v-model="selectCountryCode"
              class="filter-item"
              :placeholder="$t('placeholders.pleaseSelectedItemFilter')"
              clearable
              style="width: 160px;"
            >
              <el-option
                v-for="option in optionsCountryCode"
                :key="option.value"
                :label="option.label"
                :value="option.value"
              />
            </el-select>
            <!-- INPUT -->
            <el-input v-model="selectForGetValue" :placeholder="$t('labels.verificationCodeItem2')" class="filter-item" style="width: 250px;" />
            <!-- BUTTON -->
            <PermissionChecker :permissions="['view verification code query query']">
              <el-button type="primary" class="filter-item" @click="getPlayerInformation()">{{ $t('btn.query') }}</el-button>
            </PermissionChecker>
          </el-form-item>
          <el-form-item :label="$t('labels.verificationCode')">
            <el-input v-model="verificationCode" style="width:250px" :placeholder="$t('labels.verificationCodeItem3')" />
          </el-form-item>
        </el-form>
        <!-- -->
      </div></el-col>
    </el-row>
  </div>
</template>

<script>
import VerificationCodeQuery from '@/api/verificationCodeQuery';
import PermissionChecker from '@/components/Permissions/index.vue';
const resource = new VerificationCodeQuery();

export default {
  name: 'VerificationCodeQuery',
  components: {
    PermissionChecker,
  },
  data() {
    return {
      selectForGetName: this.$t('labels.mobileNumber'),
      selectForGetValue: '',
      selectCountryCode: '+86',
      verificationCode: '',
      optionsForGet: [{
        value: 'mobile',
        label: this.$t('labels.mobileNumber'),
      }],
      optionsCountryCode: [{
        value: '+86',
        label: this.$t('labels.countryCode86'),
      },
      {
        value: '+852',
        label: this.$t('labels.countryCode852'),
      },
      {
        value: '+853',
        label: this.$t('labels.countryCode853'),
      },
      {
        value: '+886',
        label: this.$t('labels.countryCode886'),
      }],
    };
  },
  methods: {
    getPlayerInformation() {
      if (this.selectForGetValue !== '' && this.selectCountryCode !== '') {
        const data = {
          'phone': this.selectForGetValue,
          'countryCode': this.selectCountryCode,
          'language': 'en',
        };
        this.loading = true;
        resource.post(data)
          .then(response => {
            this.verificationCode = response.code;
            this.$message({
              type: 'success',
              message: response.message,
              duration: 1 * 1000,
            });
            this.selectCountryCode = '';
            this.selectForGetValue = '';
          });
        this.loading = false;
      } else {
        // this.$message({
        //   type: 'error',
        //   message: this.$t('messages.emptyFields'),
        //   duration: 1 * 1000,
        // });
      }
    },
  },
};
</script>

<style lang="scss" scoped>
.label-title {
  line-height: 42px;
  padding: 0 15px;
  border-bottom: 1px solid #f6f6f6;
  color: #333;
  border-radius: 2px 2px 0 0;
  font-size: 1.1rem;
  margin-bottom: 30px;
}
.padding-20 {
  padding: 20px;
}
.white-box {
  background-color: #fff;
  box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1);
}
.padding-form {
  padding-left: 27px;
}
.white-box[data-v-2431a880] {
  padding: 20px;
  min-height: 87vh;
}
</style>
