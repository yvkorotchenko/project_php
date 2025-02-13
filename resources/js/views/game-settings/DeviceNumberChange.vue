<template>
  <div className="app-container">
    <el-row>
      <el-col :span="24">
        <div class="grid-content bg-purple-dark white-box filter-container">
          <!-- Label -->
          <div class="label-title">{{ $t('labels.deviceNumChange') }}</div>
          <!-- FORMS -->
          <el-form
            ref="current"
            :model="current"
            label-position="left"
            label-width="160px"
            class="padding-form"
          >
            <el-form-item
              prop="userId"
              :label="$t('labels.playerId')"
              :rules="[{ required: true, validator: handlerValidateUserIdIsset, trigger: ['change', 'blur'] }]"
            >
              <el-input
                v-model.number="current.userId"
                style="width:350px"
                @change="handlerChangePlayerId"
              />
            </el-form-item>
            <el-form-item
              prop="deviceId"
              :label="$t('labels.deviceNumber')"
              :rules="[{ required: true, message: getMessage('notBeEmpty'), trigger: 'blur' }]"
            >
              <el-input v-model="current.deviceId" style="width:350px" />
            </el-form-item>
            <el-form-item
              prop="google2faCode"
              :label="$t('dialog.authority.googleverification')"
              :rules="[{ required: true, pattern: /^[0-9]*$/, message: getMessage('pleaseEnterNumber'), trigger: 'blur' }]"
            >
              <el-input
                v-model="current.google2faCode"
                style="width:350px"
                :placeholder="$t('placeholders.googleVerification')"
              />
            </el-form-item>
            <!-- FORM BUTTON -->
            <el-form-item>
              <el-button
                type="success"
                size="small"
                plain
                @click="handlerChangeDeviceNumber"
              >
                {{ $t('btn.confirm') }}
              </el-button>
            </el-form-item>
          </el-form>
        </div>
      </el-col>
    </el-row>
  </div>
</template>

<script>
import Resource from '@/api/resource';
// Permission checking
// import PermissionChecker from '@/components/Permissions/index.vue';
import havePermission from '@/utils/permission'; // test permvision

const deviceNumberChange = new Resource('change-device-number');

export default {
  name: 'DeviceNumberChange',
  components: {
    // PermissionChecker,
  },
  data() {
    return {
      userId: null,
      current: {
        userId: undefined,
        deviceId: undefined,
        google2faCode: undefined,
      },
    };
  },
  created() {
    this.userId = this.$store.getters.userId;
    console.log(this.currentUserId);
  },
  methods: {
    // use have permission
    handleHavePermission(permission) {
      return havePermission(permission);
    },
    handlerResetCurrentData() {
      this.current = {
        userId: undefined,
        deviceId: undefined,
        google2faCode: undefined,
      };
    },
    getMessage(value, key = 'messages') {
      return this.$t(key + '.' + value);
    },
    handlerChangeDeviceNumber() {
      this.$refs['current'].validate(valid => {
        if (valid) {
          deviceNumberChange
            .update(this.userId, this.current)
            .then(response => {
              this.$message({
                type: 'success',
                message: this.$t('messages.infUpdated'),
                duration: 1 * 1000,
              });
              this.handlerResetCurrentData();
            });
        }
        console.log(this.$t('messages.infUpdated'));
      });
    },
    handlerChangePlayerId() {
      // console.log('Change value in player id');
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
</style>
