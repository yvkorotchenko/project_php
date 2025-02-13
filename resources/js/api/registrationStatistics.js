import request from '@/utils/request';

export default class RegistrationStatisticsResource {
  list(from, to) {
    return request({
      url: `statistics/registration?from=${from}&to=${to}`,
      method: 'get',
    });
  }

  latest() {
    return request({
      url: 'statistics/registration/latest',
      method: 'get',
    });
  }
}
