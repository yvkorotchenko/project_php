<template>
  <div>
    <!-- s -->
    <div class="header-box">
      网关内容配置
    </div>
    <!-- Layout -->
    <div>
      <el-form label-position="right" label-width="270px">
        <el-row>
          <el-col :span="12"><div class="grid-content bg-purple">
            <!-- -->
            <el-form-item :label="$t('labels.androidHallHotUpdateAddress')">
              <el-input />
            </el-form-item>
            <!-- -->
            <el-form-item :label="$t('labels.androidLobbyHotReplacementAddress')">
              <el-input />
            </el-form-item>
            <!-- -->
            <el-form-item :label="$t('labels.androidSubGameHotUpdateAddress')">
              <el-input />
            </el-form-item>
            <!-- -->
            <el-form-item :label="$t('labels.androidSubGameHotUpdateAlternateAddress')">
              <el-input />
            </el-form-item>
            <!-- -->
            <el-form-item :label="$t('labels.iOSHallHotUpdateAddress')">
              <el-input />
            </el-form-item>
            <!-- -->
            <el-form-item :label="$t('labels.iOSHallHotReplacementAddress')">
              <el-input />
            </el-form-item>
            <!-- -->
            <el-form-item :label="$t('labels.iOSSubGameHotUpdateAddress')">
              <el-input />
            </el-form-item>
            <!-- -->
            <el-form-item :label="$t('labels.iOSSubGameHotReplacementAddress')">
              <el-input />
            </el-form-item>
            <!-- -->
            <el-form-item :label="$t('labels.androidLobbyHotUpdateVersionNumber')">
              <el-input />
            </el-form-item>
            <!-- -->
            <el-form-item :label="$t('labels.iOSHallHotUpdateVersionNumber')">
              <el-input />
            </el-form-item>
            <!-- -->
            <el-form-item :label="$t('labels.forceUpdateAndroidVersion')">
              <el-input />
            </el-form-item>
            <!-- -->
            <el-form-item :label="$t('labels.forceUpdateAndroidAddress')">
              <el-input />
            </el-form-item>
            <!-- -->
            <el-form-item :label="$t('labels.forceUpdateIOSVersion')">
              <el-input />
            </el-form-item>
            <!-- -->
            <el-form-item :label="$t('labels.forceUpdateIOSAddress')">
              <el-input />
            </el-form-item>
            <!-- -->
            <el-form-item :label="$t('强制更新提示语')">
              <el-input />
            </el-form-item>
            <!-- -->
            <el-form-item :label="$t('labels.gameHttpService')">
              <el-input />
            </el-form-item>
            <!-- -->
            <el-form-item :label="$t('labels.paymentAddress')">
              <el-input />
            </el-form-item>
            <!-- -->
            <el-form-item :label="$t('labels.gameAddress')">
              <el-input type="textarea" rows="7" />
            </el-form-item>
            <!-- -->
            <el-form-item :label="$t('dialog.authority.googleverification')">
              <el-input :placeholder="$t('placeholders.googleVerification')" />
            </el-form-item>
            <!-- -->
            <el-form-item class="form-btn">
              <el-button type="success">{{ $t('btn.confirmModification') }}</el-button>
            </el-form-item>
          </div></el-col>
          <el-col :span="12"><div class="grid-content bg-purple-light">
            <!-- -->
            <el-form-item :label="$t('')">
              <el-input />
            </el-form-item>
            <!-- -->
            <el-form-item :label="$t('')">
              <el-input />
            </el-form-item>
            <!-- -->
            <el-form-item :label="$t('')">
              <el-input />
            </el-form-item>
            <!-- -->
            <el-form-item :label="$t('')">
              <el-input />
            </el-form-item>
            <!-- -->
            <el-form-item :label="$t('')">
              <el-input />
            </el-form-item>
            <!-- -->
            <el-form-item :label="$t('')">
              <el-input />
            </el-form-item>
            <!-- -->
            <el-form-item :label="$t('')">
              <el-input />
            </el-form-item>
            <!-- -->
            <el-form-item :label="$t('')">
              <el-input />
            </el-form-item>
            <!-- -->
            <el-form-item :label="$t('')">
              <el-input />
            </el-form-item>
            <!-- -->
            <el-form-item :label="$t('')">
              <el-input />
            </el-form-item>
            <!-- -->
            <el-form-item :label="$t('')">
              <el-input />
            </el-form-item>
            <!-- -->
            <el-form-item :label="$t('')">
              <el-input />
            </el-form-item>
            <!-- -->
            <el-form-item :label="$t('')">
              <el-input />
            </el-form-item>
            <!-- -->
            <el-form-item :label="$t('')">
              <el-input />
            </el-form-item><!-- -->
            <el-form-item :label="$t('')">
              <el-input />
            </el-form-item>
            <!-- -->
            <el-form-item :label="$t('')">
              <el-input />
            </el-form-item>

          </div></el-col>
        </el-row>
      </el-form>
    </div>
  </div>
</template>

<script>
// import Pagination from '@/components/Pagination';
// import waves from '@/directive/waves';
//
// import UserResource from '@/api/user';
//
import Resource from '@/api/resource';
//
// get log
const operationLogResource = new Resource('operation-log'); // this api url in resource
// get users
// const userResource = new UserResource();
// get prmission
const permissionResource = new Resource('permissions');

export default {
  name: 'TestGatewayConfiguration',
  data() {
    return {
      delivery: false,
      formVisible: false,
      total: 0,
      list: null,
      loading: false,
      query: {
        page: 1,
        limit: 15,
        keyword: '',
        user: '',
      },
      users: ['admin', 'manager', 'editor', 'user', 'visitor', 'root'],
      permissions: [],
      menuPermissions: [],
      otherPermissions: [],
      startDateAndTime: '',
      endDateAndTime: '',
      searchParam: '',
    };
  },
  created() {
    this.getList();
  },
  methods: {
    async getPermissions() {
      const { data } = await permissionResource.list({});
      const { all, menu, other } = this.classifyPermissions(data);
      this.permissions = all;
      this.menuPermissions = menu;
      this.otherPermissions = other;
    },
    async getList() {
      const { limit, page } = this.query;
      this.loading = true;
      const { data, meta } = await operationLogResource.list(this.query);
      this.list = data;
      this.list.forEach((element, index) => {
        element['index'] = (page - 1) * limit + index + 1;
      });
      this.total = meta.total;
      this.loading = false;
    },
    handleFilter() {
      this.query.page = 1;
      this.getList();
    },
    getFormVisible() {
      return this.formVisible;
    },
    setFormVisible(val) {
      this.formVisible = val;
    },
    onFormVisible() {
      this.setFormVisible(true);
    },
    offFormVisible() {
      this.setFormVisible(false);
    },
    showForm() {
      this.onFormVisible();
      // console.log('hello');
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
    border-bottom: 1px solid #f6f6f6;
    padding-bottom: 20px;
    margin-bottom: 23px;
  }
  .box-right {
    padding-right: 10px;
  }
  .select_box {
    padding-bottom: 27px;
  }
  .form-btn .el-form-item__content {
    margin-left: 152px!important;
  }
</style>
