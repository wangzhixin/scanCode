import request from '@/utils/request'
export function submitSetting(data) {
  return request({
    url: '/submitSetting',
    method: 'post',
    data
  })
}
export function getSetting() {
  return request({
    url: '/getSetting',
    method: 'post'
  })
}
