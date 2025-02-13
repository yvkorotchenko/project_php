<template>
  <div>
    <el-row class="separate-line">
      <el-col :span="24">
        <div class="select_box">
          <el-select
            v-model="selectedQueryFilter"
            value-key="selectedQueryFilter"
            placeholder="Activity zone"
          >
            <el-option
              v-for="item in queryFilters"
              :key="item.label"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
          <el-button type="primary" plain size="medium">
            {{ $t('btn.query') }}
          </el-button>
        </div>
      </el-col>
    </el-row>
    <!-- TABLE -->
    <el-row>
      <el-col :span="24">
        <CrudComponent
          :fetch-items="fetchItems"
          :not-editable-fields="nonEditableFields"
          :item-types="itemTypes"
          :fields-labels="fieldsLabels"
          :on-dialog="true"
          :emit-event-name="'sdialog'"
          @sdialog="handlerShowForm"
        />
      </el-col>
    </el-row>
    <el-dialog :title="title" :visible.sync="formVisible">
      <el-form label-position="right" label-width="170px">
        <!-- Activity Type -->
        <el-form-item :label="$t('labels.activityType')">
          <el-select
            v-model="selectedActivityType"
            value-key="selectedActivityType"
            :placeholder="$t('placeholders.pleaseSelectedActivity')"
            clearable
            @change="ActivityType"
          >
            <el-option
              v-for="item in activityTypes"
              :key="item.label"
              :label="item.label"
              value="value"
            />
          </el-select>
        </el-form-item>
        <!-- Title EN -->
        <el-form-item :label="$t('labels.title') + '(en)'">
          <el-input />
        </el-form-item>
        <!-- Title SC -->
        <el-form-item :label="$t('labels.title') + '(sc)'">
          <el-input />
        </el-form-item>
        <!-- Display priority -->
        <el-form-item :label="$t('labels.displayPriority')">
          <el-input />
        </el-form-item>
        <!-- Action -->
        <el-form-item :label="$t('labels.action')">
          <el-select
            v-model="selectedActions"
            value-key="selectedActions"
            clearable
          >
            <el-option
              v-for="item in listActions"
              :key="item.label"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
        </el-form-item>
        <!-- Response: -->
        <el-form-item :label="$t('labels.response')">
          <el-select
            v-model="selectedResponse"
            value-key="selectedResponse"
            clearable
          >
            <el-option
              v-for="item in listResponse"
              :key="item.label"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
        </el-form-item>
        <!-- Starting time -->
        <el-form-item
          :label="$t('labels.startingTime')"
        >
          <el-date-picker
            v-model="startTime"
            type="datetime"
            :placeholder="$t('placeholders.selectDateAndTime')"
          />
        </el-form-item>
        <!-- End Time -->
        <el-form-item
          :label="$t('labels.endTime')"
        >
          <el-date-picker
            v-model="endTime"
            type="datetime"
            :placeholder="$t('placeholders.selectDateAndTime')"
          />
        </el-form-item>
        <!-- Whether to open -->
        <el-form-item
          :label="$t('labels.whetherToOpen')"
        >
          <el-select
            v-model="selectedWhetherToOpen"
            value-key="selectedWhetherToOpen"
            clearable
          >
            <el-option
              v-for="item in listWhetherToOpen"
              :key="item.label"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
        </el-form-item>
        <!-- Separate Line -->
        <div class="wrapper-separate-line">
          <div class="bottom-line margin-bottom-20" />
        </div>
        <!-- Image En -->
        <el-form-item :label="$t('labels.pic') + '(en)'">
          <el-input v-model="imageEn" />
          <!-- Wrapper Image en -->
          <el-row :gutter="32">
            <el-col :xs="36" :sm="36" :lg="12">
              <div class="wrapper-image">
                <i
                  v-if="imageEn === ''"
                  class="el-icon-camera-solid"
                />
                <img
                  v-else
                  :src="imageEn"
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
        <el-form-item :label="$t('labels.pic') + '(sc)'">
          <el-input v-model="imageSc" />
          <el-row :gutter="32">
            <el-col :xs="36" :sm="36" :lg="12">
              <div class="wrapper-image">
                <i
                  v-if="imageSc === ''"
                  class="el-icon-camera-solid"
                />
                <img
                  v-else
                  :src="imageSc"
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
        <el-form-item class="padding-left-20p">
          <el-button plain type="success">{{ $t('btn.confirm') }}</el-button>
          <el-button plain type="primary" @click="formVisible = false">{{ $t('btn.cancel') }}</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
