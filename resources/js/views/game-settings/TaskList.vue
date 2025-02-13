<template>
  <div class="app-container">
    <div v-if="showPageNewTask">
      <el-form ref="ruleForm" :model="newTask" label-position="right" label-width="20px">
        <el-row align="middle">
          <el-col :span="3" class="left-column">
            <span>{{ $t('labels.taskManagementTableNewTaskType') }}</span>
          </el-col>
          <el-col :span="21" class="right-column">
            <el-form-item>
              <el-select v-model="newTask.type" @change="changeItem">
                <el-option v-for="val in types" :key="val.id" :label="val.name" :value="val.id" />
              </el-select>
            </el-form-item>
          </el-col>
        </el-row>
        <!-- start "id": 1, "name": "Daily Tasks" -->
        <div v-if="newTask.type === 1">
          <!-- item -->
          <el-row>
            <el-col :span="3" class="left-column">
              <span>{{ $t('labels.taskManagementTableNewTaskItem') }}</span>
            </el-col>
            <el-col :span="21" class="right-column">
              <el-form-item
                class="wrap-radio-item"
                prop="item"
                :rules="[
                  { required: true, message: 'select a item, required'},
                ]"
              >
                <el-radio v-model="newTask.item" :label="1">{{ $t('labels.effectiveBetAmountTask') }}</el-radio>
                <el-radio v-model="newTask.item" :label="2">{{ $t('labels.loseMoneyTask') }}</el-radio>
                <el-radio v-model="newTask.item" :label="3">{{ $t('labels.winningTask') }}</el-radio>
              </el-form-item>
            </el-col>
          </el-row>
          <!-- companyId -->
          <el-row>
            <el-col :span="3" class="left-column">
              <span>{{ $t('labels.taskManagementTableNewCompany') }}</span>
            </el-col>
            <el-col :span="21" class="right-column">
              <el-form-item
                prop="companyId"
                :rules="[
                  { required: true, message: 'select a company, required'},
                ]"
              >
                <el-select v-model="newTask.companyId" filterable placeholder="Select">
                  <el-option label="All companies" value="0" />
                  <el-option v-for="val in companies" :key="val.id" :label="val.name" :value="val.id" />
                </el-select>
              </el-form-item>
            </el-col>
          </el-row>
          <!-- requirement -->
          <el-row>
            <el-col :span="3" class="left-column">
              <span>{{ $t('labels.taskManagementTableNewTaskRequirements') }}</span>
            </el-col>
            <el-col :span="21" class="right-column">
              <el-form-item
                prop="requirement"
                :rules="[
                  { required: true, message: 'Requirements is required'},
                  { type: 'number', message: 'Requirements must be a number'}
                ]"
              >
                <el-input v-model.number="newTask.requirement" :placeholder="$t('labels.taskManagementTableNewTaskRequirementsPlaceHolder')" />
              </el-form-item>
            </el-col>
          </el-row>
          <!-- reward -->
          <el-row>
            <el-col :span="3" class="left-column">
              <span>{{ $t('labels.taskManagementTableNewTaskRewards') }}</span>
            </el-col>
            <el-col :span="21" class="right-column">
              <el-form-item
                prop="reward"
                :rules="[
                  { required: true, message: 'Reward is required'},
                  { type: 'number', message: 'Reward must be a number'}
                ]"
              >
                <el-input v-model.number="newTask.reward" :placeholder="$t('labels.taskManagementTableNewTaskRewardsPlaceHolder')" />
              </el-form-item>
            </el-col>
          </el-row>
          <!-- displayPriority -->
          <el-row>
            <el-col :span="3" class="left-column">
              <span>{{ $t('labels.taskManagementTableNewDisplayPriority') }}</span>
            </el-col>
            <el-col :span="21" class="right-column">
              <el-form-item
                prop="displayPriority"
                :rules="[
                  { required: true, message: 'Display Priority is required'},
                  { type: 'number', message: 'Display Priority must be a number'}
                ]"
              >
                <el-input v-model.number="newTask.displayPriority" :placeholder="$t('labels.taskManagementTableNewTaskRewardsPlaceHolder')" />
              </el-form-item>
            </el-col>
          </el-row>
          <!-- titleSc -->
          <el-row>
            <el-col :span="3" class="left-column">
              <span>{{ $t('labels.taskManagementTableNewTaskName') }}</span>
            </el-col>
            <el-col :span="21" class="right-column">
              <el-form-item
                prop="titleEn"
                :rules="[
                  { required: true, message: 'Title Sc is required'},
                ]"
              >
                <el-input v-model="newTask.titleEn" :placeholder="$t('labels.taskManagementTableNewPlaceholderEn')" />
              </el-form-item>
            </el-col>
          </el-row>
          <!-- titleSc -->
          <el-row>
            <el-col :span="21" :offset="3" class="right-column">
              <el-form-item
                prop="titleSc"
                :rules="[
                  { required: true, message: 'Title Sc is required'},
                ]"
              >
                <el-input v-model="newTask.titleSc" :placeholder="$t('labels.taskManagementTableNewPlaceholderSc')" />
              </el-form-item>
            </el-col>
          </el-row>
          <!-- descriptionEn -->
          <el-row>
            <el-col :span="3" class="left-column">
              <span>{{ $t('labels.taskManagementTableNewTaskInfo') }}</span>
            </el-col>
            <el-col :span="21" class="right-column">
              <el-form-item
                prop="descriptionEn"
                :rules="[
                  { required: true, message: 'Description en is required'},
                ]"
              >
                <el-input v-model="newTask.descriptionEn" :placeholder="$t('labels.taskManagementTableNewPlaceholderEn')" />
              </el-form-item>
            </el-col>
          </el-row>
          <!-- descriptionSc -->
          <el-row>
            <el-col :span="21" :offset="3" class="right-column">
              <el-form-item
                prop="descriptionSc"
                :rules="[
                  { required: true, message: 'Description sc is required'},
                ]"
              >
                <el-input v-model="newTask.descriptionSc" :placeholder="$t('labels.taskManagementTableNewPlaceholderSc')" />
              </el-form-item>
            </el-col>
          </el-row>
          <!-- isOpen -->
          <el-row>
            <el-col :span="3" class="left-column">
              <span>{{ $t('labels.taskManagementTableNewTaskStatus') }}</span>
            </el-col>
            <el-col :span="21" class="right-column">
              <el-form-item
                prop="isOpen"
                :rules="[
                  { required: true, message: 'Status is required'},
                ]"
              >
                <el-select v-model="newTask.isOpen">
                  <el-option v-for="val in valueIsOpen" :key="val.label" :label="val.label" :value="val.value" />
                </el-select>
              </el-form-item>
            </el-col>
          </el-row>
        </div>
        <!-- end "id": 1, "name": "Daily Tasks" -->
        <!-- start "id": 2, "name": "Recharge Tasks" -->
        <div v-if="newTask.type === 2">
          <!-- item -->
          <el-row>
            <el-col :span="3" class="left-column">
              <span>{{ $t('labels.taskManagementTableNewTaskItem') }}</span>
            </el-col>
            <el-col :span="21" class="right-column">
              <el-form-item
                class="wrap-radio-item"
                prop="item"
                :rules="[
                  { required: true, message: 'select a item, required'},
                ]"
              >
                <el-radio v-model="newTask.item" :label="4">{{ $t('labels.cumulativeRechargeOnDay') }}</el-radio>
                <el-radio v-model="newTask.item" :label="5">{{ $t('labels.singleRechargeUpperLimit') }}</el-radio>
              </el-form-item>
            </el-col>
          </el-row>
          <!-- requirement -->
          <el-row>
            <el-col :span="3" class="left-column">
              <span>{{ $t('labels.taskManagementTableNewTaskRequirements') }}</span>
            </el-col>
            <el-col :span="21" class="right-column">
              <el-form-item
                prop="requirement"
                :rules="[
                  { required: true, message: 'Requirements is required'},
                  { type: 'number', message: 'Requirements must be a number'}
                ]"
              >
                <el-input v-model.number="newTask.requirement" :placeholder="$t('labels.taskManagementTableNewTaskRequirementsPlaceHolder')" />
              </el-form-item>
            </el-col>
          </el-row>
          <!-- reward -->
          <el-row>
            <el-col :span="3" class="left-column">
              <span>{{ $t('labels.taskManagementTableNewTaskRewards') }}</span>
            </el-col>
            <el-col :span="21" class="right-column">
              <el-form-item
                prop="reward"
                :rules="[
                  { required: true, message: 'Reward is required'},
                  { type: 'number', message: 'Reward must be a number'}
                ]"
              >
                <el-input v-model.number="newTask.reward" :placeholder="$t('labels.taskManagementTableNewTaskRewardsPlaceHolder')" />
              </el-form-item>
            </el-col>
          </el-row>
          <!-- displayPriority -->
          <el-row>
            <el-col :span="3" class="left-column">
              <span>{{ $t('labels.taskManagementTableNewDisplayPriority') }}</span>
            </el-col>
            <el-col :span="21" class="right-column">
              <el-form-item
                prop="displayPriority"
                :rules="[
                  { required: true, message: 'Display Priority is required'},
                  { type: 'number', message: 'Display Priority must be a number'}
                ]"
              >
                <el-input v-model.number="newTask.displayPriority" :placeholder="$t('labels.taskManagementTableNewTaskRewardsPlaceHolder')" />
              </el-form-item>
            </el-col>
          </el-row>
          <!-- titleSc -->
          <el-row>
            <el-col :span="3" class="left-column">
              <span>{{ $t('labels.taskManagementTableNewTaskName') }}</span>
            </el-col>
            <el-col :span="21" class="right-column">
              <el-form-item
                prop="titleEn"
                :rules="[
                  { required: true, message: 'Title Sc is required'},
                ]"
              >
                <el-input v-model="newTask.titleEn" :placeholder="$t('labels.taskManagementTableNewPlaceholderEn')" />
              </el-form-item>
            </el-col>
          </el-row>
          <!-- titleSc -->
          <el-row>
            <el-col :span="21" :offset="3" class="right-column">
              <el-form-item
                prop="titleSc"
                :rules="[
                  { required: true, message: 'Title Sc is required'},
                ]"
              >
                <el-input v-model="newTask.titleSc" :placeholder="$t('labels.taskManagementTableNewPlaceholderSc')" />
              </el-form-item>
            </el-col>
          </el-row>
          <!-- descriptionEn -->
          <el-row>
            <el-col :span="3" class="left-column">
              <span>{{ $t('labels.taskManagementTableNewTaskInfo') }}</span>
            </el-col>
            <el-col :span="21" class="right-column">
              <el-form-item
                prop="descriptionEn"
                :rules="[
                  { required: true, message: 'Description en is required'},
                ]"
              >
                <el-input v-model="newTask.descriptionEn" :placeholder="$t('labels.taskManagementTableNewPlaceholderEn')" />
              </el-form-item>
            </el-col>
          </el-row>
          <!-- descriptionSc -->
          <el-row>
            <el-col :span="21" :offset="3" class="right-column">
              <el-form-item
                prop="descriptionSc"
                :rules="[
                  { required: true, message: 'Description sc is required'},
                ]"
              >
                <el-input v-model="newTask.descriptionSc" :placeholder="$t('labels.taskManagementTableNewPlaceholderSc')" />
              </el-form-item>
            </el-col>
          </el-row>
          <!-- isOpen -->
          <el-row>
            <el-col :span="3" class="left-column">
              <span>{{ $t('labels.taskManagementTableNewTaskStatus') }}</span>
            </el-col>
            <el-col :span="21" class="right-column">
              <el-form-item
                prop="isOpen"
                :rules="[
                  { required: true, message: 'Status is required'},
                ]"
              >
                <el-select v-model="newTask.isOpen">
                  <el-option v-for="val in valueIsOpen" :key="val.label" :label="val.label" :value="val.value" />
                </el-select>
              </el-form-item>
            </el-col>
          </el-row>
        </div>
        <!-- end "id": 2, "name": "Recharge Tasks" -->
        <!-- start "id": 3, "name": "Time limited mission" -->
        <div v-if="newTask.type === 3">
          <!-- item -->
          <el-row>
            <el-col :span="3" class="left-column">
              <span>{{ $t('labels.taskManagementTableNewTaskItem') }}</span>
            </el-col>
            <el-col :span="21" class="right-column">
              <el-form-item
                class="wrap-radio-item"
                prop="item"
                :rules="[
                  { required: true, message: 'select a item, required'},
                ]"
              >
                <el-radio v-model="newTask.item" :label="1">{{ $t('labels.effectiveBetAmountTask') }}</el-radio>
                <el-radio v-model="newTask.item" :label="2">{{ $t('labels.loseMoneyTask') }}</el-radio>
                <el-radio v-model="newTask.item" :label="3">{{ $t('labels.winningTask') }}</el-radio>
                <el-radio v-model="newTask.item" :label="4">{{ $t('labels.cumulativeRechargeOnDay') }}</el-radio>
                <el-radio v-model="newTask.item" :label="5">{{ $t('labels.singleRechargeUpperLimit') }}</el-radio>
              </el-form-item>
            </el-col>
          </el-row>
          <!-- startDateTime endDateTime showEndDateTime -->
          <el-row>
            <el-col :span="3" class="left-column">
              <span>{{ $t('labels.taskManagementTableNewTimeLimit') }}</span>
            </el-col>
            <el-col :span="21" class="right-column">
              <span class="date-picker-item">
                <el-form-item
                  prop="startDateTime"
                  :rules="[
                    { required: true, message: 'Start Date Time is required'},
                  ]"
                >
                  <el-date-picker
                    v-model="newTask.startDateTime"
                    type="date"
                    :placeholder="$t('labels.taskManagementTableNewStartDateTime')"
                    format="yyyy-MM-dd HH:mm:ss"
                    value-format="yyyy-MM-dd HH:mm:ss"
                  />
                </el-form-item>
              </span>
              <span class="date-picker-item">
                <el-form-item
                  prop="endDateTime"
                  :rules="[
                    { required: true, message: 'End Date Time is required'},
                  ]"
                >
                  <el-date-picker
                    v-model="newTask.endDateTime"
                    type="datetime"
                    :placeholder="$t('labels.taskManagementTableNewStartDateTime')"
                    format="yyyy-MM-dd HH:mm:ss"
                    value-format="yyyy-MM-dd HH:mm:ss"
                  />
                </el-form-item>
              </span>
              <span class="date-picker-item">
                <el-form-item
                  prop="showEndDateTime"
                  :rules="[
                    { required: true, message: 'Show End Date Time is required'},
                  ]"
                >
                  <el-date-picker
                    v-model="newTask.showEndDateTime"
                    type="datetime"
                    :placeholder="$t('labels.taskManagementTableNewStartDateTime')"
                    format="yyyy-MM-dd HH:mm:ss"
                    value-format="yyyy-MM-dd HH:mm:ss"
                  />
                </el-form-item>
              </span>
            </el-col>
          </el-row>
          <!-- requirement -->
          <el-row>
            <el-col :span="3" class="left-column">
              <span>{{ $t('labels.taskManagementTableNewTaskRequirements') }}</span>
            </el-col>
            <el-col :span="21" class="right-column">
              <el-form-item
                prop="requirement"
                :rules="[
                  { required: true, message: 'Requirements is required'},
                  { type: 'number', message: 'Requirements must be a number'}
                ]"
              >
                <el-input v-model.number="newTask.requirement" :placeholder="$t('labels.taskManagementTableNewTaskRequirementsPlaceHolder')" />
              </el-form-item>
            </el-col>
          </el-row>
          <!-- reward -->
          <el-row>
            <el-col :span="3" class="left-column">
              <span>{{ $t('labels.taskManagementTableNewTaskRewards') }}</span>
            </el-col>
            <el-col :span="21" class="right-column">
              <el-form-item
                prop="reward"
                :rules="[
                  { required: true, message: 'Reward is required'},
                  { type: 'number', message: 'Reward must be a number'}
                ]"
              >
                <el-input v-model.number="newTask.reward" :placeholder="$t('labels.taskManagementTableNewTaskRewardsPlaceHolder')" />
              </el-form-item>
            </el-col>
          </el-row>
          <!-- displayPriority -->
          <el-row>
            <el-col :span="3" class="left-column">
              <span>{{ $t('labels.taskManagementTableNewDisplayPriority') }}</span>
            </el-col>
            <el-col :span="21" class="right-column">
              <el-form-item
                prop="displayPriority"
                :rules="[
                  { required: true, message: 'Display Priority is required'},
                  { type: 'number', message: 'Display Priority must be a number'}
                ]"
              >
                <el-input v-model.number="newTask.displayPriority" :placeholder="$t('labels.taskManagementTableNewTaskRewardsPlaceHolder')" />
              </el-form-item>
            </el-col>
          </el-row>
          <!-- titleSc -->
          <el-row>
            <el-col :span="3" class="left-column">
              <span>{{ $t('labels.taskManagementTableNewTaskName') }}</span>
            </el-col>
            <el-col :span="21" class="right-column">
              <el-form-item
                prop="titleEn"
                :rules="[
                  { required: true, message: 'Title Sc is required'},
                ]"
              >
                <el-input v-model="newTask.titleEn" :placeholder="$t('labels.taskManagementTableNewPlaceholderEn')" />
              </el-form-item>
            </el-col>
          </el-row>
          <!-- titleSc -->
          <el-row>
            <el-col :span="21" :offset="3" class="right-column">
              <el-form-item
                prop="titleSc"
                :rules="[
                  { required: true, message: 'Title Sc is required'},
                ]"
              >
                <el-input v-model="newTask.titleSc" :placeholder="$t('labels.taskManagementTableNewPlaceholderSc')" />
              </el-form-item>
            </el-col>
          </el-row>
          <!-- descriptionEn -->
          <el-row>
            <el-col :span="3" class="left-column">
              <span>{{ $t('labels.taskManagementTableNewTaskInfo') }}</span>
            </el-col>
            <el-col :span="21" class="right-column">
              <el-form-item
                prop="descriptionEn"
                :rules="[
                  { required: true, message: 'Description en is required'},
                ]"
              >
                <el-input v-model="newTask.descriptionEn" :placeholder="$t('labels.taskManagementTableNewPlaceholderEn')" />
              </el-form-item>
            </el-col>
          </el-row>
          <!-- descriptionSc -->
          <el-row>
            <el-col :span="21" :offset="3" class="right-column">
              <el-form-item
                prop="descriptionSc"
                :rules="[
                  { required: true, message: 'Description sc is required'},
                ]"
              >
                <el-input v-model="newTask.descriptionSc" :placeholder="$t('labels.taskManagementTableNewPlaceholderSc')" />
              </el-form-item>
            </el-col>
          </el-row>
          <!-- isOpen -->
          <el-row>
            <el-col :span="3" class="left-column">
              <span>{{ $t('labels.taskManagementTableNewTaskStatus') }}</span>
            </el-col>
            <el-col :span="21" class="right-column">
              <el-form-item
                prop="isOpen"
                :rules="[
                  { required: true, message: 'Status is required'},
                ]"
              >
                <el-select v-model="newTask.isOpen">
                  <el-option v-for="val in valueIsOpen" :key="val.label" :label="val.label" :value="val.value" />
                </el-select>
              </el-form-item>
            </el-col>
          </el-row>
        </div>
        <!-- end "id": 3, "name": "Time limited mission" -->
      </el-form>
      <el-row>
        <el-col :span="21" :offset="3" class="right-column">
          <el-button type="success" plain size="small" @click="createFormNewTask('ruleForm')">{{ $t('labels.taskManagementTableNewConfirm') }}</el-button>
          <el-button type="info" plain size="small" @click="hideFormNewTask">{{ $t('labels.taskManagementTableNewCancel') }}</el-button>
        </el-col>
      </el-row>
    </div>
    <div v-else>
      <PermissionChecker :permissions="[viewPermission.create]">
        <div id="wrap-button-add">
          <el-button plain type="success" @click="showFormNewTask">{{ $t('labels.taskManagementTableNew') }}</el-button>
        </div>
      </PermissionChecker>
      <div id="wrap-table-list">
        <el-table
          :data="tableData"
          style="width: 100%"
          class="table-list-task-management"
        >
          <el-table-column
            prop="titleEn"
            :label="$t('labels.taskManagementTableTaskTypeTitleEn')"
            align="center"
          />
          <el-table-column
            prop="titleSc"
            :label="$t('labels.taskManagementTableTaskTypeTitleSc')"
            align="center"
          />
          <el-table-column
            prop="id"
            :label="$t('labels.taskManagementTableTaskID')"
            align="center"
          />
          <el-table-column
            prop="displayPriority"
            :label="$t('labels.taskManagementTableDisplayPriority')"
            align="center"
            width="77"
          />
          <el-table-column
            prop="isOpen"
            :label="$t('labels.taskManagementTableWhetherToOpen')"
            align="center"
          >
            <template slot-scope="scope">
              <div class="wrap-button-on-off">
                <span v-if="scope.row.isOpen === true">
                  <el-button size="mini" plain type="success" @click="whetherToOpen(scope.row)">{{ $t('labels.taskManagementTableOn') }}</el-button>
                </span>
                <span v-else>
                  <el-button size="mini" plain type="danger" @click="whetherToOpen(scope.row)">{{ $t('labels.taskManagementTableOff') }}</el-button>
                </span>
              </div>
            </template>
          </el-table-column>
          <el-table-column
            prop="requirement"
            :label="$t('labels.taskManagementTableTaskRequirement')"
            align="center"
          />
          <el-table-column
            prop="startDateTime"
            :label="$t('labels.taskManagementTableTaskStartTime')"
            align="center"
          />
          <el-table-column
            prop="endDateTime"
            :label="$t('labels.taskManagementTableTaskEndTime')"
            align="center"
          />
          <el-table-column
            prop="showEndDateTime"
            :label="$t('labels.taskManagementTableTaskShowEndTime')"
            align="center"
          />
          <el-table-column
            prop="numberOfParticipant"
            :label="$t('labels.taskManagementTableParticipation')"
            align="center"
          />
          <PermissionChecker :permissions="[viewPermission.edit, viewPermission.delete]">
            <el-table-column
              prop="operation"
              :label="$t('labels.taskManagementTableOperation')"
              width="160"
              align="center"
            >
              <template slot-scope="scope">
                <PermissionChecker :permissions="[viewPermission.edit]">
                  <el-button size="mini" type="primary" plain @click="editRow(scope.row)">{{ $t('btn.edit') }}</el-button>
                </PermissionChecker>
                <PermissionChecker :permissions="[viewPermission.delete]">
                  <el-button size="mini" type="danger" plain @click="deleteRow(scope.row.id)">{{ $t('btn.delete') }}</el-button>
                </PermissionChecker>
              </template>
            </el-table-column>
          </PermissionChecker>
        </el-table>
        <pagination v-show="total>0" :total="total" :page.sync="query.page" :limit.sync="query.size" @pagination="getTaskManagementList" />
      </div>
    </div>
  </div>
