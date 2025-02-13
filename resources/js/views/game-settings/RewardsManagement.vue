<template>
  <div>
    <PermissionChecker :permissions="[viewPermission.listDelete, viewPermission.create]">
      <div class="filter-item">
        <el-button plain type="danger" size="small" @click="handlerDeleteMultipleSelectionRewards">{{ $t('btn.delete') }}</el-button>
        <el-button plain type="success" size="small" @click="handlerAddReward">{{ $t('btn.add') }}</el-button>
      </div>
    </PermissionChecker>
    <!-- Separate line -->
    <div class="separate-line" />
    <!-- TABLE -->
    <el-table
      v-loading="loading"
      :data="list"
      style="width: 100%"
      height="650"
      border
      fit
      highlight-current-row
      @selection-change="handlerMultipleSelectionRewards"
    >
      <el-table-column
        align="center"
        type="selection"
        width="50"
      />
      <!-- id -->
      <el-table-column
        align="center"
        prop="id"
        width="77"
        :label="$t('labels.id')"
      >
        <template slot-scope="scope">
          {{ scope.row.id }}
        </template>
      </el-table-column>
      <el-table-column
        align="center"
        prop="title"
        :label="$t('labels.name')"
      >
        <template slot-scope="scope">
          {{ scope.row.name }}
        </template>
      </el-table-column>
      <el-table-column
        align="center"
        prop="imageEn"
        :label="$t('labels.imageEN')"
      >
        <template slot-scope="scope">
          <el-tag v-if="scope.row.imageEn" type="success" plain>{{ $t('btn.yes') }}</el-tag>
          <el-tag v-else type="danger" plain>{{ $t('btn.no') }}</el-tag>
        </template>
      </el-table-column>
      <el-table-column
        align="center"
        prop="imageLocal"
        :label="$t('labels.imageZH')"
      >
        <template slot-scope="scope">
          <el-tag v-if="scope.row.imageLocal" type="success" plain>{{ $t('btn.yes') }}</el-tag>
          <el-tag v-else type="danger" plain>{{ $t('btn.no') }}</el-tag>
        </template>
      </el-table-column>
      <el-table-column
        align="center"
        prop="created"
        :label="$t('labels.date')"
      >
        <template slot-scope="scope">
          {{ scope.row.created }}
        </template>
      </el-table-column>
      <!-- Buttons -->
      <PermissionChecker :permissions="[viewPermission.delete, viewPermission.edit]">
        <el-table-column
          fixed="right"
          width="187"
          align="center"
          prop="actions"
          :label="$t('labels.action')"
        >
          <template slot-scope="scope">
            <PermissionChecker :permissions="[viewPermission.delete]">
              <el-button type="danger" plain size="small" @click="handlerDeleteRewards(scope.row.id)">{{ $t('btn.delete') }}</el-button>
            </PermissionChecker>
            <PermissionChecker :permissions="[viewPermission.edit]">
              <el-button type="primary" plain size="small" @click="handlerEditRewards(scope.row.id)">{{ $t('btn.edit') }}</el-button>
            </PermissionChecker>
          </template>
        </el-table-column>
      </PermissionChecker>
    </el-table>
    <Pagination
      v-show="total>0"
      :total="total"
      :page.sync="query.page"
      :limit.sync="query.limit"
      :page-sizes="[10, 15, 25, 45, 65, 85, 130, 160, 250, 350]"
      @pagination="getRewards"
    />
    <!-- PAGINATION -->
    <!-- DIALOG -->
    <el-dialog :title="title" :visible.sync="formVisible" :before-close="handlerCloseForm">
      <el-form
        ref="rewards"
        :model="current"
        label-position="right"
        label-width="177px"
      >
        <!-- NAME -->
        <el-form-item :label="$t('labels.name')">
          <el-input v-model="current.name" />
        </el-form-item>
        <!-- Separate Line -->
        <div class="wrapper-separate-line">
          <div class="bottom-line margin-bottom-20" />
        </div>
        <!-- IMAGE EN -->
        <el-form-item :label="$t('labels.picEN')">
          <el-input v-model="inputImages.en" />
          <!-- START WRAPPER IMAGE EN -->
          <el-row :gutter="32">
            <!-- IMAGE -->
            <el-col :xs="36" :sm="36" :lg="12">
              <div class="wrapper-image">
                <i
                  v-if="current.imageEn === ''"
                  class="el-icon-camera-solid"
                />
                <img
                  v-else
                  :src="current.imageEn"
                >
              </div>
            </el-col>
            <!-- BUTTON -->
            <el-col :xs="36" :sm="36" :lg="12">
              <div class="wrapper-upload-picture">
                <el-button type="success" plain @click="handlerSelectImage('img-en')">
                  {{ $t('btn.uploadPicture') }}
                </el-button>
                <!-- INPUT FILES -->
                <input ref="img-en" name="image-en" type="file" class="no-display" @change="handlerSelectedImage('img-en')">
              </div>
            </el-col>
          </el-row>
          <!-- END WRAPPER IMAGE EN -->
        </el-form-item>
        <el-form-item :label="$t('labels.picZH')">
          <el-input v-model="inputImages.zh" />
          <!-- START WRAPPER  IMAGE ZH -->
          <el-row :gutter="32">
            <el-col :xs="36" :sm="36" :lg="12">
              <div class="wrapper-image">
                <i
                  v-if="current.imageLocal === ''"
                  class="el-icon-camera-solid"
                />
                <img
                  v-else
                  :src="current.imageLocal"
                >
              </div>
            </el-col>
            <!-- BUTTON -->
            <el-col :xs="36" :sm="36" :lg="12">
              <div class="wrapper-upload-picture">
                <el-button type="success" plain @click="handlerSelectImage('img-sc')">
                  {{ $t('btn.uploadPicture') }}
                </el-button>
                <!-- INPUT FILES -->
                <input ref="img-sc" name="image-sc" type="file" class="no-display" @change="handlerSelectedImage('img-sc')">
              </div>
            </el-col>
          </el-row>
          <!-- END WRAPPER IMAGE ZH -->
        </el-form-item>
        <!-- IMAGE ZH -->
        <!-- FORM BUTTONS -->
        <el-form-item style="padding: 20px 0 0 11%;">
          <el-button plain type="success" @click="handlerCreateReward">{{ $t('btn.confirm') }}</el-button>
          <el-button plain type="primary" @click="handlerCloseForm">{{ $t('btn.cancel') }}</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
    <PermissionChecker :permissions="['edit']">
      <div />
    </PermissionChecker>
  </div>
