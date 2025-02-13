import request from '@/utils/request';
import Resource from '@/api/resource';

export default class ShareAddress extends Resource {
  constructor() {
    super('share-address');
  }

  put(data) {
    return request({
      url: 'share-address',
      method: 'put',
      data: data,
    });
  }

  generateQrCodeUrl(url) {
    return request({
      url: 'share-address/generate-qr-code',
      method: 'post',
      data: { url: url },
    });
  }
}
