<template>
  <div>
    <PermissionChecker :permissions="[viewPermission.listDelete, viewPermission.create]">
      <div class="filter-item">
        <PermissionChecker :permissions="[viewPermission.listDelete]">
          <el-button plain type="danger" size="small" @click="handlerDeleteMultipleSelectionSevenDaysWelfare">{{ $t('btn.delete') }}</el-button>
        </PermissionChecker>
        <PermissionChecker :permissions="[viewPermission.create]">
          <el-button plain type="success" size="small" @click="handlerAddReward">{{ $t('btn.add') }}</el-button>
        </PermissionChecker>
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
      @selection-change="handlerMultipleSelectionSevenDaysWelfare"
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
        label="Id"
      >
        <template slot-scope="scope">
          {{ scope.row.id }}
        </template>
      </el-table-column>
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
        prop="imageEn"
        :label="$t('table.requirements')"
      >
        <template slot-scope="scope">
          {{ scope.row.receiveRequirements }}
        </template>
      </el-table-column>
      <el-table-column
        align="center"
        prop="actionType"
        :label="$t('table.actionType')"
      >
        <template slot-scope="scope">
          {{ scope.row.actionType }}
        </template>
      </el-table-column>
      <el-table-column
        align="center"
        prop="active"
        :label="$t('table.active')"
      >
        <template slot-scope="scope">
          <el-tag v-if="scope.row.active" type="success">{{ $t('btn.on') }}</el-tag>
          <el-tag v-else type="danger">{{ $t('btn.off') }}</el-tag>
        </template>
      </el-table-column>
      <el-table-column
        align="center"
        prop="imageLocal"
        :label="$t('table.priority')"
      >
        <template slot-scope="scope">
          {{ scope.row.displayPriority }}
        </template>
      </el-table-column>
      <!-- Start data -->
      <el-table-column
        align="center"
        prop="created"
        :label="$t('table.startingTime')"
      >
        <template slot-scope="scope">
          {{ scope.row.startTime }}
        </template>
      </el-table-column>
      <!-- End data -->
      <el-table-column
        align="center"
        prop="created"
        :label="$t('table.endTime')"
      >
        <template slot-scope="scope">
          {{ scope.row.endTime }}
        </template>
      </el-table-column>
      <!-- Buttons -->
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
              <el-button type="danger" plain size="small" @click="handlerDeleteSevenDaysWelfare(scope.row)">{{ $t('btn.delete') }}</el-button>
            </PermissionChecker>
            <PermissionChecker :permissions="[viewPermission.edit]">
              <el-button type="primary" plain size="small" @click="handlerEditSevenDaysWelfare(scope.row.id, scope.row)">{{ $t('btn.edit') }}</el-button>
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
      @pagination="getSevenDaysWelfare"
    />
    <!-- PAGINATION -->
    <!-- DIALOG -->
    <el-dialog :title="title" :visible.sync="formVisible" :before-close="handlerCloseForm">
      <el-form
        ref="rewards"
        :rules="rules"
        :model="current"
        label-position="right"
        label-width="177px"
      >
        <!-- TITLE EN -->
        <el-form-item
          :label="$t('labels.title') + ' (en)'"
          prop="titleEn"
          :rules="[{ required: true, message: getMessage('notBeEmpty'), trigger: 'blur' }]"
        >
          <el-input v-model="current.titleEn" />
        </el-form-item>
        <!-- TITLE SC -->
        <el-form-item
          :label="$t('labels.title') + ' (sc)'"
          prop="titleLocal"
          :rules="[{ required: true, message: getMessage('notBeEmpty'), trigger: 'blur' }]"
        >
          <el-input v-model="current.titleLocal" />
        </el-form-item>
        <!-- Display priority -->
        <el-form-item
          :label="$t('labels.displayPriority')"
          prop="displayPriority"
          :rules="[{ required: true, pattern: /^[0-9]*$/, message: getMessage('pleaseEnterNumber'), trigger: 'blur' }]"
        >
          <el-input v-model.number="current.displayPriority" />
        </el-form-item>
        <!-- START ACTION GROPE -->
        <el-row>
          <el-col :span="12">
            <div class="grid-content bg-purple">
              <!-- Actions -->
              <el-form-item :label="$t('labels.action')">
                <el-select v-model="current.actionType" @change="handlerChangeActionType">
                  <el-option
                    v-for="item in actionTypes"
                    :key="item"
                    :value="item.value"
                    :label="item.label"
                  />
                </el-select>
              </el-form-item>
            </div>
          </el-col>
          <el-col :span="12">
            <div class="grid-content bg-purple-light">
              <!-- Response -->
              <template v-if="current.actionType !== 'NONE'">
                <el-form-item
                  :label="$t('labels.response')"
                  prop="action"
                  :rules="[{ required: true, message: getMessage('notBeEmpty'), trigger: 'blur' }]"
                >
                  <!-- URL -->
                  <template v-if="current.actionType === 'URL'">
                    <el-input v-model="current.action" clearable />
                  </template>
                  <!-- UI -->
                  <template v-if="current.actionType === 'UI'">
                    <el-select v-model="current.action">
                      <el-option
                        v-for="item in ui"
                        :key="item"
                        :label="item"
                        :value="item"
                      />
                    </el-select>
                  </template>
                  <!-- GAME -->
                  <template v-if="current.actionType === 'GAME'">
                    <el-select v-model="current.action">
                      <el-option
                        v-for="item in game"
                        :key="item.value"
                        :label="item.label"
                        :value="item.value"
                      />
                    </el-select>
                  </template>
                </el-form-item>
              </template>
            </div>
          </el-col>
        </el-row>
        <!-- END ACTION GROPE -->
        <!-- START SELECT TIME -->
        <el-row>
          <el-col :span="12">
            <div class="grid-content bg-purple">
              <!-- Start date -->
              <el-form-item
                :label="$t('labels.startingTime')"
                prop="startTime"
                :rules="[{ required: true, message: getMessage('notBeEmpty'), trigger: 'blur' }]"
              >
                <el-date-picker
                  v-model="current.startTime"
                  type="datetime"
                  :placeholder="$t('placeholders.selectDateAndTime')"
                  value-format="yyyy-MM-dd HH:mm:ss"
                />
              </el-form-item>
            </div>
          </el-col>
          <el-col :span="12">
            <div class="grid-content bg-purple-light">
              <!-- End date -->
              <el-form-item
                :label="$t('labels.endTime')"
                prop="endTime"
                :rules="[{ required: true, message: getMessage('notBeEmpty'), trigger: 'blur' }]"
              >
                <el-date-picker
                  v-model="current.endTime"
                  type="datetime"
                  :placeholder="$t('placeholders.selectDateAndTime')"
                  value-format="yyyy-MM-dd HH:mm:ss"
                />
              </el-form-item>
            </div>
          </el-col>
        </el-row>
        <!-- END START SELECT TIME -->
        <!-- select type -->
        <el-form-item :label="$t('labels.category')">
          <el-select v-model="current.type">
            <el-option
              v-for="item in types"
              :key="item"
              :value="item.value"
              :label="item.label"
            />
          </el-select>
        </el-form-item>
        <!-- Separate Line -->
        <div class="wrapper-separate-line">
          <div class="bottom-line margin-bottom-20" />
        </div>
        <!-- Title -->
        <div>
          <h4 style="font-size: 1.2rem;">{{ $t('titles.welfareBanner') }}</h4>
        </div>
        <!-- Separate Line -->
        <div class="wrapper-separate-line">
          <div class="bottom-line margin-bottom-30" />
        </div>
        <!-- IMAGE EN -->
        <el-form-item
          :label="$t('labels.picEN')"
          prop="bannerEn"
          :rules="[{ required: true, message: getMessage('notBeEmpty'), trigger: 'blur' }]"
        >
          <el-input v-model="bannerImages.en" />
        </el-form-item>
        <!-- START WRAPPER IMAGE EN -->
        <el-form-item>
          <el-row :gutter="32">
            <!-- IMAGE -->
            <el-col :xs="36" :sm="36" :lg="12">
              <el-form-item
                prop="bannerEn"
                :rules="[{ required: true, validator: validateImage, trigger: 'blur' }]"
              >
                <div ref="bannerEn" class="wrapper-image" @click="handlerSelectImage('img-en')">
                  <i
                    v-if="current.bannerEn === ''"
                    class="el-icon-camera-solid"
                  />
                  <img
                    v-else
                    :src="current.bannerEn"
                  >
                </div>
              </el-form-item>
            </el-col>
            <!-- BUTTON -->
            <el-col :xs="36" :sm="36" :lg="12">
              <div class="wrapper-upload-picture">
                <el-button type="success" plain @click="handlerSelectImage('img-en')">
                  {{ $t('btn.uploadPicture') }}
                </el-button>
                <!-- INPUT FILES -->
                <input ref="img-en" name="image-en" type="file" class="no-display" @change="handlerSelectedImage('img-en', setBannerImage)">
              </div>
            </el-col>
          </el-row>
        </el-form-item>
        <!-- END WRAPPER IMAGE EN -->
        <el-form-item
          :label="$t('labels.picZH')"
          prop="bannerLocal"
          :rules="[{ required: true, message: getMessage('notBeEmpty'), trigger: 'blur' }]"
        >
          <el-input v-model="bannerImages.zh" />
        </el-form-item>
        <el-form-item>
          <!-- START WRAPPER  IMAGE ZH -->
          <el-row :gutter="32">
            <el-col :xs="36" :sm="36" :lg="12">
              <el-form-item
                prop="bannerLocal"
                :rules="[{ required: true, validator: validateImage, trigger: 'blur' }]"
              >
                <div ref="bannerLocal" class="wrapper-image" @click="handlerSelectImage('img-sc')">
                  <i
                    v-if="current.bannerLocal === ''"
                    class="el-icon-camera-solid"
                  />
                  <img
                    v-else
                    :src="current.bannerLocal"
                  >
                </div>
              </el-form-item>
            </el-col>
            <!-- BUTTON -->
            <el-col :xs="36" :sm="36" :lg="12">
              <div class="wrapper-upload-picture">
                <el-button type="success" plain @click="handlerSelectImage('img-sc')">
                  {{ $t('btn.uploadPicture') }}
                </el-button>
                <!-- INPUT FILES -->
                <input ref="img-sc" name="image-sc" type="file" class="no-display" @change="handlerSelectedImage('img-sc', setBannerImage)">
              </div>
            </el-col>
          </el-row>
          <!-- END WRAPPER IMAGE ZH -->
        </el-form-item>
        <!-- IMAGE ZH -->
        <!-- Separate Line -->
        <div class="wrapper-separate-line">
          <div class="bottom-line margin-bottom-20" />
        </div>
        <!-- Title -->
        <div>
          <h4 style="font-size: 1.2rem;">{{ $t('titles.welfareImages') }}</h4>
        </div>
        <!-- Separate Line -->
        <div class="wrapper-separate-line">
          <div class="bottom-line margin-bottom-30" />
        </div>
        <!-- START IMAGES -->
        <!-- IMAGE EN -->
        <el-form-item
          :label="$t('labels.picEN')"
          prop="imageEn"
          :rules="[{ required: true, message: getMessage('notBeEmpty'), trigger: 'blur' }]"
        >
          <el-input v-model="inputsImages.en" />
        </el-form-item>
        <!-- START WRAPPER IMAGE EN -->
        <el-form-item>
          <el-row :gutter="32">
            <!-- IMAGE -->
            <el-col :xs="36" :sm="36" :lg="12">
              <el-form-item
                prop="imageEn"
                :rules="[{ required: true, validator: validateImage, trigger: 'blur' }]"
              >
                <div ref="imageEn" class="wrapper-image" @click="handlerSelectImage('imgs-en')">
                  <i
                    v-if="current.imageEn === ''"
                    class="el-icon-camera-solid"
                  />
                  <img
                    v-else
                    :src="current.imageEn"
                  >
                </div>
              </el-form-item>
            </el-col>
            <!-- BUTTON -->
            <el-col :xs="36" :sm="36" :lg="12">
              <div class="wrapper-upload-picture">
                <el-button type="success" plain @click="handlerSelectImage('imgs-en')">
                  {{ $t('btn.uploadPicture') }}
                </el-button>
                <!-- INPUT FILES -->
                <input ref="imgs-en" name="image-en" type="file" class="no-display" @change="handlerSelectedImage('imgs-en', setImages)">
              </div>
            </el-col>
          </el-row>
        </el-form-item>
        <!-- END WRAPPER IMAGE EN -->
        <el-form-item
          :label="$t('labels.picZH')"
          prop="imageLocal"
          :rules="[{ required: true, message: getMessage('notBeEmpty'), trigger: 'blur' }]"
        >
          <el-input v-model="inputsImages.zh" />
        </el-form-item>
        <el-form-item>
          <!-- START WRAPPER  IMAGE ZH -->
          <el-row :gutter="32">
            <el-col :xs="36" :sm="36" :lg="12">
              <el-form-item
                prop="imageLocal"
                :rules="[{ required: true, validator: validateImage, trigger: 'blur' }]"
              >
                <div ref="imageLocal" class="wrapper-image" @click="handlerSelectImage('imgs-sc')">
                  <i
                    v-if="current.imageLocal === ''"
                    class="el-icon-camera-solid"
                  />
                  <img
                    v-else
                    :src="current.imageLocal"
                  >
                </div>
              </el-form-item>
            </el-col>
            <!-- BUTTON -->
            <el-col :xs="36" :sm="36" :lg="12">
              <div class="wrapper-upload-picture">
                <el-button type="success" plain @click="handlerSelectImage('imgs-sc')">
                  {{ $t('btn.uploadPicture') }}
                </el-button>
                <!-- INPUT FILES -->
                <input ref="imgs-sc" name="image-sc" type="file" class="no-display" @change="handlerSelectedImage('imgs-sc', setImages)">
              </div>
            </el-col>
          </el-row>
          <!-- END WRAPPER IMAGE ZH -->
        </el-form-item>
        <!-- IMAGE ZH -->
        <!-- END IMAGES -->
        <!-- MAXIMUM BONUS -->
        <el-form-item
          :label="$t('labels.maximumBonus')"
          prop="maxRewardLimit"
          :rules="[{ required: true, pattern: /^[0-9/.]*$/, message: getMessage('pleaseEnterNumber'), trigger: 'blur' }]"
        >
          <el-input v-model="current.maxRewardLimit" @change="handlerSetMaxRewardLimitToFloat" />
        </el-form-item>
        <!-- Separate Line -->
        <div class="wrapper-separate-line">
          <div class="bottom-line margin-bottom-20 padding-top-17" />
        </div>
        <!-- SELECT GROUPE -->
        <el-row :gutter="32">
          <el-col :xs="35" :sm="35" :lg="10">
            <el-form-item :label="$t('labels.receiveButton')">
              <el-select v-model="current.active" @change="handlerDeleteAllAddedButtons">
                <el-option
                  v-for="item in switchLists"
                  :key="item.label"
                  :value="item.value"
                  :label="item.label"
                />
              </el-select>
            </el-form-item>
          </el-col>
          <el-col :xs="38" :sm="38" :lg="14">
            <el-form-item class="for-mode">
              <el-form-item
                :label="$t('labels.receiveRequirements')"
                prop="receiveRequirements"
                :rules="[{ required: true, message: getMessage('notBeEmpty'), trigger: 'blur' }]"
              >
                <el-select v-model="current.receiveRequirements">
                  <el-option
                    v-for="item in receiveRequirements"
                    :key="item.label"
                    :value="item.value"
                    :label="language === 'en' ? item.label : item.labelZH"
                  />
                </el-select>
              </el-form-item>
            </el-form-item>
          </el-col>
        </el-row>
        <!-- WRAPPER ADD BUTTON -->
        <div v-if="current.active">
          <div v-if="addButton">
            <!-- Separate Line -->
            <div class="wrapper-separate-line">
              <div class="bottom-line margin-bottom-20 padding-top-17" />
            </div>
            <!-- ADD BUTTON -->
            <div class="center">
              <el-button icon="el-icon-plus" plain type="success" @click="handlerAddNewButton" />
            </div>
          </div>
          <!-- Condition -->
          <!-- Buttons Loop banner -->
          <div v-for="(val, key) in current.buttons" :key="key">
            <!-- Separate Line -->
            <div class="wrapper-separate-line padding-top-17">
              <div class="label-in-line">{{ $t('labels.receiveButton') + '# ' + key }}</div>
              <div class="bottom-line margin-bottom-20" />
            </div>
            <!-- Requirements -->
            <el-form-item
              :label="$t('labels.requirements')"
              :prop="'buttons[' + key + '].condition'"
              :rules="[{ required: true, pattern: /^[0-9/.]*$/, message: getMessage('pleaseEnterNumber'), trigger: 'blur' }]"
            >
              <el-input v-model.number="current.buttons[key].condition" />
            </el-form-item>
            <!-- Receive gold -->
            <el-form-item
              :label="$t('labels.receiveGold')"
              :prop="'buttons[' + key + '].receiveAmount'"
              :rules="[{ required: true, pattern: /^[0-9/.]*$/, message: getMessage('pleaseEnterNumber'), trigger: 'blur' }]"
            >
              <el-input v-model="current.buttons[key].receiveAmount" @change="handlerSetReceiveAmountToFloat(key)" />
            </el-form-item>
            <!-- BUTTON EN -->
            <el-form-item :label="$t('lang.en')">
              <el-row>
                <el-col :span="12" class="padding-right-17">
                  <!-- ACTIVE -->
                  <div class="grid-content bg-purple">
                    <el-form-item
                      :prop="'buttons[' + key + '].images.en.active'"
                      :rules="[{ required: true, message: getMessage('notBeEmpty'), trigger: 'blur' }]"
                    >
                      <el-input
                        v-model="inputButtonsImages[key].en.active"
                        :placeholder="getMessage('noBonusButton', 'labels')"
                      />
                    </el-form-item>
                    <!-- IMAGE PREV EN -->
                    <el-form-item
                      :prop="'buttons[' + key + '].images.en.active'"
                      :rules="[{ required: true, dynamically: true, validator: validateImage, trigger: 'blur' }]"
                    >
                      <div
                        :ref="'buttons[' + key + '].images.en.active'"
                        class="wrapper-image"
                        @click="handlerSelectImage('btn-' + key + '-img-en-active', true)"
                      >
                        <i
                          v-if="current.buttons[key].images.en.active === ''"
                          class="el-icon-camera-solid"
                        />
                        <img
                          v-else
                          :src="current.buttons[key].images.en.active"
                        >
                      </div>
                    </el-form-item>
                    <el-button
                      size="small"
                      type="primary"
                      plain
                      class="margin-top-21"
                      @click="handlerSelectImage('btn-' + key + '-img-en-active', true)"
                    >
                      {{ $t('btn.uploadPicture') }}
                    </el-button>
                    <input
                      :ref="'btn-' + key + '-img-en-active'"
                      :name="'button-' + key + '-image-en-active'"
                      type="file"
                      class="no-display"
                      @change="handlerSelectedImage('btn-' + key + '-img-en-active', setButtonsImages, key, 'active')"
                    >
                  </div>
                </el-col>
                <el-col :span="12" class="padding-right-17">
                  <!-- INACTIVE -->
                  <div class="grid-content bg-purple-light">
                    <el-form-item
                      :prop="'buttons[' + key + '].images.en.inactive'"
                      :rules="[{ required: true, message: getMessage('notBeEmpty'), trigger: 'blur' }]"
                    >
                      <el-input
                        v-model="inputButtonsImages[key].en.inactive"
                        :placeholder="getMessage('receiveBonusButton', 'labels')"
                      />
                    </el-form-item>
                    <!-- IMAGE PREV EN -->
                    <el-form-item
                      :prop="'buttons[' + key + '].images.en.inactive'"
                      :rules="[{ required: true, dynamically: true, validator: validateImage, trigger: 'blur' }]"
                    >
                      <div
                        :ref="'buttons[' + key + '].images.en.inactive'"
                        class="wrapper-image"
                        @click="handlerSelectImage('btn-' + key + '-img-en-inactive', true)"
                      >
                        <i
                          v-if="current.buttons[key].images.en.inactive === ''"
                          class="el-icon-camera-solid"
                        />
                        <img
                          v-else
                          :src="current.buttons[key].images.en.inactive"
                        >
                      </div>
                    </el-form-item>
                    <el-button
                      size="small"
                      type="primary"
                      plain
                      class="margin-top-21"
                      @click="handlerSelectImage('btn-' + key + '-img-en-inactive', true)"
                    >
                      {{ $t('btn.uploadPicture') }}
                    </el-button>
                    <!-- -->
                    <input
                      :ref="'btn-' + key + '-img-en-inactive'"
                      :name="'button-' + key + '-image-en-inactive'"
                      type="file"
                      class="no-display"
                      @change="handlerSelectedImage('btn-' + key + '-img-en-inactive', setButtonsImages, key, 'inactive')"
                    >
                  </div>
                </el-col>
              </el-row>
            </el-form-item>
            <!-- BUTTON ZH -->
            <el-form-item :label="$t('lang.sc')">
              <el-row>
                <el-col :span="12" class="padding-right-17">
                  <!-- ACTIVE -->
                  <div class="grid-content bg-purple">
                    <el-form-item
                      :prop="'buttons[' + key + '].images.local.active'"
                      :rules="[{ required: true, message: getMessage('notBeEmpty'), trigger: 'blur' }]"
                    >
                      <el-input
                        v-model="inputButtonsImages[key].zh.active"
                        :placeholder="getMessage('noBonusButton', 'labels')"
                      />
                    </el-form-item>
                    <!-- IMAGE ACTIVE ZH -->
                    <el-form-item
                      :prop="'buttons[' + key + '].images.local.active'"
                      :rules="[{ required: true, dynamically: true, validator: validateImage, trigger: 'blur' }]"
                    >
                      <div
                        :ref="'buttons[' + key + '].images.local.active'"
                        class="wrapper-image"
                        @click="handlerSelectImage('btn-' + key + '-img-zh-active', true)"
                      >
                        <i
                          v-if="current.buttons[key].images.local.active === ''"
                          class="el-icon-camera-solid"
                        />
                        <img
                          v-else
                          :src="current.buttons[key].images.local.active"
                        >
                      </div>
                    </el-form-item>
                    <el-button
                      size="small"
                      type="primary"
                      plain
                      class="margin-top-21"
                      @click="handlerSelectImage('btn-' + key + '-img-zh-active', true)"
                    >
                      {{ $t('btn.uploadPicture') }}
                    </el-button>
                    <!-- INPUT FILE -->
                    <input
                      :ref="'btn-' + key + '-img-zh-active'"
                      :name="'button-' + key + '-image-zh-active'"
                      type="file"
                      class="no-display"
                      @change="handlerSelectedImage('btn-' + key + '-img-zh-active', setButtonsImages, key, 'active')"
                    >
                  </div>
                </el-col>
                <el-col :span="12" class="padding-right-17">
                  <!-- INACTIVE -->
                  <div class="grid-content bg-purple-light">
                    <el-form-item
                      :prop="'buttons[' + key + '].images.local.inactive'"
                      :rules="[{ required: true, message: getMessage('notBeEmpty'), trigger: 'blur' }]"
                    >
                      <el-input
                        v-model="inputButtonsImages[key].zh.inactive"
                        :placeholder="getMessage('receiveBonusButton', 'labels')"
                      />
                    </el-form-item>
                    <!-- IMAGE ACTIVE ZH -->
                    <el-form-item
                      :prop="'buttons[' + key + '].images.local.inactive'"
                      :rules="[{ required: true, dynamically: true, validator: validateImage, trigger: 'blur' }]"
                    >
                      <div
                        :ref="'buttons[' + key + '].images.local.inactive'"
                        class="wrapper-image"
                        @click="handlerSelectImage('btn-' + key + '-img-zh-inactive', true)"
                      >
                        <i
                          v-if="current.buttons[key].images.local.inactive === ''"
                          class="el-icon-camera-solid"
                        />
                        <img
                          v-else
                          :src="current.buttons[key].images.local.inactive"
                        >
                      </div>
                    </el-form-item>
                    <el-button
                      size="small"
                      type="primary"
                      plain
                      class="margin-top-21"
                      @click="handlerSelectImage('btn-' + key + '-img-zh-inactive', true)"
                    >
                      {{ $t('btn.uploadPicture') }}
                    </el-button>
                    <!-- INPUT IMAGE FILE -->
                    <input
                      :ref="'btn-' + key + '-img-zh-inactive'"
                      :name="'button-' + key + '-image-zh-inactive'"
                      type="file"
                      class="no-display"
                      @change="handlerSelectedImage('btn-' + key + '-img-zh-inactive', setButtonsImages, key, 'inactive')"
                    >
                  </div>
                </el-col>
              </el-row>
            </el-form-item>
            <!-- REMOVE BUTTON -->
            <div class="center">
              <el-button icon="el-icon-close" plain type="danger" @click="handlerRemoveButton(key)" />
            </div>
            <!-- Separate Line -->
            <div class="wrapper-separate-line">
              <div class="bottom-line margin-bottom-20 padding-top-17" />
            </div>
          </div>
          <!-- WRAPPER ADD BUTTON -->
          <div v-if="addButton === false">
            <!-- ADD BUTTON -->
            <div class="center">
              <el-button icon="el-icon-plus" plain type="success" @click="handlerAddNewButton" />
            </div>
          </div>
        </div>
        <!-- <|- FOOTER FORM -|> -->
        <!-- FORM BUTTONS -->
        <el-form-item style="padding: 20px 0 0 11%;">
          <el-button plain type="success" @click="handlerCreateReward">{{ $t('btn.confirm') }}</el-button>
          <el-button plain type="primary" @click="handlerCloseForm">{{ $t('btn.cancel') }}</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
    <div />
  </div>
