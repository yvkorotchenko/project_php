<template>
  <div>
    <PermissionChecker :permissions="[viewPermission.listDelete, viewPermission.create]">
      <div class="filter-item">
        <PermissionChecker :permissions="[viewPermission.listDelete]">
          <el-button plain type="danger" size="small" @click="handlerDeleteMultipleSelectionBanners">{{ $t('btn.delete') }}</el-button>
        </PermissionChecker>
        <PermissionChecker :permissions="[viewPermission.create]">
          <el-button plain type="success" size="small" @click="handlerAddBanner">{{ $t('btn.add') }}</el-button>
        </PermissionChecker>
      </div>
    </PermissionChecker>
    <!-- Separate line -->
    <div class="separate-line" />
    <!-- Table -->
    <el-table
      v-loading="loading"
      :data="list"
      style="width: 100%"
      height="650"
      border
      fit
      highlight-current-row
      @selection-change="handlerMultipleSelectionBanners"
    >
      <el-table-column
        align="center"
        type="selection"
        width="50"
      />
      <el-table-column
        align="center"
        prop="title"
        :label="$t('table.title')"
      >
        <template slot-scope="scope">
          {{ scope.row.titleEn }}
        </template>
      </el-table-column>
      <el-table-column
        align="center"
        prop="pic"
        :label="$t('table.image')"
      >
        <template slot-scope="scope">
          <el-tag v-if="scope.row.urlEn || scope.row.urlLocal" type="success" plain>{{ $t('btn.yes') }}</el-tag>
          <el-tag v-else type="danger" plain>{{ $t('btn.no') }}</el-tag>
        </template>
      </el-table-column>
      <el-table-column
        align="center"
        prop="actionType"
        :label="$t('table.actionType')"
      />
      <el-table-column
        align="center"
        prop="startDate"
        :label="$t('table.bannerStartDate')"
      >
        <template slot-scope="scope">
          {{ scope.row.createdAt }}
        </template>
      </el-table-column>
      <el-table-column
        align="center"
        prop="stopDate"
        :label="$t('table.bannerStopDate')"
      >
        <template slot-scope="scope">
          {{ scope.row.endDate }}
        </template>
      </el-table-column>
      <PermissionChecker :permissions="[viewPermission.delete, viewPermission.edit]">
        <el-table-column
          fixed="right"
          width="187"
          align="center"
          prop="actions"
          :label="$t('table.action')"
        >
          <template slot-scope="scope">
            <PermissionChecker :permissions="[viewPermission.delete]">
              <el-button type="danger" plain size="small" @click="handlerDeleteBanner(scope.row.id)">{{ $t('btn.delete') }}</el-button>
            </PermissionChecker>
            <PermissionChecker :permissions="[viewPermission.edit]">
              <el-button type="primary" plain size="small" @click="handlerEditBanner(scope.row.id)">{{ $t('btn.edit') }}</el-button>
            </PermissionChecker>
          </template>
        </el-table-column>
      </PermissionChecker>
    </el-table>
    <!-- pagination -->
    <Pagination
      v-show="total>0"
      :total="total"
      :page.sync="query.page"
      :limit.sync="query.limit"
      :page-sizes="[10, 15, 20, 30, 70, 90, 150, 200, 250]"
      @pagination="getBanners"
    />
    <!-- Add new Banner dialog -->
    <el-dialog :title="title" :visible.sync="formVisible">
      <el-form
        ref="banner"
        :model="current"
        label-position="right"
        label-width="170px"
      >
        <!-- Title EN -->
        <el-form-item
          :label="$t('labels.title') + '(en)'"
          prop="titleEn"
          :rules="{required: true, message: $t('messages.notEmpty'), trigger: 'blur'}"
        >
          <el-input v-model="current.titleEn" />
        </el-form-item>
        <!-- Title SC -->
        <el-form-item
          :label="$t('labels.title') + '(sc)'"
          prop="titleLocal"
          :rules="{required: true, message: $t('messages.notEmpty'), trigger: 'blur'}"
        >
          <el-input v-model="current.titleLocal" />
        </el-form-item>
        <!-- Display priority -->
        <el-form-item
          :label="$t('labels.displayPriority')"
          prop="displayPriority"
          :rules="{required: true, message: $t('messages.notEmpty'), trigger: 'blur'}"
        >
          <el-input v-model="current.displayPriority" />
        </el-form-item>
        <!-- Action -->
        <el-form-item
          :label="$t('labels.action')"
        >
          <el-select v-model="current.actionType" @change="handlerChangeSetEmptyString">
            <el-option
              v-for="item in actionType"
              :key="item.label"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
        </el-form-item>
        <!-- Response: -->
        <el-form-item
          v-if="current.actionType !== 'NONE'"
          :label="$t('labels.response')"
        >
          <!-- Url -->
          <el-input
            v-if="current.actionType === 'URL'"
            v-model="current.action"
          />
          <!-- UI -->
          <el-select
            v-if="current.actionType === 'UI'"
            v-model="current.action"
            clearable
          >
            <el-option
              v-for="item in ui"
              :key="item"
              :label="item"
              :value="item"
            />
          </el-select>
          <!-- Game -->
          <el-select
            v-if="current.actionType === 'GAME'"
            v-model="current.action"
            clearable
          >
            <el-option
              v-for="item in game"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
        </el-form-item>
        <!-- Starting time -->
        <el-form-item
          :label="$t('labels.startingTime')"
          prop="createdAt"
          :rules="{required: true, message: $t('messages.notEmpty'), trigger: 'blur'}"
        >
          <el-date-picker
            v-model="current.createdAt"
            type="datetime"
            :placeholder="$t('placeholders.selectDateAndTime')"
            value-format="yyyy-MM-dd HH:mm:ss"
          />
        </el-form-item>
        <!-- End Time -->
        <el-form-item
          :label="$t('labels.endTime')"
          prop="endDate"
          :rules="{required: true, message: $t('messages.notEmpty'), trigger: 'blur'}"
        >
          <el-date-picker
            v-model="current.endDate"
            type="datetime"
            :placeholder="$t('placeholders.selectDateAndTime')"
            value-format="yyyy-MM-dd HH:mm:ss"
          />
        </el-form-item>
        <!-- Separate Line -->
        <div class="wrapper-separate-line">
          <div class="bottom-line margin-bottom-20" />
        </div>
        <!-- Image En -->
        <el-form-item
          :label="$t('labels.imageEN')"
          prop="urlEn"
          :rules="{required: true, message: $t('messages.notEmpty'), trigger: 'blur'}"
        >
          <el-input v-model="inputsImage.en" />
          <!-- Wrapper Image en -->
          <el-row :gutter="32">
            <el-col :xs="36" :sm="36" :lg="12">
              <div class="wrapper-image">
                <i
                  v-if="current.urlEn === ''"
                  class="el-icon-camera-solid"
                />
                <img
                  v-else
                  :src="current.urlEn"
                >
              </div>
            </el-col>
            <el-col :xs="36" :sm="36" :lg="12">
              <div class="wrapper-upload-picture">
                <el-button type="success" plain @click="handlerSelecteImage('img-en')">
                  {{ $t('btn.uploadPicture') }}
                </el-button>
                <!-- SELECT FILES -->
                <input ref="img-en" name="image-en" type="file" class="no-display" @change="handlerSelectedImage('img-en')">
              </div>
            </el-col>
          </el-row>
        </el-form-item>
        <!-- Image SC -->
        <el-form-item
          :label="$t('labels.imageZH')"
          prop="urlLocal"
          :rules="{required: true, message: $t('messages.notEmpty'), trigger: 'blur'}"
        >
          <el-input v-model="inputsImage.zh" />
          <el-row :gutter="32">
            <el-col :xs="36" :sm="36" :lg="12">
              <div class="wrapper-image">
                <i
                  v-if="current.urlLocal === ''"
                  class="el-icon-camera-solid"
                />
                <img
                  v-else
                  :src="current.urlLocal"
                >
              </div>
            </el-col>
            <el-col :xs="36" :sm="36" :lg="12">
              <div class="wrapper-upload-picture">
                <el-button type="success" plain @click="handlerSelecteImage('img-sc')">
                  {{ $t('btn.uploadPicture') }}
                </el-button>
                <!-- SELECT FILES -->
                <input ref="img-sc" name="image-sc" type="file" class="no-display" @change="handlerSelectedImage('img-sc')">
              </div>
            </el-col>
          </el-row>
        </el-form-item>
        <el-form-item
          :label="$t('labels.description') + ' (en)'"
          prop="descriptionEn"
          :rules="{required: true, message: $t('messages.notEmpty'), trigger: 'blur'}"
        >
          <el-input v-model="current.descriptionEn" type="textarea" />
        </el-form-item>
        <el-form-item
          :label="$t('labels.description') + ' (sc)'"
          prop="descriptionLocal"
          :rules="{required: true, message: $t('messages.notEmpty'), trigger: 'blur'}"
        >
          <el-input v-model="current.descriptionLocal" type="textarea" />
        </el-form-item>
        <el-form-item class="padding-left-20p">
          <el-button plain type="success" @click="handlerCreateBanner">{{ $t('btn.confirm') }}</el-button>
          <el-button plain type="primary" @click="handlerCloseForm">{{ $t('btn.cancel') }}</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