</template>

<script>
import UploadImage from '@/utils/uploadImage';
import CrudComponent from '@/components/CrudComponent';
import ActivityConfiguration from '@/api/activityConfiguration';
const activityConfiguration = new ActivityConfiguration();

export default {
  name: 'ActivityConfiguration',
  components: {
    CrudComponent,
  },
  data() {
    return {
      // DATA
      fetchItems: (queryParams) => activityConfiguration.list(queryParams),
      title: this.$t('dialog.titles.newActivity'),
      selectedQueryFilter: [],
      itemTypes: {
        id: Number,
        typeId: Number,
        type: String,
        name: String,
        image: String,
        active: Boolean,
        startingTime: String,
        endTime: String,
        reward: Object,
        maxProgress: String,
        created: String,
      },
      fieldsLabels: {
        id: this.$t('table.id'),
        typeId: this.$t('table.typeId'),
        type: this.$t('table.type'),
        name: this.$t('table.name'),
        image: this.$t('table.image'),
        active: this.$t('table.active'),
        startingTime: this.$t('table.startingTime'),
        endTime: this.$t('table.endTime'),
        reward: this.$t('table.reward'),
        maxProgress: this.$t('table.maxProgress'),
        created: this.$t('table.сreationTime'),
      },
      nonEditableFields: ['id', 'typeId'],
      // Filters
      queryFilters: [
        {
          value: '1',
          label: 'All status',
        },
      ],
      loading: false,
      formVisible: false,
      list: [
        {
          id: 1,
        },
      ],
      // Activity Type
      activityTypes: [
        {
          value: '1',
          label: 'Welfare',
        },
        {
          value: '2',
          label: 'Task Tasks',
        },
        {
          value: '3',
          label: 'Reward',
        },
      ],
      selectedActivityType: [],
      // Actions
      listActions: [
        {
          label: this.$t('options.dontJump'),
          value: '1',
        },
        {
          label: this.$t('options.url'),
          value: '2',
        },
        {
          label: this.$t('options.ui'),
          value: '3',
        },
        {
          label: this.$t('options.game'),
          value: '4',
        },
      ],
      selectedActions: [],
      //
      listResponse: [
        {
          label: 'One',
          value: '1',
        },
        {
          label: 'Two',
          value: '2',
        },
        {
          label: 'Three',
          value: '3',
        },
        {
          label: 'Fore',
          value: '4',
        },
        {
          label: 'Five',
          value: '5',
        },
      ],
      selectedResponse: [],
      // start end time
      startTime: '',
      endTime: '',
      imageEn: '',
      imageSc: '',
      selectedWhetherToOpen: [],
      listWhetherToOpen: [
        {
          label: this.$t('btn.on'),
          value: true,
        },
        {
          label: this.$t('btn.off'),
          value: false,
        },
      ],
      // images files
      files: [],
      uploadImageUri: '/activity-configurations/upload-image',
    };
  },
  created() {
    this.selectedQueryFilter = this.queryFilters[0].label;
    this.selectedActivityType = this.activityTypes[0].label;
    // default action
    this.selectedActions = this.listActions[0].label;
    this.selectedResponse = this.listResponse[0].label;
    // default off
    this.selectedWhetherToOpen = this.listWhetherToOpen[1].label;
  },
  methods: {
    handlerShowForm() {
      this.formVisible = true;
    },
    getImage() {
      this.imageEn = 'https://images.pexels.com/photos/257360/pexels-photo-257360.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500';
      this.imageSc = 'https://cache.desktopnexus.com/thumbseg/977/977172-bigthumbnail.jpg';
    },
    ActivityType(val) {
      // console.log(val);
    },
    handlerSelecteImage(name) {
      this.$refs[name].click();
    },
    handlerSelectedImage(name) {
      const img = this.files[name] = this.$refs[name].files[0];
      const type = img.type;
      // upload file
      UploadImage(this.uploadImageUri, type, img)
        .then(response => {
          // const { data } = response;
          // console.log(data);
          console.log(response);
        });
    },
  },
};
</script>

<style lang="scss">
.el-dialog__header {
  border-bottom: 1px solid #d5d5d5;
  background-color: #f6f6f6;
  padding-bottom: 17px;
}
.separate-line {
  border-bottom: 1px solid #e6e6e6;
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
