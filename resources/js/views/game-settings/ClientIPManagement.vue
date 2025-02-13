<template>
  <div class="app-container">
    <!-- DIALOG -->
    <el-dialog :title="$t('labels.information')" :visible.sync="formVisible" width="40%">
      <div class="label">
        {{ $t('labels.whetherEnableIpRestriction') }}
      </div>
      <!-- FOOTER -->
      <div slot="footer" class="dialog-footer" align="center">
        <el-button type="success" icon="el-icon-plus" @click="handlerConfirmSwitchForm">
          {{ $t('btn.confirm') }}
        </el-button>
        <el-button type="primary" icon="el-icon-close" @click="offForm">
          {{ $t('btn.cancel') }}
        </el-button>
      </div>
    </el-dialog>
    <!--  -->
    <el-form label-width="150px" class="demo-ruleForm">
      <el-form-item v-for="val in list" :key="val.id" :label="val.name">
        <el-switch v-model="val.state" active-text="ON" inactive-text="OFF" active-color="green" @change="handlerSwitchForm(val.id)" />
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import Resource from '@/api/resource';
const iPWhitelistResource = new Resource('clientipmanagement');

export default {
  name: 'ClientIPManagement',
  data() {
    return {
      total: 0,
      loading: false,
      query: {
        page: 1,
        size: 15,
      },
      currentList: {
        id: 1,
        name: 'Whether to enable IP restriction',
        state: false,
      },
      list: null,
      formVisible: false,
    };
  },
  computed: {
    language: function() {
      return this.$store.getters.language;
    },
  },
  created() {
    this.getList();
  },
  methods: {
    async getList(){
      this.loading = true;
      iPWhitelistResource.list(this.query)
        .then(response => {
          this.loading = false;
          this.total = response.meta.total;
          this.list = response.data;
          this.offForm();
        })
        .catch(() => {
          this.loading = false;
          this.offForm();
        });
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
    async handlerConfirmSwitchForm() {
      const allItem = this.list.find((item) => {
        if (item.id === 1) {
          return item;
        }
      });
      // "All" change OFF -> ON
      if (allItem.id === this.currentList.id) {
        this.list.map((item) => {
          if (item.id !== 1) {
            item.state = false;
            this.sendUpdateItem(item.id, item);
          }
        });
      }
      this.list.find((item) => {
        if (item.id === this.currentList.id) {
          item.state = !item.state;
        }
      });
      this.offForm();
      this.loading = true;
      // if "ALL" state = ON and other OFF -> ON => "All" state = OFF
      if (allItem.state && this.currentList.id !== 1 && this.currentList.state) {
        this.list.find((item) => {
          if (item.id === 1) {
            item.state = false;
          }
        });
        this.sendUpdateItem(1, allItem);
      }
      this.sendUpdateItem(this.currentList.id, this.currentList);
    },
    sendUpdateItem(id, currentList) {
      iPWhitelistResource.update(id, currentList)
        .then(response => {
          this.loading = false;
          this.total = response.meta.total;
          this.list = response.data;
          this.offForm();
        })
        .catch(() => {
          this.loading = false;
          this.offForm();
        });
    },
    handlerSwitchForm(id) {
      this.onForm();
      this.currentList = this.list.find((item) => {
        if (item.id === id) {
          item.state = !item.state;
          return item;
        }
      });
    },
    noSwitch() {
      this.offForm();
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
