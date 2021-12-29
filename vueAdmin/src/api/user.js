import request from '@/utils/request'

export function login(data) {
  return request({
    url: '/login',
    method: 'post',
    data
  })
}

export function getInfo() {
  return request({
    url: '/getUserInfo.html',
    method: 'post'
  })
}

export function logout() {
  return request({
    url: '/logoutAdmin.html',
    method: 'post'
  })
}
export function getUserList(page = 1) {
  var data = { 'page': page }
  return request({
    url: '/getUserList.html',
    method: 'post',
    data
  })
}