</template>

<script>
import Resource from '@/api/resource';
import PermissionChecker from '@/components/Permissions/index.vue';
import UploadImage from '@/utils/uploadImage';
import Pagination from '@/components/Pagination';
import viewsPermissions from '@/viewsPermissions.js';

const rewards = new Resource('rewards-management');

export default {
  name: 'RewardsManagement',
  components: {
    Pagination,
    PermissionChecker,
  },
  data() {
    return {
      viewPermission: viewsPermissions.rewardsManagement.permissions.controls,
      loading: false,
      multipleSelectionRewards: [],
      inputImages: {
        'en': '',
        'zh': '',
      },
      cacheImage: {
        'en': '',
        'zh': '',
      },
      current: {
        id: undefined,
        imageEn: '',
        imageLocal: '',
        name: '',
        created: '',
      },
      list: [],
      query: {
        limit: 20,
        page: 1,
      },
      total: 0,
      formVisible: false,
      title: '',
      // for upload image
      files: [],
      uploadImageUri: '/rewards-management-upload-images/',
    };
  },
  created() {
    this.getRewards();
  },
  methods: {
    async getRewards() {
      const { limit, page } = this.query;
      this.loading = true;
      const { data, meta } = await rewards.list(this.query);
      this.list = data;
      this.list.forEach((item, index) => {
        item['index'] = (page - 1) * limit + index + 1;
      });
      this.total = meta.total;
      this.loading = false;
    },
    handlerDeleteMultipleSelectionRewards() {
      const ids = [];
      const links = [];
      if (this.multipleSelectionRewards.length === 0) {
        // this.$message({
        //   type: 'error',
        //   message: this.$t('messages.noDeleteItems'),
        //   duration: 1 * 1000,
        // });
      } else {
        this.multipleSelectionRewards.forEach((item, index) => {
          ids.push(item.id);
          links.push({
            'en': item.imageEn,
            'zh': item.imageLocal,
          });
        });
        // CONFIRM MESSAGE
        this.$confirm(this.$t('messages.deleteConfirmMessage') + '?', 'Warning', {
          confirmButtonText: this.$t('btn.ok'),
          cancelButtonText: this.$t('btn.cancel'),
          type: 'warning',
        })
          .then(() => {
            rewards
              .del(ids.toString(), links)
              .then(() => {
                this.$message({
                  type: 'success',
                  message: this.$t('messages.delCompleteUser'),
                  duration: 1 * 1000,
                });
              })
              .finally(() => {
                this.getRewards();
              });
          })
          .catch(() => {
            this.$message({
              type: 'info',
              message: this.$t('messages.delCancelUser'),
              duration: 1 * 1000,
            });
          });
      }
    },
    handlerAddReward() {
      this.title = 'Add';
      this.formVisible = true;
    },
    handlerMultipleSelectionRewards(val) {
      this.multipleSelectionRewards = val;
    },
    handlerDeleteRewards(id) {
      this.$confirm(this.$t('messages.deleteConfirmMessage') + '?', 'Warning', {
        confirmButtonText: this.$t('btn.ok'),
        cancelButtonText: this.$t('btn.cancel'),
      })
        .then(() => {
          const row = this.list.find(item => item.id === id);
          const links = {};
          // if set image for delete
          let items = false;
          if (row.imageEn !== '') {
            links.en = row.imageEn;
            items = true;
          }
          if (row.imageLocal !== '') {
            links.zh = row.imageLocal;
            items = true;
          }
          if (items) {
            rewards
              .del(id, links)
              .then(() => {
                this.$message({
                  type: 'success',
                  message: this.$t('messages.delCompleteUser'),
                  duration: 1 * 1000,
                });
              }).finally(() => {
                this.handlerResetCurrentData();
                this.getRewards();
              });
          } else {
            rewards
              .destroy(id)
              .then(() => {
                this.$message({
                  type: 'success',
                  message: this.$t('messages.delCompleteUser'),
                  duration: 1 * 1000,
                });
              }).finally(() => {
                this.handlerResetCurrentData();
                this.getRewards();
              });
          }
        })
        .catch(() => {
          this.$message({
            type: 'info',
            message: this.$t('messages.delCancelUser'),
            duration: 1 * 1000,
          });
        });
    },
    handlerEditRewards(id) {
      const row = this.list.find((item) => item.id === id);
      this.title = this.$t('btn.update');
      this.formVisible = true;
      // edit current reward
      this.current = row;
      // set image url
      this.inputImages.en = this.current.imageEn;
      this.inputImages.zh = this.current.imageLocal;
    },
    handlerResetCurrentData() {
      this.current = {
        id: undefined,
        imageEn: '',
        imageLocal: '',
        name: '',
        created: '',
      };
      // reset url in input
      this.inputImages = {
        'en': '',
        'zh': '',
      };
      //
      this.cacheImage = {
        'en': '',
        'zh': '',
      };
    },
    handlerSelectedImage(name) {
      const img = this.files[name] = this.$refs[name].files[0];
      const type = img.type;
      // upload files
      UploadImage(this.uploadImageUri, type, img)
        .then(response => {
          const { data } = response;
          const url = data.url;
          if (name === 'img-en') {
            this.inputImages.en = url;
            this.current.imageEn = url;
            this.cacheImage.en = url;
          }
          if (name === 'img-sc') {
            this.inputImages.zh = url;
            this.current.imageLocal = url;
            this.cacheImage.zh = url;
          }
        });
    },
    handlerSelectImage(name) {
      this.$refs[name].click();
    },
    handlerCloseForm() {
      const links = {};
      let del = false;
      this.formVisible = false;
      // delete image if save
      if (this.cacheImage.en !== '') {
        links.en = this.cacheImage.en;
        del = true;
      }
      if (this.cacheImage.zh !== '') {
        links.zh = this.cacheImage.zh;
        del = true;
      }
      if (del) {
        rewards.del(0, links)
          .then(() => {
            this.$message({
              type: 'success',
              message: this.$t('messages.selectedImageBeenDeleted'),
              duration: 1 * 1000,
            });
          });
      }
      this.handlerResetCurrentData();
    },
    handlerCreateReward() {
      if (this.current.id === undefined) {
        rewards
          .store(this.current)
          .then(response => {
            this.$message({
              type: 'success',
              message: this.$t('messages.dataAddedSuccessfully'),
              duration: 1 * 1000,
            });
          })
          .catch(error => {
            console.log(error);
            // this.$message({
            //   type: 'error',
            //   message: error.error,
            //   duration: 1 * 1000,
            // });
          })
          .finally(() => {
            this.formVisible = false;
            this.handlerResetCurrentData();
            this.getRewards();
          });
      } else {
        if (this.current.index) {
          delete this.current.index;
        }
        rewards
          .update(this.current.id, this.current)
          .then(response => {
            this.$message({
              type: 'success',
              message: this.$t('messages.dataAddedSuccessfully'),
            });
          })
          .catch(error => {
            console.log(error);
            // this.$message({
            //   type: 'error',
            //   message: error.error,
            //   duration: 1 * 1000,
            // });
          })
          .finally(() => {
            this.formVisible = false;
            this.handlerResetCurrentData();
            this.getRewards();
          });
      }
    },
  },
};
</script>

<style lang="scss">
.no-display {
  display: none;
}
.separate-line {
  padding-top: 20px;
  border-bottom: 1px solid #e6e6e6;
}
/* dialog header */
.el-dialog__header {
  border-bottom: 1px solid #d5d5d5;
  background-color: #f6f6f6;
  padding-bottom: 17px;
}
.wrapper-separate-line {
  text-align: center;
}
.margin-bottom-20 {
  margin-bottom: 20px;
}
.bottom-line {
  border-bottom: 1px solid #dcdfe6;
}
.wrapper-image {
  border-radius: 7px;
  background: #e6e6e6cc;
  margin-top: 23px;
  height: 20vh;
  text-align: center;
  position: relative;
  i {
    font-size: 3.7rem;
    position: absolute;
    left: 41%;
    top: 32%;
  }
  img {
    width: 100%;
    height: 100%;
    border-radius: 7px;
  }
}
.wrapper-upload-picture {
  padding-top:30%;
}
.padding-left-20p {
  padding-left: 20%;
}
</style>
