<template>
  <div>
    <!-- -->
    <div class="header-box">{{ $t('titles.promoterAddress') }}</div>
    <el-form label-position="end" label-width="280px" style="width: 50%">
      <!-- INPUT Landpage url: -->
      <el-form-item :label="$t('labels.shareAddressLandpageUrl')">
        <el-input v-model="shareAddressLandpageUrl" @change="onLandingPageUrlChange" />
      </el-form-item>
      <!-- INPUT App download QRcode pic url: -->
      <el-form-item :label="$t('labels.shareAddressAppDownloadQRCode')">
        <el-input v-model="appDownloadQRCode" @change="() => generateQrCode(appDownloadQRCode, 'appDownloadQRCode', 'isShareUrlChanged')" />
      </el-form-item>
      <!-- INPUT Share fb content: -->
      <el-form-item :label="$t('labels.shareAddressShareFbContent')">
        <el-input v-model="shareFbContent" type="textarea" :rows="3" />
      </el-form-item>
      <!-- INPUT Share url: -->
      <el-form-item :label="$t('labels.shareAddressShareUrl')">
        <el-input v-model="shareUrl" @change="onShareAddressUrlChange" />
      </el-form-item>
      <el-form-item class="qr-gen-btn-wrp">
        <el-button @click="() => generateQrCode(shareUrl, 'shareQRcode', 'isShareUrlChanged')">Generate QR</el-button>
      </el-form-item>
      <!-- INPUT Share qrcode pic url: -->
      <el-form-item :label="$t('labels.shareAddressShareQRcode')">
        <el-input v-model="shareQRcode" />
      </el-form-item>
      <!-- INPUT google verification: Eric said that this field is not relevant yet.  -->
      <!-- <el-form-item :label="$t('labels.shareAddressGoogleVerification')">
        <el-input v-model="googleVerification" placeholder="Eric said that this field is not relevant yet. " />
      </el-form-item> -->
      <!-- BUTTON -->
      <el-form-item>
        <el-button type="success" @click="updateAddress()">{{ $t('btn.confirm') }}</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import ShareAddress from '@/api/ShareAddress';
const resource = new ShareAddress();
export default {
  name: 'ShareAddress',
  data() {
    return {
      id: 1,
      shareAddressLandpageUrl: '',
      appDownloadQRCode: '',
      shareFbContent: '',
      shareUrl: '',
      shareQRcode: '',
      googleVerification: '',
      isLandingPageUrlChanged: false,
      isShareUrlChanged: false,
    };
  },
  beforeMount() {
    this.getAllAddress();
  },
  methods: {
    getAllAddress() {
      resource.get(this.id).then((res) => {
        this.shareAddressLandpageUrl = res.data.landPageUrl;
        this.appDownloadQRCode = res.data.shareQrCodeUrl;
        this.shareFbContent = res.data.fbContent;
        this.shareUrl = res.data.shareUrl;
        this.shareQRcode = res.data.qrCodeUrl;
        this.googleVerification = res.data.googleVerification;
        this.id = res.data.id;
      });
    },
    updateAddress() {
      if (
        this.shareAddressLandpageUrl !== '' &&
        this.appDownloadQRCode !== '' &&
        this.shareFbContent !== '' &&
        this.shareUrl !== '' &&
        this.shareQRcode !== ''
      ) {
        const data = {
          'id': this.id,
          'landPageUrl': this.shareAddressLandpageUrl,
          'qrCodeUrl': this.shareQRcode,
          'fbContent': this.shareFbContent,
          'shareUrl': this.shareUrl,
          'shareQrCodeUrl': this.appDownloadQRCode,
          'googleVerification': this.googleVerification,
        };
        this.loading = true;
        resource.put(data)
          .then(response => {
            this.verificationCode = response.data;
            this.$message({
              type: 'success',
              message: this.$t('messages.dataReceivedSuccessfully'),
              duration: 1 * 1000,
            });
            this.selectCountryCode = '';
            this.selectForGetValue = '';
          });
        this.loading = false;
      } else {
        // this.$message({
        //   type: 'error',
        //   message: this.$t('messages.emptyFields'),
        //   duration: 1 * 1000,
        // });
      }
    },
    generateQrCode(url, field, markerField) {
      // console.log(url, field, markerField);
      resource.generateQrCodeUrl(url).then(response => {
        this[field] = response.url;
        this[markerField] = false;
      });
    },
    onLandingPageUrlChange() {
      this.isLandingPageUrlChanged = this.shareAddressLandpageUrl !== '';
    },
    onShareAddressUrlChange() {
      this.isShareUrlChanged = this.shareUrl !== '';
    },
  },
};
</script>

<style lang="scss">
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
  padding-bottom: 20px;
  margin-bottom: 23px;
}
.box-right {
  padding-right: 10px;
}
.qr-gen-btn-wrp {text-align: center;}
</style>
