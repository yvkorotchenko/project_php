<template>
  <div className="app-container">
    <el-row>
      <el-col :span="24"><div class="grid-content bg-purple-dark bg-white filter-container">
        <el-form ref="newbieMailValidateForm" :model="newbieMailValidateForm" label-width="120px" class="demo-dynamic">
          <el-form-item
            :label="$t('labels.chineseTitle')"
            prop="titleLocal"
            :rules="{required: true, message: $t('placeholders.pleaseEnterTitle'), trigger: 'blur'}"
          >
            <el-input v-model="newbieMailValidateForm.titleLocal" class="input-newbie" />
          </el-form-item>
          <el-form-item
            :label="$t('labels.chineseContent')"
            prop="contentLocal"
            :rules="{required: true, message: $t('placeholders.pleaseEnterContent'), trigger: 'blur'}"
          >
            <el-input v-model="newbieMailValidateForm.contentLocal" class="textarea-newbie" type="textarea" :autosize="{ minRows: 8, maxRows: 8}" />
          </el-form-item>
          <el-form-item
            :label="$t('labels.englishTitle')"
            prop="titleEn"
            :rules="{required: true, message: $t('placeholders.pleaseEnterTitle'), trigger: 'blur'}"
          >
            <el-input v-model="newbieMailValidateForm.titleEn" class="input-newbie" />
          </el-form-item>
          <el-form-item
            :label="$t('labels.englishContent')"
            prop="contentEn"
            :rules="{required: true, message: $t('placeholders.pleaseEnterContent'), trigger: 'blur'}"
          >
            <el-input v-model="newbieMailValidateForm.contentEn" class="textarea-newbie" type="textarea" :autosize="{ minRows: 8, maxRows: 8}" />
          </el-form-item>
          <el-form-item>
            <el-button type="primary" @click="submitForm('newbieMailValidateForm')">{{ $t('btn.confirm') }}</el-button>
            <el-button @click="resetForm('newbieMailValidateForm')">Reset</el-button>
          </el-form-item>
        </el-form>
      </div></el-col>
    </el-row>
  </div>
</template>

<script>
import Resource from '@/api/resource';
const NewbieMailResource = new Resource('newbie-mail');

export default {
  name: 'NewbieMail',
  data() {
    return {
      loading: false,
      newbieMailValidateForm: {
        contentEn: '',
        contentLocal: '',
        state: 1,
        titleEn: '',
        titleLocal: '',
        operatorId: '',
      },
    };
  },
  created() {
    this.newbieMailValidateForm.operatorId = this.$store.getters.userId;
  },
  methods: {
    submitForm(formName) {
      this.$refs[formName].validate((valid) => {
        if (valid) {
          this.loading = true;
          NewbieMailResource.store(this.newbieMailValidateForm)
            .then(response => {
              this.$message({
                type: 'success',
                message: this.$t('messages.messageSent'),
                duration: 1 * 1000,
              });
              this.resetForm('newbieMailValidateForm');
              this.loading = false;
            });
        } else {
          return false;
        }
      });
    },
    resetForm(formName) {
      this.$refs[formName].resetFields();
    },
  },
};
</script>

<style lang="scss" scoped>
  .textarea-newbie{
    width: 600px;
  }
  .input-newbie{
    width: 600px;
  }
</style>
