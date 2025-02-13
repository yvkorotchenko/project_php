<template>
  <div class="app-container">
    <div class="filter-item">
      <el-button plain type="danger" size="small" @click="handlerDelete('multiple')">{{ $t('btn.delete') }}</el-button>
      <el-button plain type="success" size="small" @click="handlerShowCreateFormAvatar">{{ $t('btn.add') }}</el-button>
    </div>
    <!-- Separate line -->
    <div class="separate-line" />
    <!-- Lists Icons -->
    <el-table
      v-loading="loading"
      :data="lists"
      style="width: 100%"
      height="650"
      border
      fit
      highlight-current-row
      @selection-change="handlerMultipleSelectionAvatars"
    >
      <el-table-column
        align="center"
        type="selection"
        width="50"
      />
      <!-- Id -->
      <el-table-column
        prop="id"
        :label="tableItems.id"
        width="117px"
        align="center"
      >
        <template slot-scope="scope">
          <span>{{ scope.row.id }}</span>
        </template>
      </el-table-column>
      <!-- Name -->
      <el-table-column
        align="center"
        prop="name"
        :label="tableItems.name"
      >
        <template slot-scope="scope">
          <span class="avatar-name">{{ scope.row.name }}</span>
        </template>
      </el-table-column>
      <!-- Url -->
      <el-table-column
        align="center"
        prop="img_url"
        :label="tableItems.img_url"
      >
        <template slot-scope="scope">
          <div class="wrapper-avatar">
            <img :src="scope.row.img_url">
          </div>
        </template>
      </el-table-column>
      <!-- Sort -->
      <el-table-column
        align="center"
        prop="position"
        :label="tableItems.position"
      >
        <template slot-scope="scope">
          <span class="avatar-name">{{ scope.row.position }}</span>
        </template>
      </el-table-column>
      <!-- Display -->
      <el-table-column
        align="center"
        prop="visible"
        :label="tableItems.visible"
      >
        <template slot-scope="scope">
          <span>
            <el-switch
              v-if="scope.row.id !== 1"
              v-model="scope.row.visible"
              style="display: block;"
              active-color="#13ce66"
              inactive-color="#ff4949"
              :active-text="$t('btn.on')"
              :inactive-text="$t('btn.off')"
              @change="() => changeDisplayAvatar(scope.row)"
            />
          </span>
        </template>
      </el-table-column>
      <!-- Buttons -->
      <el-table-column
        align="center"
        fixed="right"
        :label="$t('labels.operations')"
        width="217px"
      >
        <template slot-scope="scope">
          <el-button
            type="primary"
            plain
            @click="handlerShowEditDialog(scope.row.id, scope.row)"
          >{{ $t('btn.edit') }}</el-button>
          <el-button
            v-if="scope.row.id !== 1"
            type="danger"
            plain
            @click="handlerDelete(scope.row)"
          >{{ $t('btn.delete') }}</el-button>
        </template>
      </el-table-column>
    </el-table>
    <!-- Lists pages -->
    <Pagination
      v-show="total>0"
      :total="total"
      :page.sync="query.page"
      :limit.sync="query.limit"
      :page-sizes="[10, 15, 20, 30, 70, 90, 150, 200, 250]"
      @pagination="getAvatars"
    />
    <!-- Dialog -->
    <el-dialog
      :title="title"
      :visible.sync="formVisible"
      :before-close="handlerCloseAvatarForm"
    >
      <el-form
        v-if="current !== null"
        ref="formAvatar"
        :model="current"
        label-position="right"
        class="dialog-form"
      >
        <el-form-item
          v-if="current.id !== 1"
          :label="tableItems.name"
          prop="name"
          :rules="[{ required: true, message: $t('messages.notEmpty'), trigger: 'blur' }]"
        >
          <el-input v-model.trim="current.name" size="medium" />
        </el-form-item>
        <!-- images -->
        <el-form-item
          :label="$t('labels.pic')"
          prop="img_url"
          :rules="[{ required: true, message: $t('messages.notBeEmpty'), trigger: 'blur' }]"
        >
          <el-row>
            <el-col>
              <div v-if="current.img_url !== ''" class="wrapper-img">
                <img :src="current.img_url">
              </div>
              <div v-else class="box-image" @click="handlerChooseImage">
                <i class="el-icon-camera-solid" />
              </div>
            </el-col>
          </el-row>
        </el-form-item>
        <el-form-item
          :label="$t('labels.imgUrl')"
        >
          <el-row>
            <el-col>
              <el-input v-model="currentImgUrl" size="medium" />
            </el-col>
          </el-row>
        </el-form-item>
        <el-form-item>
          <el-button
            v-if="current.img_url === ''"
            type="primary"
            plain
            class="btn-block el-btn-big"
            @click="handlerChooseImage"
          >{{ $t('btn.uploadPicture') }}</el-button>
          <el-button
            v-if="current.img_url !== ''"
            type="danger"
            plain
            class="btn-block el-btn-big"
            @click="handlerDeleteAvatarImage"
          >{{ $t('btn.deletePicture') }}</el-button>
          <!-- SELECT INPUT FILE -->
          <input ref="avatar" type="file" name="avatar" class="no-display" @change="handlerSelectedAvatarImage">
        </el-form-item>
        <el-form-item>
          <el-input-number v-model="current.position" size="medium" :min="1" style="width: 100%" />
        </el-form-item>
        <br>
        <el-form-item>
          <el-switch
            v-if="current.id !== 1"
            v-model="current.visible"
            style="display: block"
            active-color="#13ce66"
            inactive-color="#ff4949"
            :active-text="$t('btn.on')"
            :inactive-text="$t('btn.off')"
          />
        </el-form-item>
        <!-- footer -->
        <el-form-item class="form-footer">
          <el-button type="success" plain class="btn-block el-btn-big" @click="handlerSaveAvatar">{{ $t('btn.confirm') }}</el-button>
          <br>
          <el-button type="primary" plain class="btn-block el-btn-big" @click="handlerCloseAvatarForm">{{ $t('btn.cancel') }}</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