</template>

<script>
import Resource from '@/api/resource';
import PermissionChecker from '@/components/Permissions/index.vue';
import UploadImage from '@/utils/uploadImage';
import Pagination from '@/components/Pagination';
import viewsPermissions from '@/viewsPermissions.js';

const sevenDaysWelfare = new Resource('seven-days-welfare');
const platformsGames = new Resource('platforms-games');

export default {
  name: 'SevenDaysWelfare',
  components: {
    Pagination,
    PermissionChecker,
  },
  data() {
    return {
      viewPermission: viewsPermissions.sevenDaysWelfare.permissions.controls,
      editMode: false,
      loading: false,
      multipleSelectionsevenDaysWelfare: [],
      // for image
      inputsImages: {
        en: '',
        zh: '',
      },
      cacheInputsImages: {
        en: '',
        zh: '',
      },
      // for banners
      cacheButtonsImages: {},
      inputButtonsImages: {},
      bannerImages: {
        'en': '',
        'zh': '',
      },
      cacheBannerImages: {
        'en': '',
        'zh': '',
      },
      current: {
        id: undefined,
        titleEn: '',
        titleLocal: '',
        receiveRequirements: '',
        displayPriority: 999,
        startTime: '',
        endTime: '',
        actionType: 'NONE',
        action: '',
        active: false,
        bannerEn: '',
        bannerLocal: '',
        imageEn: '',
        imageLocal: '',
        type: 'POPULAR',
        maxRewardLimit: 0,
        canEdit: false,
        buttons: {},
      },
      list: [],
      query: {
        limit: 20,
        page: 1,
      },
      total: 0,
      formVisible: false,
      title: '',
      // Types and Actions
      receiveRequirements: [
        {
          label: 'Number of logins',
          labelZH: '登入次数',
          value: 'LOGIN',
        },
        {
          label: 'Recharge amount',
          labelZH: '充值金额',
          value: 'RECHARGE',
        },
        {
          label: 'Number of Recharges per day',
          labelZH: '单日充值金额',
          value: 'RECHARGE_PER_DAY',
        },
        {
          label: 'Bet amount daily',
          labelZH: '单日打码',
          value: 'BET_AMOUNT_PER_DAY',
        },
        {
          label: 'Bet amount - period statistics',
          labelZH: '累积打码量',
          value: 'BET_AMOUNT_PER_PERIOD',
        },
      ],
      types: [
        { label: this.$t('labels.welfarePopular'), value: this.$t('labels.welfarePopular', 'en') },
        { label: this.$t('labels.welfareLimitedTime'), value: this.$t('labels.welfareLimitedTime', 'en') },
        { label: this.$t('labels.welfareUpToDate'), value: this.$t('labels.welfareUpToDate', 'en') },
      ],
      actionTypes: [
        { label: this.$t('labels.welfareNone'), value: this.$t('labels.welfareNone', 'en') },
        { label: this.$t('labels.welfareUI'), value: this.$t('labels.welfareUI', 'en') },
        { label: this.$t('labels.welfareGame'), value: this.$t('labels.welfareGame', 'en') },
        { label: this.$t('labels.welfareURL'), value: this.$t('labels.welfareURL', 'en') },
      ],
      // Actions
      ui: ['Customer service chat', 'Active', 'InBox', 'VIP', 'Finance', 'Personal center'],
      game: [],
      // for upload image
      files: [],
      // uploadImageUri: '/sevenDaysWelfare-management-upload-images/',
      uploadImageUri: '/seven-days-welfare-upload-images/',
      switchLists: [
        {
          label: this.$t('btn.on'),
          value: true,
        },
        {
          label: this.$t('btn.off'),
          value: false,
        },
      ],
      buttons: {},
      addButton: true,
      buttonId: 1,
      // Rules
      rules: {
        titleEn: [{ required: true, message: this.$t('messages.notBeEmpty'), trigger: 'blur' }],
        titleLocal: [{ required: true, message: this.$t('messages.notBeEmpty'), trigger: 'blur' }],
      },
    };
  },
  computed: {
    language: function() {
      return this.$store.getters.language;
    },
  },
  created() {
    this.getSevenDaysWelfare();
    this.getPlatformsGames();
  },
  methods: {
    async getSevenDaysWelfare() {
      const { limit, page } = this.query;
      this.loading = true;
      const { data, meta } = await sevenDaysWelfare.list(this.query);
      this.list = data;
      this.list.forEach((item, index) => {
        item['index'] = (page - 1) * limit + index + 1;
      });
      this.total = meta.total;
      this.loading = false;
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
    handlerDeleteMultipleSelectionSevenDaysWelfare() {
      const ids = [];
      const links = [];
      if (this.multipleSelectionsevenDaysWelfare.length === 0) {
        // this.$message({
        //   type: 'error',
        //   message: this.$t('messages.noDeleteItems'),
        //   duration: 1 * 1000,
        // });
      } else {
        this.multipleSelectionsevenDaysWelfare.forEach((item, index) => {
          ids.push(item.id);
          this.getCurrentImages(item, links);
          this.getCurrentBannerImages(item, links);
          // get links images on created buttons
          this.getButtonsImagesLinkActiveAndInactive(item, links);
        });
        // CONFIRM MESSAGE
        this.$confirm(this.$t('messages.deleteConfirmMessage') + '?', 'Warning', {
          confirmButtonText: this.$t('btn.ok'),
          cancelButtonText: this.$t('btn.cancel'),
          type: 'warning',
        })
          .then(() => {
            sevenDaysWelfare
              .del(ids.toString(), links)
              .then(() => {
                this.$message({
                  type: 'success',
                  message: this.$t('messages.delCompleteUser'),
                  duration: 1 * 1000,
                });
              })
              .finally(() => {
                this.getSevenDaysWelfare();
              });
          })
          .catch(() => {
            this.$message({
              type: 'info',
              message: this.$t('messages.delCancelUser'),
            });
          });
      }
    },
    handlerAddReward() {
      this.title = this.$t('labels.addWelfare');
      this.formVisible = true;
      //
    },
    handlerMultipleSelectionSevenDaysWelfare(val) {
      this.multipleSelectionsevenDaysWelfare = val;
    },
    handlerDeleteSevenDaysWelfare(obj) {
      this.$confirm(this.$t('messages.deleteConfirmMessage' + '?'), 'Warning', {
        confirmButtonText: this.$t('btn.ok'),
        cancelButtonText: this.$t('btn.cancel'),
      })
        .then(() => {
          const id = obj.id;
          const links = [];
          this.getCurrentImages(obj, links);
          this.getCurrentBannerImages(obj, links);
          this.getButtonsImagesLinkActiveAndInactive(obj.buttons, links);
          if (links.length > 0) {
            sevenDaysWelfare
              .del(id, links)
              .then(() => {
                this.$message({
                  type: 'success',
                  message: this.$t('messages.delCompleteUser'),
                });
              }).finally(() => {
                this.handlerResetCurrentData();
                this.getSevenDaysWelfare();
              });
          } else {
            sevenDaysWelfare
              .destroy(id)
              .then(() => {
                this.$message({
                  type: 'success',
                  message: this.$t('messages.delCompleteUser'),
                });
              }).finally(() => {
                this.handlerResetCurrentData();
                this.getSevenDaysWelfare();
              });
          }
        })
        .catch(() => {
          this.$message({
            type: 'info',
            message: this.$t('messages.delCancelUser'),
          });
        });
    },
    handlerEditSevenDaysWelfare(id, obj) {
      // const row = this.list.find((item) => item.id === id);
      this.title = this.$t('btn.update') + ' Welfare';
      this.formVisible = true;
      // on edit mode
      this.editMode = true;
      // edit current reward
      this.current = obj;
      const buttons = this.current.buttons;
      let key;
      for (key in buttons) {
        this.initInputButtonsImages(key, buttons[key]['images']);
      }
      // set image url
      this.initBannerImages(this.current);
      // set button images
      this.initImages(this.current);
    },
    initBannerImages(obj) {
      this.bannerImages.en = obj.bannerEn;
      this.bannerImages.zh = obj.bannerLocal;
      // init cache
      this.cacheBannerImages.en = obj.bannerEn;
      this.cacheBannerImages.zh = obj.bannerLocal;
    },
    initImages(obj) {
      this.inputsImages.en = obj.imageEn;
      this.inputsImages.zh = obj.imageLocal;
      // init cache
      this.cacheInputsImages.en = obj.imageEn;
      this.cacheInputsImages.zh = obj.imageLocal;
    },
    // init
    initInputButtonsImages(key, images) {
      this.inputButtonsImages[key] = {};
      this.inputButtonsImages[key]['en'] = {};
      this.inputButtonsImages[key]['zh'] = {};
      // en input set
      this.inputButtonsImages[key]['en']['active'] = images.en.active;
      this.inputButtonsImages[key]['en']['inactive'] = images.en.inactive;
      // zh input set
      this.inputButtonsImages[key]['zh']['active'] = images.local.active;
      this.inputButtonsImages[key]['zh']['inactive'] = images.local.inactive;
      // set cache
      this.cacheButtonsImages[key] = {};
      this.cacheButtonsImages[key]['en'] = {};
      this.cacheButtonsImages[key]['zh'] = {};
      //
      this.cacheButtonsImages[key]['en']['active'] = images.en.active;
      this.cacheButtonsImages[key]['en']['inactive'] = images.en.inactive;
      //
      this.cacheButtonsImages[key]['zh']['active'] = images.local.active;
      this.cacheButtonsImages[key]['zh']['inactive'] = images.local.active;
    },
    handlerResetCurrentData() {
      this.current = {
        id: undefined,
        titleEn: '',
        titleLocal: '',
        receiveRequirements: '',
        displayPriority: 999,
        startTime: '',
        endTime: '',
        actionType: 'NONE',
        action: '',
        active: false,
        bannerEn: '',
        bannerLocal: '',
        imageEn: '',
        imageLocal: '',
        type: '',
        maxRewardLimit: 0,
        canEdit: false,
        buttons: {},
      };
      // RESET INPUT DATA
      // banner images
      this.bannerImages = {
        'en': '',
        'zh': '',
      };
      this.cacheBannerImages = {
        'en': '',
        'zh': '',
      };
      // inputs images
      this.inputsImages = {
        en: '',
        zh: '',
      };
      this.cacheInputsImages = {
        en: '',
        zh: '',
      };
      // image on created buttons
      this.cacheButtonsImages = {};
      this.inputButtonsImages = {};
    },
    handlerSelectedImage(name, callback, id = 0, actionType = '') {
      let img;
      if (id > 0) {
        img = this.files[name] = this.$refs[name][0].files[0];
      } else {
        img = this.files[name] = this.$refs[name].files[0];
      }
      const type = img.type;
      // UPLOAD FILES
      UploadImage(this.uploadImageUri, type, img)
        .then(response => {
          const { data } = response;
          const url = data.url;
          callback(name, url, id, actionType);
        });
    },
    // Images
    setImages(name, url) {
      if (name === 'imgs-en') {
        this.inputsImages.en = url;
        this.current.imageEn = url;
        this.cacheInputsImages.en = url;
      }
      if (name === 'imgs-sc') {
        this.inputsImages.zh = url;
        this.current.imageLocal = url;
        this.cacheInputsImages.zh = url;
      }
    },
    // BANNER
    setBannerImage(name, url) {
      if (name === 'img-en') {
        this.bannerImages.en = url;
        this.current.bannerEn = url;
        this.cacheBannerImages.en = url;
      }
      if (name === 'img-sc') {
        this.bannerImages.zh = url;
        this.current.bannerLocal = url;
        this.cacheBannerImages.zh = url;
      }
    },
    // SET IMAGES
    setButtonsImages(name, url, id, type) {
      if (name === 'btn-' + id + '-img-en-' + type) {
        this.inputButtonsImages[id]['en'][type] = url;
        this.current.buttons[id]['images']['en'][type] = url;
        this.cacheButtonsImages[id]['en'][type] = url;
      }
      if (name === 'btn-' + id + '-img-zh-' + type) {
        this.inputButtonsImages[id]['zh'][type] = url;
        this.current.buttons[id]['images']['local'][type] = url;
        this.cacheButtonsImages[id]['zh'][type] = url;
      }
    },
    handlerSelectImage(name, last = false) {
      if (last) {
        this.$refs[name][0].click();
      } else {
        this.$refs[name].click();
      }
    },
    handlerCloseForm() {
      const links = [];
      this.formVisible = false;
      // set delete links
      this.getCacheBannerImagesLink(links);
      this.getCacheInputsImagesLink(links);
      this.getCacheButtonsImagesLink(links);
      // send delet if length > 0
      if (!this.editMode) {
        if (links.length > 0) {
          sevenDaysWelfare.del(0, links)
            .then(() => {
              this.$message({
                type: 'success',
                message: this.$t('messages.selectedImageBeenDeleted'),
              });
            });
        }
      }
      this.handlerResetCurrentData();
    },
    handlerCreateReward() {
      this.$refs['rewards'].validate(valid => {
        console.log('Validate::', valid);
        if (valid) {
          if (this.current.id === undefined) {
            sevenDaysWelfare
              .store(this.current)
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
                if (valid) {
                  this.formVisible = false;
                  this.handlerResetCurrentData();
                  this.getSevenDaysWelfare();
                }
              });
          } else {
            if (this.current.index) {
              delete this.current.index;
            }
            sevenDaysWelfare
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
                if (valid) {
                  this.formVisible = false;
                  this.handlerResetCurrentData();
                  this.getSevenDaysWelfare();
                }
              });
          }
        } else {
          return false;
        }
      });
    },
    // Actions
    handlerChangeActionType() {
      // Clear action
      if (this.current.actionType === 'NONE' || this.current.actionType === 'URL') {
        this.current.action = '';
      } else if (this.current.actionType === 'UI') {
        // set default value for UI
        this.current.action = this.ui[0];
      } else if (this.current.actionType === 'GAME') {
        // set default value for GAME
        this.current.action = this.game[0];
      }
    },
    handlerButtonIsset(id) {
      return this.current.buttons[id];
    },
    handlerAddNewButtonInButtons(id, obj) {
      if (id !== undefined && obj !== undefined) {
        this.$set(this.current.buttons, id, obj);
      }
    },
    //
    handlerGetObjectButton() {
      return {
        images: {
          en: {
            active: '',
            inactive: '',
          },
          local: {
            active: '',
            inactive: '',
          },
        },
        receiveAmount: 0,
        condition: 0,
      };
    },
    handlerAddNewButton() {
      this.addButton = false;
      // set input url image
      this.$set(this.inputButtonsImages, this.buttonId, this.getInputButtonsImages(this.buttonId));
      // set cache input url image
      this.$set(this.cacheButtonsImages, this.buttonId, this.getCacheButtonsImages(this.buttonId));
      // add button
      this.handlerAddNewButtonInButtons(this.buttonId, this.handlerGetObjectButton());
      this.buttonId++;
    },
    handlerRemoveButton(id) {
      this.$delete(this.current.buttons, id);
      this.buttonId--;
    },
    handlerDeleteAllAddedButtons() {
      if (this.current.active === false) {
        this.$delete(this.current, this.current.buttons);
        this.$set(this.current, 'buttons', {});
        this.buttonId = 1;
      }
    },
    //
    getMessage(name, index = 'messages') {
      return this.$t(index + '.' + name);
    },
    //
    getInputButtonsImages(id) {
      const obj = {};
      // set object
      obj[id] = {
        'en': {
          'active': '',
          'inactive': '',
        },
        'zh': {
          'active': '',
          'inactive': '',
        },
      };
      return obj[id];
    },
    //
    getCacheButtonsImages(id) {
      const obj = {};
      // set obj
      obj[id] = {
        'en': {
          'active': '',
          'inactive': '',
        },
        'zh': {
          'active': '',
          'inactive': '',
        },
      };
      return obj[id];
    },
    // validate
    validateImage(rule, value, callback) {
      const img = rule.field;
      const btns = rule.dynamically;
      let minImg;
      if (value === '') {
        callback(new Error(this.$t('messages.pictureMustDownloaded')));
        if (btns) {
          minImg = this.$refs[img][0];
        } else {
          minImg = this.$refs[img];
        }
        minImg.className += ' error';
      } else {
        callback();
      }
    },
    handlerSetReceiveAmountToFloat(id) {
      this.current.buttons[id].receiveAmount = Number.parseFloat(this.current.buttons[id].receiveAmount);
    },
    handlerSetMaxRewardLimitToFloat() {
      this.current.maxRewardLimit = Number.parseFloat(this.current.maxRewardLimit);
    },
    // get image link
    getCacheBannerImagesLink(links) {
      const images = this.cacheBannerImages;
      if (images.en !== '') {
        links.push(images.en);
      }
      if (images.zh !== '') {
        links.push(images.zh);
      }
    },
    getCacheInputsImagesLink(links) {
      const images = this.cacheInputsImages;
      if (images.en !== '') {
        links.push(images.en);
      }
      if (images.zh !== '') {
        links.push(images.zh);
      }
    },
    getCacheButtonsImagesLink(links) {
      let key;
      const buttons = this.cacheButtonsImages;
      for (key in buttons) {
        if (buttons[key].en.active !== '') {
          links.push(buttons[key].en.active);
        }
        if (buttons[key].en.inactive !== '') {
          links.push(buttons[key].en.inactive);
        }
        if (buttons[key].zh.active !== '') {
          links.push(buttons[key].zh.active);
        }
        if (buttons[key].zh.inactive !== '') {
          links.push(buttons[key].zh.inactive);
        }
      }
    },
    // get links in buttons
    getButtonsImagesLinkActiveAndInactive(obj, arr) {
      let key;
      for (key in obj) {
        if (obj[key].images !== undefined) {
          if (obj[key].images.en.active !== '') {
            arr.push(obj[key].images.en.active);
          }
          if (obj[key].images.en.inactive !== '') {
            arr.push(obj[key].images.en.inactive);
          }
          if (obj[key].images.local.active !== '') {
            arr.push(obj[key].images.local.active);
          }
          if (obj[key].images.local.inactive !== '') {
            arr.push(obj[key].images.local.inactive);
          }
        }
      }
    },
    // get url to current obj
    getCurrentImages(obj, arr) {
      if (obj.imageEn !== '') {
        arr.push(obj.imageEn);
      }
      if (obj.imageLocal !== '') {
        arr.push(obj.imageLocal);
      }
    },
    // get to current banner image url
    getCurrentBannerImages(obj, arr) {
      if (obj.bannerEn !== '') {
        arr.push(obj.bannerEn);
      }
      if (obj.bannerLocal !== '') {
        arr.push(obj.bannerLocal);
      }
    },
  },
};
</script>

<style lang="scss">
.no-display {
  display: none;
}
.error {
  border: 1px solid #ff4949;
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
  position: relative;
}
.margin-bottom-20 {
  margin-bottom: 20px;
}
.bottom-line {
  border-bottom: 1px solid #dcdfe6;
}
.wrapper-image {
  cursor: pointer;
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
.padding-top-17 {
  padding-top: 17px;
}
.padding-top-30 {
  padding-top: 30px;
}
.margin-bottom-30 {
  margin-bottom: 30px;
}
.label-in-line {
  position: absolute;
  background: #fff;
  font-size: 1.1rem;
  top: 7px;
  padding-right: 10px;
  font-weight: bold;
}
.padding-top-15 {
  padding-top: 15px;
}
.margin-top-21 {
  margin-top: 21px;
}
.padding-right-17
{
padding-right: 17px;
}
.for-mode {
  & > .el-form-item__content {
    margin-left: 117px!important;
  }
}
.center {
  text-align: center;
}
</style>