</template>

<script>
import Pagination from '@/components/Pagination';
import Resource from '@/api/resource';
import PermissionChecker from '@/components/Permissions/index.vue';
import UploadImage from '@/utils/uploadImage';
import viewsPermissions from '@/viewsPermissions.js';

const banners = new Resource('banner-management');
const platformsGames = new Resource('platforms-games');

export default {
  name: 'BannerManagement',
  components: {
    Pagination,
    PermissionChecker,
  },
  data() {
    return {
      viewPermission: viewsPermissions.bannerManagement.permissions.controls,
      loading: false,
      multipleSelectionBanners: [],
      inputsImage: {
        'en': '',
        'zh': '',
      },
      current: {
        id: undefined,
        urlEn: '',
        urlLocal: '',
        titleEn: '',
        titleLocal: '',
        displayPriority: 0,
        actionType: 'NONE',
        action: '',
        createdAt: '',
        endDate: '',
        descriptionEn: '',
        descriptionLocal: '',
      },
      list: [],
      query: {
        limit: 20,
        page: 1,
      },
      total: 0,
      // dialog
      formVisible: false,
      title: '',
      // Action types
      actionType: [
        {
          value: 'NONE',
          label: this.$t('select.none'),
        },
        {
          value: 'URL',
          label: this.$t('select.url'),
        },
        {
          value: 'UI',
          label: this.$t('select.ui'),
        },
        {
          value: 'GAME',
          label: this.$t('select.game'),
        },
      ],
      // actions types
      ui: [
        'Customer service chat',
        'Active',
        'inBox',
        'VIP',
        'finance',
        'Personal center',
      ],
      game: [],
      // active action
      selectedActions: '',
      // selected lang title
      tableTitle: '',
      // images files
      files: [],
      uploadImageUri: '/banner-management-upload-images/',
    };
  },
  created() {
    this.getPlatformsGames();
    this.getBanners();
    // set default value
    this.selectedActions = 'NONE';
  },
  methods: {
    async getBanners() {
      const { limit, page } = this.query;
      this.loading = true;
      const { data, meta } = await banners.list(this.query);
      this.list = data;
      this.list.forEach((item, index) => {
        item['index'] = (page - 1) * limit + index + 1;
      });
      this.total = meta.total;
      this.loading = false;
    },
    handlerResetCurrentData() {
      this.current = {
        id: undefined,
        urlEn: '',
        urlLocal: '',
        titleEn: '',
        titleLocal: '',
        displayPriority: 0,
        actionType: 'NONE',
        action: '',
        createdAt: '',
        endDate: '',
      };
      //
      this.inputsImage.en = '';
      this.inputsImage.zh = '';
    },
    handlerAddBanner() {
      this.title = this.$t('titles.newBanner');
      this.formVisible = true;
      //
      this.handlerResetCurrentData();
    },
    handlerEditBanner(id) {
      this.title = this.$t('titles.editBanner');
      this.formVisible = true;
      //
      const row = this.list.find(item => item.id === id);
      this.current = row;
      this.inputsImage.en = row.urlEn;
      this.inputsImage.zh = row.urlLocal;
    },
    handlerDeleteBanner(id) {
      this.$confirm(this.$t('messages.deleteConfirmMessage') + '?', 'Warning', {
        confirmButtonText: this.$t('btn.ok'),
        cancelButtonText: this.$t('btn.cancel'),
        type: 'warning',
      })
        .then(() => {
          const row = this.list.find(item => item.id === id);
          const links = {
            'en': row.urlEn,
            'zh': row.urlLocal,
          };
          banners.del(id, links)
            .then(() => {
              this.$message({
                type: 'success',
                message: this.$t('messages.delCompleteUser'),
                duration: 1 * 1000,
              });
            })
            .finally(() => {
              this.getBanners();
            });
        }).catch(() => {
          this.$message({
            type: 'info',
            message: this.$t('messages.delCancelUser'),
            duration: 1 * 1000,
          });
        });
    },
    handlerDeleteMultipleSelectionBanners() {
      const ids = [];
      const links = [];
      if (this.multipleSelectionBanners.length === 0) {
        // this.$message({
        //   type: 'error',
        //   message: this.$t('messages.noDeleteItems'),
        //   duration: 1 * 1000,
        // });
      } else {
        this.multipleSelectionBanners.forEach((item, index) => {
          ids.push(item.id);
          links.push({
            'en': item.urlEn,
            'zh': item.urlLocal,
          });
        });
        // confirm message
        this.$confirm(this.$t('messages.deleteConfirmMessage') + '?', 'Warning', {
          confirmButtonText: this.$t('btn.ok'),
          cancelButtonText: this.$t('btn.cancel'),
          type: 'warning',
        })
          .then(() => {
            banners
              .del(ids.toString(), links)
              .then(() => {
                this.$message({
                  type: 'success',
                  message: this.$t('messages.delCompleteUser'),
                  duration: 1 * 1000,
                });
              })
              .finally(() => {
                this.getBanners();
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
      //
    },
    handlerMultipleSelectionBanners(val) {
      this.multipleSelectionBanners = val;
    },
    async getPlatformsGames() {
      const { data } = await platformsGames.list({ page: 1, limit: 333 });
      const options = [];
      data.forEach((item, index) => {
        options.push({
          value: item.id,
          label: item.name,
        });
      });
      this.game = options;
    },
    //
    handlerChangeSetEmptyString() {
      this.current.action = '';
    },
    //
    handlerCreateBanner() {
      this.$refs['banner'].validate((valid) => {
        if (valid) {
          if (this.current.id === undefined) {
            banners
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
                this.handlerResetCurrentData();
                this.handlerUpdateDate();
              });
          } else {
            if (this.current.index) {
              delete this.current.index;
            }
            banners
              .update(this.current.id, this.current)
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
                this.handlerUpdateDate();
              });
          }
        } else {
          return false;
        }
      });
    },
    handlerUpdateDate() {
      this.handlerCloseForm();
      this.handlerResetCurrentData();
      this.getBanners();
    },
    handlerCloseForm() {
      this.formVisible = false;
    },
    handlerSelectedImage(name) {
      const img = this.files[name] = this.$refs[name].files[0];
      const type = img.type;
      // upload file
      UploadImage(this.uploadImageUri, type, img)
        .then(response => {
          const { data } = response;
          if (name === 'img-sc') {
            this.inputsImage.zh = data.url;
            this.current.urlLocal = data.url;
          }
          if (name === 'img-en') {
            this.inputsImage.en = data.url;
            this.current.urlEn = data.url;
          }
        });
    },
    handlerSelecteImage(name) {
      this.$refs[name].click();
    },
  },
};
</script>

<style lang="scss">
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
</style>