</template>

<script>
import Resource from '@/api/resource';
import UploadImage from '@/utils/uploadImage';
import Pagination from '@/components/Pagination';
import TaskManagement from '@/api/taskManagement';
import PermissionChecker from '@/components/Permissions/index.vue';
import viewsPermissions from '@/viewsPermissions.js';

const taskManagementResource = new TaskManagement();
const typesResource = new Resource('task-management/daily/list');
const companyResource = new Resource('task-management/company/list');
const itemResource = new Resource('task-management/item/list');

export default {
  name: 'TaskList',
  components: {
    Pagination,
    PermissionChecker,
  },
  data() {
    return {
      viewPermission: viewsPermissions.taskList.permissions.controls,
      valueIsOpen: [
        { value: true, label: 'open' },
        { value: false, label: 'close' },
      ],
      uploadImageUri: '/task-management/image-upload/',
      showPageNewTask: false,
      types: [],
      companies: [],
      items: [],
      files: [],
      total: 1,
      query: {
        page: 1,
        size: 10,
      },
      newTask: {
        id: 0,
        type: 1,
        item: '',
        companyId: '',
        startDateTime: '',
        endDateTime: '',
        showEndDateTime: '',
        picUrlEn: '',
        picUrlSc: '',
        requirement: '',
        reward: '',
        displayPriority: '',
        titleEn: '',
        titleSc: '',
        descriptionEn: '',
        descriptionSc: '',
        numberOfParticipant: 0,
        isOpen: '',
      },
      statusChecked: '',
      tableData: [{
        titleEn: '',
        titleSc: '',
        id: '',
        displayPriority: '',
        isOpen: '',
        requirement: '',
        startDateTime: '',
        endDateTime: '',
        showEndDateTime: '',
        numberOfParticipant: '',
      }],
    };
  },
  created() {
    this.getTaskManagementList();
    this.getTypes();
    this.getCompanies();
    this.getItems();
  },
  methods: {
    async getTaskManagementList() {
      this.loading = true;
      await taskManagementResource.list(this.query)
        .then(response => {
          this.loading = false;
          this.total = response.meta.total;
          this.tableData = response.data;
        });
    },
    whetherToOpen(obj){
      obj.isOpen = !obj.isOpen;
      obj.type = (this.types.find((item) => item.name === obj.type)).id;
      obj.item = (this.items.find((item) => item.name === obj.item)).id;
      this.loading = true;
      taskManagementResource.update(obj.id, obj)
        .then(response => {
          this.getTaskManagementList();
          this.$message({
            type: 'success',
            message: this.$t('messages.taskManagementStatusSuccess'),
            duration: 1 * 1000,
          });
          this.loading = false;
        });
    },
    deleteRow(id){
      this.loading = true;
      taskManagementResource.destroy(id)
        .then(response => {
          this.getTaskManagementList();
          this.$message({
            type: 'success',
            message: this.$t('messages.taskManagementStatusSuccess'),
            duration: 1 * 1000,
          });
          this.loading = false;
        });
    },
    editRow(obj){
      obj.type = (this.types.find((item) => item.name === obj.type)).id;
      obj.item = (this.items.find((item) => item.name === obj.item)).id;
      this.newTask = obj;
      this.showPageNewTask = true;
    },
    showFormNewTask() {
      this.resetFormNewTask();
      this.showPageNewTask = true;
    },
    hideFormNewTask() {
      this.showPageNewTask = false;
      if (this.newTask.id !== 0) {
        this.newTask.type = (this.types.find((item) => item.id === this.newTask.type)).name;
        this.newTask.item = (this.items.find((item) => item.id === this.newTask.item)).name;
      }
      this.resetFormNewTask();
    },
    changeItem(){
      this.$refs.ruleForm.clearValidate();
    },
    resetFormNewTask() {
      this.newTask = {
        id: 0,
        type: 1,
        item: '',
        companyId: '',
        startDateTime: '',
        endDateTime: '',
        showEndDateTime: '',
        picUrlEn: '',
        picUrlSc: '',
        requirement: '',
        reward: '',
        displayPriority: '',
        titleEn: '',
        titleSc: '',
        descriptionEn: '',
        descriptionSc: '',
        numberOfParticipant: 0,
        isOpen: '',
      };
    },
    createFormNewTask(formName) {
      this.$refs[formName].validate((valid) => {
        if (valid) {
          this.showPageNewTask = false;
          this.newTask.companyId = (this.newTask.companyId === '') ? 0 : this.newTask.companyId;
          this.loading = true;
          if (this.newTask.id === 0) {
            taskManagementResource.store(this.newTask)
              .then(response => {
                this.getTaskManagementList();
                this.$message({
                  type: 'success',
                  message: this.$t('messages.taskManagementStatusSuccess'),
                  duration: 1 * 1000,
                });
                this.loading = false;
              });
          } else {
            taskManagementResource.update(this.newTask.id, this.newTask)
              .then(response => {
                this.getTaskManagementList();
                this.$message({
                  type: 'success',
                  message: this.$t('messages.taskManagementStatusSuccess'),
                  duration: 2 * 1000,
                });
                this.loading = false;
              });
          }
        } else {
          return false;
        }
      });
    },
    handlerSelectedImage(name) {
      const img = this.files[name] = this.$refs[name].files[0];
      const type = img.type;
      UploadImage(this.uploadImageUri, type, img)
        .then(response => {
          const { data } = response;
          if (name === 'img-sc') {
            this.newTask.picUrlSc = data.url;
          }
          if (name === 'img-en') {
            this.newTask.picUrlEn = data.url;
          }
        });
    },
    getTypes() {
      this.loading = true;
      typesResource.list(this.query)
        .then(response => {
          this.loading = false;
          this.types = response.data.data;
        });
    },
    getItems() {
      this.loading = true;
      itemResource.list()
        .then(response => {
          this.loading = false;
          this.items = response.data.data.map((item) => {
            return {
              ...item,
              'type1': ((item.id === 1 || item.id === 2 || item.id === 3) ? 1 : 0),
              'type2': ((item.id === 4 || item.id === 5) ? 1 : 0),
              'type3': 1,
            };
          });
        });
    },
    getCompanies() {
      this.loading = true;
      companyResource.list(this.query)
        .then(response => {
          this.loading = false;
          this.companies = response.data.data.result;
        });
    },
    handlerSelecteImage(name) {
      this.$refs[name].click();
    },
  },
};
</script>

<style lang="scss" scoped>
  #wrap-button-add{
    margin: 0px 0 10px 0px;
  }
  .img-items{
    width: 80%;
  }
  .el-radio {
    margin: 0px 15px;
  }
  .wrap-radio-item{
    float: left;
  }
  .text-aliment-right{
    text-align: right;
  }
  .left-column{
    text-align: right;
    height: 36px;
    display: grid;
    align-items: center;
  }
  .item-center-block{
    text-align: right;
    display: grid;
    align-items: center;
  }
  .item-center-block img{
    margin: 0 auto;
  }
  .item-center-block  button{
    width: 200px;
    margin: 20px auto;
  }
  .date-picker-item{
    float: left;
    margin: 0px 20px 0px 0px;
  }
  .wrap-button-on-off{
    display: grid;
    align-items: center;
    justify-content: center;
  }
  .no-display{
    display: none;
  }
</style>