</template>

<script>
import Pagination from '@/components/Pagination';
import Resource from '@/api/resource';
import UploadImage from '@/utils/uploadImage';

const avatars = new Resource('element-ui-avatar');

export default {
  name: 'Avatars',
  components: {
    Pagination,
  },
  data() {
    const Items = {
      id: this.$t('labels.id'),
      name: this.$t('labels.name'),
      img_url: this.$t('labels.url'),
      position: this.$t('labels.sort'),
      visible: this.$t('labels.display'),
    };
    return {
      loading: false,
      multipleSelectionAvatars: {
        ids: [],
        urls: [],
      },
      current: {
        id: undefined,
        name: '',
        img_url: '',
        position: 0,
        visible: true,
      },
      lists: [],
      visibles: [],
      query: {
        limit: 20,
        page: 1,
      },
      total: 0,
      formVisible: false,
      toggle: true,
      title: '',
      tableItems: Items,
      avatar: '',
      uploadAvatarUri: '/user-avatar-images/',
      currentImgUrl: '',
      offEditMode: true,
    };
  },
  created() {
    this.getAvatars();
  },
  methods: {
    pushObj(id, val) {
      const obj = {};
      obj[id] = val;
      return obj;
    },
    async getAvatars() {
      const { limit, page } = this.query;
      this.loading = true;
      const { data } = await avatars.list(this.query);
      this.lists = data.data;
      this.visibles = [];
      this.lists.forEach((item, index) => {
        item['index'] = (page - 1) * limit + index + 1;
        this.visibles.push(this.pushObj(item.id, item.visible));
      });
      this.total = data.total;
      this.loading = false;
    },
    changeDisplayAvatar(item) {
      if (item.id !== undefined) {
        avatars
          .update(item.id, item)
          .then(response => {
            this.$message({
              type: 'success',
              message: this.$t('messages.dataAddedSuccessfully'),
            });
          });
      }
    },
    handlerShowEditDialog(id, currentAvatar){
      this.title = this.$t('titles.editAvatar');
      this.formVisible = true;
      this.offEditMode = false;
      this.current = currentAvatar;
      this.currentImgUrl = currentAvatar.img_url;
    },
    handlerShowCreateFormAvatar() {
      this.title = this.$t('titles.addAvatar');
      this.formVisible = true;
    },
    handlerEditAvatar(id){},
    handlerDelete(item) {
      if (item !== 'multiple') {
        this.handlerDeleteAvatar(
          item.id,
          [item.img_url]
        );
      } else {
        if (this.multipleSelectionAvatars.ids.length === 0) {
          // this.$message({
          //   type: 'error',
          //   message: this.$t('messages.noDeleteItems'),
          //   duration: 1 * 1000,
          // });
        } else {
          this.handlerDeleteAvatar(
            this.multipleSelectionAvatars.ids.join(),
            this.multipleSelectionAvatars.urls
          );
        }
      }
    },
    handlerDeleteAvatar(ids, urls){
      this.$confirm(this.$t('messages.deleteConfirmMessage') + '?', 'Warning', {
        confirmButtonText: this.$t('btn.ok'),
        cancelButtonText: this.$t('btn.cancel'),
        type: 'warning',
      })
        .then(() => {
          avatars
            .del(ids, {
              urls: urls,
            })
            .then(() => {
              this.$message({
                type: 'success',
                message: this.$t('messages.delCompleteUser'),
              });
            })
            .finally(() => {
              this.handlerUpdateDate();
            });
        })
        .catch(() => {
          this.$message({
            type: 'info',
            message: this.$t('messages.delCancelUser'),
          });
        });
    },
    handlerDeleteAvatarImage() {
      if (this.current.img_url !== '') {
        avatars
          .del(0, {
            urls: [this.current.img_url],
          }).then(response => {
            const { data } = response;
            if (data.success === 1) {
              this.$message({
                type: 'success',
                message: this.$t('messages.delCompleteUser'),
              });
            } else {
              // this.$message({
              //   type: 'error',
              //   message: data.message,
              //   duration: 1 * 1000,
              // });
            }
            // clear data
            this.avatar = '';
            this.current.img_url = '';
            this.currentImgUrl = '';
          });
      }
    },
    handlerClearForm() {
      this.handlerResetDataCurrentAvatar();
      this.currentImgUrl = '';
    },
    handlerResetDataCurrentAvatar() {
      this.current = {
        id: undefined,
        name: '',
        img_url: '',
        position: 0,
        visible: true,
      };
    },
    convertBoolToInt(val) {
      return (val) ? 1 : 0;
    },
    convertIntToBool(val) {
      return (val === 1);
    },
    // upload image
    handlerSelectedAvatarImage() {
      const img = this.avatar = this.$refs['avatar'].files[0];
      const type = img.type;
      UploadImage(this.uploadAvatarUri, type, img).then(response => {
        const { data } = response;
        this.currentImgUrl = data.url;
        this.current.img_url = data.url;
        // clear selected avatar
        this.avatar = '';
      });
    },
    handlerChooseImage() {
      this.$refs['avatar'].click();
    },
    handlerCloseAvatarForm() {
      this.formVisible = false;
      if (this.offEditMode) {
        if (this.current.img_url !== '') {
          avatars.del(0, { urls: this.current.img_url })
            .then(response => {
              if (response.data.success === 1) {
                this.$message({
                  type: 'success',
                  message: this.$t('messages.delCompleteUser'),
                });
                this.handlerResetDataCurrentAvatar();
                this.currentImgUrl = '';
                this.avatar = '';
              }
            });
        }
      } else {
        this.handlerClearForm();
      }
    },
    handlerSaveAvatar() {
      // Validation
      this.$refs['formAvatar'].validate((valid) => {
        if (valid) {
          if (this.current.id !== undefined) {
            avatars
              .update(this.current.id, this.current)
              .then(response => {
                this.$message({
                  type: 'success',
                  message: this.$t('messages.dataAddedSuccessfully'),
                });
                // clear form data and close form
                this.handlerUpdateDate();
              });
          } else {
            avatars
              .store(this.current)
              .then(response => {
                this.$message({
                  type: 'success',
                  message: this.$t('messages.dataAddedSuccessfully'),
                });
                // clear form data and close form
                this.handlerUpdateDate();
              });
          }
        } else {
          this.$message({
            type: 'warning',
            message: this.$t('messages.errorMessage'),
          });
        }
      });
    },
    handlerUpdateDate() {
      this.formVisible = false;
      this.handlerClearForm();
      this.getAvatars();
    },
    handlerResetMultipleSelection() {
      this.multipleSelectionAvatars = {
        ids: [],
        urls: [],
      };
    },
    handlerMultipleSelectionAvatars(val) {
      this.handlerResetMultipleSelection();
      val.forEach((elem, index) => {
        if (elem.img_url !== '') {
          if (elem.id !== 1) {
            this.multipleSelectionAvatars.ids.push(
              elem.id
            );
            this.multipleSelectionAvatars.urls.push(
              elem.img_url
            );
          }
        }
        if (elem.id === 1) {
          this.$message({
            type: 'warning',
            message: this.$t('messages.notDeleteDefaultAvatar'),
            duration: 1 * 1000,
          });
        }
      });
    },
  },
};
</script>

