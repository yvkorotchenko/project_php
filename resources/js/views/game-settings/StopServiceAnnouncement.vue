<template>
  <div class="app-container">
    <el-form
      ref="current"
      :model="announcement"
      label-position="left"
      label-width="165px"
      style="width: 50%"
    >
      <el-row>
        <el-col :span="12">
          <el-form-item
            prop="from"
            :label="$t('labels.announcementStart')"
            :rules="[{ required: true, message: this.$t('messages.notBeEmpty'), trigger: 'blur' }]"
          >
            <el-date-picker
              v-model="announcement.from"
              class="filter-item"
              type="datetime"
              :placeholder="$t('placeholders.formatData')"
              format="yyyy-MM-dd hh:mm:ss"
              value-format="yyyy-MM-dd hh:mm:ss"
            />
          </el-form-item>
        </el-col>
        <el-col :span="12">
          <el-form-item
            prop="to"
            :label="$t('labels.announcementExpired')"
            :rules="[{ required: true, message: this.$t('messages.notBeEmpty'), trigger: 'blur' }]"
          >
            <el-date-picker
              v-model="announcement.to"
              class="filter-item"
              type="datetime"
              :placeholder="$t('placeholders.formatData')"
              format="yyyy-MM-dd hh:mm:ss"
              value-format="yyyy-MM-dd hh:mm:ss"
            />
          </el-form-item>
        </el-col>
      </el-row>
      <el-form-item
        prop="contentEn"
        :label="$t('labels.englishAnnouncementContent')"
        :rules="[{ required: true, message: this.$t('messages.notBeEmpty'), trigger: 'blur' }]"
      >
        <el-input
          v-model="announcement.contentEn"
          type="textarea"
          rows="3"
          :placeholder="$t('placeholders.pleaseEnterContent')"
        />
      </el-form-item>
      <el-form-item
        prop="contentLocal"
        :label="$t('labels.thaiLanguageAnnouncementContent')"
        :rules="[{ required: true, message: this.$t('messages.notBeEmpty'), trigger: 'blur' }]"
      >
        <el-input
          v-model="announcement.contentLocal"
          type="textarea"
          rows="3"
          :placeholder="$t('placeholders.pleaseEnterContent')"
        />
      </el-form-item>
      <!-- -->
      <el-form-item
        prop="google2faCode"
        :label="$t('dialog.authority.googleverification')"
        :rules="[{ required: true, message: this.$t('messages.notBeEmpty'), trigger: 'blur' }]"
      >
        <el-input
          v-model="announcement.google2faCode"
          :placeholder="$t('placeholders.google')"
        />
      </el-form-item>
      <!-- -->
      <el-form-item>
        <PermissionChecker :permissions="['view menu stop service announcement update']">
          <el-button plain size="small" type="success" @click="getCurrentUser">{{ $t('btn.modifyTheSuspensionOfServiceAnnouncement') }}</el-button>
        </PermissionChecker>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import Resource from '@/api/resource';
import PermissionChecker from '@/components/Permissions/index.vue';
const stopServiceAnnouncementResource = new Resource('stop-service-announcement');

export default {
  name: 'StopServiceAnnouncement',
  components: {
    PermissionChecker,
  },
  data() {
    return {
      loading: false,
      announcement: {
        contentEn: '',
        contentLocal: '',
        from: '',
        to: '',
        google2faCode: '',
        titleEn: 'System maintenance notice',
        titleLocal: '停服公告',
        userId: '',
      },
    };
  },
  created() {
    this.announcement.userId = this.$store.getters.userId;
  },
  methods: {
    getCurrentUser(){
      this.$refs['current'].validate((valid) => {
        if (valid) {
          this.loading = true;
          stopServiceAnnouncementResource.update(this.announcement.userId, this.announcement)
            .then(response => {
              console.log('answer::', response);
              this.$message({
                type: 'success',
                message: response.data.message,
                duration: 1 * 1000,
              });
              this.announcement = {
                contentEn: '',
                contentLocal: '',
                from: '',
                to: '',
                google2faCode: '',
                titleEn: 'System maintenance notice',
                titleLocal: '停服公告',
                userId: this.announcement.userId,
              };
            });
          this.loading = false;
        } else {
          return false;
        }
      });
    },
    handlerValidateUserIdIsset(rule, value, callback) {
      const pattern = /^[0-9]*$/;
      if (value === '' || value === undefined) {
        callback(new Error(this.$t('messages.notBeEmpty')));
      } else if (!pattern.test(value)) {
        callback(new Error(this.$t('messages.pleaseEnterNumber')));
      } else {
        callback();
      }
    },
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
.left-padding {
  padding-left: 20px;
}
.margin-bottom {
  margin-bottom: 15px;
}
</style>
