<template>
  <div class="app-container">
    <el-form label-position="left" label-width="150px" style="width: 50%">
      <el-form-item :label="$t('labels.serverStatus')" prop="delivery">
        <el-switch v-model="delivery" />
      </el-form-item>
      <el-form-item :label="$t('labels.kickAllPlayers')">
        <el-button type="success">{{ $t('btn.kickAllPlayers') }}</el-button>
      </el-form-item>
      <!-- -->
      <el-form-item :label="$t('labels.forceUpdateAndroidVersion')">
        <el-input />
      </el-form-item>
      <el-form-item :label="$t('labels.forceUpdateAndroidAddress')">
        <el-input />
      </el-form-item>
      <!-- -->
      <el-form-item :label="$t('labels.forceUpdateIOSVersion')">
        <el-input />
      </el-form-item>
      <el-form-item :label="$t('labels.forceUpdateIOSAddress')">
        <el-input />
      </el-form-item>
      <el-form-item :label="$t('labels.forcedUpdatePromptEnglish')">
        <el-input />
      </el-form-item>
      <el-form-item :label="$t('labels.translateOne')">
        <el-input />
      </el-form-item>
      <el-form-item :label="$t('labels.greenMandatoryUpdateAndroidVersion')">
        <el-input />
      </el-form-item>
      <el-form-item :label="$t('labels.greenMandatoryUpdateAndroidAddress')">
        <el-input />
      </el-form-item>
      <!-- -->
      <el-form-item :label="$t('labels.greenMandatoryUpdateIOSVersion')">
        <el-input />
      </el-form-item>
      <el-form-item :label="$t('labels.greenMandatoryUpdateIOSAddress')">
        <el-input />
      </el-form-item>
      <!-- -->
      <el-form-item>
        <el-button type="success">{{ $t('btn.modify') }}</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
export default {
  name: 'StopServiceOperation',
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
.el-dialog__body {
  padding: 23px 20px;
}
.el-dialog__header {
  border-bottom: 1px solid #eee;
  overflow: hidden;
  background-color: #F8F8F8;
  border-radius: 2px 2px 0 0;
}
.label {
  font-size: 1.2rem;
}
</style>
