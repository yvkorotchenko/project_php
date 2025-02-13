import request from '@/utils/request';
import Resource from '@/api/resource';

export default class VerificationCodeQuery extends Resource {
  constructor() {
    super('verification-code-query');
  }

  post(data) {
    return request({
      url: 'verification-code-query',
      method: 'post',
      data: data,
    });
  }
}
