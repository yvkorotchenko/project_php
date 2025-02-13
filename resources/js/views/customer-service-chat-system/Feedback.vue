<template>
  <div className="app-container">
    <!-- FORM REPLY -->
    <el-form v-if="showFormReply" :model="formReply" label-width="220px">
      <!-- user ID -->
      <el-row>
        <el-col :span="24">
          <el-form-item :label="$t('labels.feedbackUserID')">
            <span>{{ formReply.uid }}</span>
          </el-form-item>
        </el-col>
      </el-row>
      <!-- date -->
      <el-row>
        <el-col :span="24">
          <el-form-item :label="$t('labels.feedbackDate')">
            <span>{{ formReply.created }}</span>
          </el-form-item>
        </el-col>
      </el-row>
      <!-- question describe title -->
      <el-row>
        <el-col :span="24">
          <el-form-item :label="$t('labels.feedbackQuestionDescribeTitle')">
            <span>{{ formReply.categoryName }}</span>
          </el-form-item>
        </el-col>
      </el-row>
      <!-- question describe Description -->
      <el-row>
        <el-col :span="24">
          <el-form-item :label="$t('labels.feedbackQuestionDescribeDescription')">
            <span>{{ formReply.message }}</span>
          </el-form-item>
        </el-col>
      </el-row>
      <!-- Customer service -->
      <el-row>
        <el-col :span="24">
          <el-form-item :label="$t('labels.feedbackCustomerService')">
            <span>{{ (formReply.reply !== null) ? formReply.reply.operatorId : operator.name }}</span>
          </el-form-item>
        </el-col>
      </el-row>
      <!-- Reply date -->
      <el-row>
        <el-col :span="24">
          <el-form-item :label="$t('labels.feedbackReplyDate')">
            <span>{{ (formReply.reply !== null) ? formReply.reply.created : operator.date }}</span>
          </el-form-item>
        </el-col>
      </el-row>
      <!-- Reply content -->
      <el-row>
        <el-col :span="24">
          <el-form-item :label="$t('labels.feedbackReplyContent')">
            <span v-if="formReply.reply !== null">{{ formReply.reply.message }}</span>
            <el-input
              v-else
              v-model="formReply.replyContent"
              type="textarea"
            />
          </el-form-item>
        </el-col>
      </el-row>
      <!-- Buttons -->
      <el-row type="flex" justify="center">
        <el-col :span="4">
          <el-button v-if="formReply.reply === null" class="feedback-button-form-reply" type="primary" @click="storeFormReply">{{ $t('labels.feedbackConfirm') }}</el-button>
          <el-button class="feedback-button-form-reply" type="primary" @click="hideReplyForm">{{ $t('labels.feedbackBack') }}</el-button>
        </el-col>
      </el-row>
    </el-form>
    <el-row v-else-if="showFormDelete" class="wrap-form-delete">
      <el-col :span="24">
        <el-row>
          <el-col :span="24">
            <div>{{ $t('labels.feedbackDeleteUserFeedbackData') }}</div>
            <div>{{ $t('labels.feedbackQuestionCategory') + ' ' + deleteRow.category }}</div>
            <div>{{ $t('labels.feedbackQuestionDescription') + ' ' + deleteRow.message }}</div>
          </el-col>
        </el-row>
        <!-- Buttons -->
        <el-row type="flex" justify="center">
          <el-col :span="4">
            <el-button class="feedback-button-form-delete" type="primary" @click="deleteThisRow(deleteRow.id)">{{ $t('labels.feedbackConfirm') }}</el-button>
            <el-button class="feedback-button-form-delete" type="primary" @click="hideDeleteForm">{{ $t('labels.feedbackBack') }}</el-button>
          </el-col>
        </el-row>
      </el-col>
    </el-row>
    <!-- TABLE -->
    <div v-else>
      <el-table v-loading="loading" :data="tableData" height="650" border fit highlight-current-row>
        <!-- question category -->
        <el-table-column align="center" :label="$t('labels.feedbackQuestionCategory')">
          <template slot-scope="scope">
            <span>{{ scope.row.categoryName }}</span>
          </template>
        </el-table-column>
        <!-- question describe -->
        <el-table-column align="center" :label="$t('labels.feedbackQuestionDescribe')">
          <template slot-scope="scope">
            <span>{{ scope.row.message }}</span>
          </template>
        </el-table-column>
        <!-- user ID -->
        <el-table-column align="center" :label="$t('labels.feedbackUserID')">
          <template slot-scope="scope">
            <span>{{ scope.row.uid }}</span>
          </template>
        </el-table-column>
        <!-- date created -->
        <el-table-column align="center" :label="$t('labels.feedbackDate')">
          <template slot-scope="scope">
            <span>{{ scope.row.created }}</span>
          </template>
        </el-table-column>
        <!-- Customer service -->
        <el-table-column align="center" :label="$t('labels.feedbackCustomerService')">
          <template slot-scope="scope">
            <span>{{ (scope.row.reply === null) ? " - " : scope.row.reply.operatorId }}</span>
          </template>
        </el-table-column>
        <!-- Date -->
        <el-table-column align="center" :label="$t('labels.feedbackReplyDate')">
          <template slot-scope="scope">
            <span>{{ (scope.row.reply === null) ? " - " : scope.row.reply.created }}</span>
          </template>
        </el-table-column>
        <!-- Operate -->
        <PermissionChecker :permissions="[viewPermission.reply, viewPermission.view, viewPermission.delete]">
          <el-table-column align="center" :label="$t('labels.feedbackOperate')">
            <template slot-scope="scope">
              <PermissionChecker v-if="scope.row.reply !== null" :permissions="[viewPermission.view]">
                <el-button class="feedback-button" size="mini" type="info" @click="viewReplyForm(scope.row)">{{ $t('labels.feedbackView') }}</el-button>
              </PermissionChecker>
              <PermissionChecker v-else :permissions="[viewPermission.reply]">
                <el-button class="feedback-button" size="mini" type="primary" plain @click="viewReplyForm(scope.row)">{{ $t('labels.feedbackReply') }}</el-button>
              </PermissionChecker>
              <PermissionChecker :permissions="[viewPermission.delete]">
                <el-button class="feedback-button" size="mini" type="danger" plain @click="deleteFeedback(scope.row)">{{ $t('labels.feedbackDelete') }}</el-button>
              </PermissionChecker>
            </template>
          </el-table-column>
        </PermissionChecker>
      </el-table>
      <!-- PAGINATION -->
      <pagination v-show="total>0" :total="total" :page.sync="query.page" :limit.sync="query.size" @pagination="getfeedbacks" />
    </div>
  </div>
