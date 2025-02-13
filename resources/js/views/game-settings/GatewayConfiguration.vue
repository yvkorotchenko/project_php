<template>
  <div>
    <!-- -->
    <div class="header-box">{{ $t('titles.gatewayConfiguration') }}</div>
    <!-- FORM -->
    <el-form label-position="right" label-width="150px" style="min-width: 250px; max-width: 50%;">
      <!-- -->
      <el-form-item :label="$t('labels.paymentAddress')">
        <el-input />
      </el-form-item>
      <!-- -->
      <el-form-item :label="$t('labels.shareAddress')">
        <el-input />
      </el-form-item>
      <!-- -->
      <el-form-item :label="$t('labels.androidHallHotUpdateAddress')">
        <el-input />
      </el-form-item>
      <!-- -->
      <el-form-item :label="$t('labels.androidLobbyHotReplacementAddress')">
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
      <el-form-item :label="$t('labels.androidLobbyHotUpdateVersionNumber')">
        <el-input />
      </el-form-item>
      <!-- -->
      <el-form-item :label="$t('labels.imageUpload')">
        <el-input />
      </el-form-item>
      <!-- -->
      <el-form-item :label="$t('labels.gameHttpService')">
        <el-input />
      </el-form-item>
      <!-- -->
      <el-form-item :label="$t('labels.label1')">
        <el-input />
      </el-form-item>
      <!-- -->
      <el-form-item :label="$t('labels.label2')">
        <el-input />
      </el-form-item>
      <!-- -->
      <el-form-item :label="$t('labels.threePartyRequestAddress')">
        <el-input />
      </el-form-item>
      <!-- -->
      <el-form-item :label="$t('labels.gameAddress')">
        <el-input type="textarea" rows="6" />
      </el-form-item>
      <!-- -->
      <el-form-item :label="$t('dialog.authority.googleverification')">
        <el-input :placeholder="$t('placeholders.google')" />
      </el-form-item>
      <!-- BTN -->
      <el-form-item>
        <el-button type="success">{{ $t('btn.confirmModification') }}</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
export default {
  name: 'GatewayConfiguration',
  data() {
    return {
      delivery: false,
      formVisible: false,
    };
  },
  methods: {
    getDelivery() {
      return this.delivery;
    },
    setDelivery(val) {
      this.delivery = val;
    },
    onDelivery() {
      this.setDelivery(true);
    },
    offDelivery() {
      this.setDelivery(false);
    },
    getFormVisible() {
      return this.formVisible;
    },
    setFormVisible(val) {
      this.formVisible = val;
    },
    onForm() {
      this.setFormVisible(true);
    },
    offForm() {
      this.setFormVisible(false);
    },
    handlerSwitchForm() {
      this.onForm();
    },
    noSwitch() {
      // hide form
      this.offForm();
      // disable switch
      this.offDelivery();
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
</style>
