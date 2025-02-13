import request from '@/utils/request';

export default function uploadImage(sendUrl, type, file) {
  const formData = new FormData();

  formData.append('image', file);
  formData.append('type', type);

  return request({
    url: sendUrl,
    method: 'POST',
    data: formData,
    headers: {
      Accept: 'application/json',
      'Content-Type': 'multipart/form-data',
    },
  });
}
