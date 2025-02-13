<template>
  <div class="app-container">
    <!-- FILTERS -->
    <div class="filter-container">
      <div class="bottom-separate-line">
        {{ $t('titles.testIpWhitelist') }}
      </div>
    </div>
    <!-- FORM -->
    <el-form ref="ipWhiteList" :model="current" label-position="left" label-width="177px" style="width:50%;">
      <el-form-item
        :label="$t('labels.ipWhitelist') + ':'"
        prop="ipWhiteList"
        :rules="[{ required: true, message: $t('messages.notEmpty'), trigger: 'blur' }]"
      >
        <el-input v-model.trim="current.ipWhiteList" type="textarea" rows="7" @input="handlerIpWhiteList" @blur="handlerCleanIpWhiteList" />
      </el-form-item>
      <el-form-item
        :label="$t('dialog.authority.googleverification') + ':'"
        prop="googleVerificationCode"
        :rules="[{ required: true, message: $t('messages.notEmpty'), trigger: 'blur' }]"
      >
        <el-input v-model.trim="current.googleVerificationCode" maxlength="6" @input="handlerGoogleVerificationCode" />
      </el-form-item>
      <!-- Button -->
      <el-form-item>
        <PermissionChecker :permissions="['view menu test ip white list confirm']">
          <el-button type="success" icon="el-icon-plus" plain @click="handlerSaveIpWhiteList">{{ $t('btn.confirmModification') }}</el-button>
        </PermissionChecker>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import Resource from '@/api/resource';
import PermissionChecker from '@/components/Permissions/index.vue';

const testIpWhiteList = new Resource('test-ip-white-list');

export default {
  name: 'TestIPWhiteList',
  components: {
    PermissionChecker,
  },
  data() {
    return {
      current: {
        ipWhiteList: '',
        googleVerificationCode: '',
      },
      currentUser: 0,
      maxLength: 6,
      ipLists: [],
    };
  },
  created() {
    this.currentUser = this.$store.getters.userId || 0;
  },
  methods: {
    handlerIpWhiteList(value) {
      const IPS = /(?:[0-9]{1,3}\.){3}[0-9]{1,3}/gm;
      this.current.ipWhiteList = value.replace(/[^0-9/.,]/g, '');
      this.ipLists = [];
      const ips = this.current.ipWhiteList.split(',');
      ips.forEach((val, index) => {
        val = val.match(IPS);
        if (val !== null) {
          this.ipLists.push(val);
        }
      });
    },
    handlerCleanIpWhiteList() {
      this.current.ipWhiteList = this.ipLists.join(',');
    },
    handlerGoogleVerificationCode(value) {
      this.current.googleVerificationCode = value.replace(/[^0-9]*/g, '');
    },
    handlerSaveIpWhiteList() {
      // validation
      this.$refs['ipWhiteList'].validate((valid) => {
        if (valid) {
          testIpWhiteList.update(this.currentUser, {
            ips: this.ipLists,
            code: this.current.googleVerificationCode,
          }).then(response => {
            this.$message({
              type: 'success',
              message: this.$t('messages.dataAddedSuccessfully'),
              duration: 1 * 1000,
            });
            this.current.ipWhiteList = '';
            this.current.googleVerificationCode = '';
          });
        } else {
          this.$message({
            type: 'warning',
            message: this.$t('messages.errorMessage'),
            duration: 1 * 1000,
          });
          return false;
        }
      });
    },
  },
};
</script>

<style lang="scss">
.app-container {
  padding: 10px;
}
.box-line {
  border: 1px solid rgb(246, 246, 246);
  border-width: 1px 0;
  padding: 21px 10px 10px 10px;
  margin: 10px 0;
}
.bottom-separate-line {
  border: 1px solid rgb(246, 246, 246);
  border-width: 0 0 1px;
  padding-bottom: 10px;
  margin-bottom: 10px;
}
.el-dialog__header {
  border-bottom: 1px solid #eee;
  font-size: 1.3rem;
  overflow: hidden;
  background-color: #F8F8F8;
  border-radius: 2px 2px 0 0;
}
</style>