<style lang="scss">
.avatar-name {
  font-size: 1rem;
  font-weight: bold;
}
.wrapper-avatar {
  text-align: center;
  img {
    display: inline-block;
    width: 70px;
    border-radius: 10px;
  }
}
.wrapper-img {
  img {
    border-radius: 24px;
    width: 100%;
    height: auto;
  }
}
.form-footer {
  padding-top: 25px;
}
.el-dialog {
  width: 14.6%;
}
.el-btn-big {
  padding: 15px 7px;
  font-size: 1.1rem;
}
.btn-block {
  display: block;
  width: 100%;
}
.separate-line {
  padding-top: 20px;
  border-bottom: 1px solid #e6e6e6;
}
.el-dialog__header {
  border-bottom: 1px solid #d5d5d5;
  background-color: #f6f6f6;
  padding-bottom: 17px;
}
.bottom-line {
  border-bottom: 1px solid #dcdfe6;
}
.bottom-padding-15 {
  padding-bottom: 15px;
}
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
  padding: 0 0 27px 0;
  margin: 27px 0;
}
.box-right {
  padding-right: 10px;
}
.select_box {
  padding-bottom: 27px;
}
.margin-bottom-20 {
  margin-bottom: 20px;
}
.inline-block-77-interest {
  width: 77%;
  display: inline-block;
}
.wrapper-separate-line {
  text-align: center;
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
.no-display {
  display: none;
}
.box-image {
  cursor: pointer;
  border-radius: 7px;
  background: #e6e6e6cc;
  margin-top: 14px;
  text-align: center;
  position: relative;
  border: 1px solid #d7d6d6;
  height: 24vh;
  width: 24vh;
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
</style>
