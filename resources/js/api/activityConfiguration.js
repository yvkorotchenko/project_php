import Resource from '@/api/resource';
import request from '@/utils/request';

export default class ActivityConfiguration extends Resource {
  constructor() {
    super('activity-configurations');
  }
  // upload image
  uploadFile(activityId, type, file) {
    const formData = new FormData();
    formData.append('image', file);
    formData.append('type', type);
    return request({
      url: this.uri + `/${activityId}/upload-image`,
      method: 'POST',
      data: formData,
      headers: {
        Accept: 'application/json',
        'Content-Type': 'multipart/form-data',
      },
    });
  }
}