</template>

<script>
import Resource from '@/api/resource';
import Pagination from '@/components/Pagination';
import PermissionChecker from '@/components/Permissions/index.vue';
import viewsPermissions from '@/viewsPermissions.js';

const feedbackResource = new Resource('feedback');
const feedbackReplyResource = new Resource('feedback/reply');
const feedbackDeleteResource = new Resource('feedback/delete');
const operatorResource = new Resource('feedback/operator');

export default {
  name: 'Feedback',
  components: {
    Pagination,
    PermissionChecker,
  },
  data() {
    return {
      viewPermission: viewsPermissions.feedback.permissions.controls,
      loading: false,
      total: 1,
      query: {
        page: 1,
        size: 10,
        date: '',
        from: '',
        to: '',
      },
      operator: [],
      showFormReply: false,
      showFormDelete: false,
      formReply: {
        category: '',
        categoryName: '',
        created: '',
        id: '',
        images: [],
        message: '',
        reply: [],
        uid: '',
        replyContent: '',
      },
      deleteRow: {
        category: '',
        categoryName: '',
        created: '',
        id: '',
        images: [],
        message: '',
        reply: [],
        uid: '',
      },
      tableData: [
        {
          category: '',
          categoryName: '',
          created: '',
          id: '',
          images: [],
          message: '',
          reply: [],
          uid: '',
        },
      ],
    };
  },
  computed: {
    language: function() {
      return this.$store.getters.language;
    },
  },
  created() {
    this.getfeedbacks();
    this.getOperator();
  },
  methods: {
    getfeedbacks() {
      this.loading = true;
      feedbackResource.list(this.query)
        .then(response => {
          this.loading = false;
          this.total = response.meta.total;
          this.tableData = response.data;
        });
    },
    getOperator(){
      operatorResource.list(this.query)
        .then(response => {
          this.loading = false;
          this.operator = response;
        });
    },
    resetFormReply(){
      this.formReply = {
        userId: '',
        questionDate: '',
        questionDescribeTitle: '',
        questionDescribeDescription: '',
        customerService: '',
        replyDate: '',
        replyContent: '',
      };
    },
    storeFormReply(){
      this.loading = true;
      const obj = {
        created: this.operator.date,
        id: this.formReply.id,
        message: this.formReply.replyContent,
        operatorId: this.operator.id,
      };
      feedbackReplyResource.list(obj)
        .then(response => {
          this.loading = false;
          this.showFormReply = false;
          this.getfeedbacks();
          this.getOperator();
        });
    },
    viewReplyForm(row){
      this.formReply = row;
      this.showFormReply = true;
    },
    hideReplyForm(){
      this.showFormReply = false;
      this.showFormDelete = false;
    },
    hideDeleteForm(){
      this.showFormReply = false;
      this.showFormDelete = false;
    },
    deleteThisRow(id){
      this.loading = true;
      feedbackDeleteResource.list({ id: id })
        .then(response => {
          this.loading = false;
          this.showFormReply = false;
          this.showFormDelete = false;
          this.getfeedbacks();
          this.getOperator();
        });
    },
    deleteFeedback(row){
      this.showFormDelete = true;
      this.deleteRow = row;
    },
  },
};
</script>

<style lang="scss" scoped>
/* set colors */
$colorLine: #e6e6e6;
.bg-white {
  padding: 29px 20px 20px;
  background-color: #fff;
  box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1);
}
.margin-bottom-10 {
  margin-bottom: 10px;
}
.margin-top {
  margin-top: 15px;
}
.separate-line {
  border-top: 1px solid $colorLine;
}
.wrap-form-delete{
  text-align: center;
  margin: 50px;
}
.feedback-button-form-delete{
  margin: 20px 0px 0px 0px;
}
</style>
