/**
 * Created by PanJiaChen on 16/11/18.
 */

import { getToken, getTokenKey } from '@/utils/auth'
import store from '@/store'
/**
  * Parse the time to string
  * @param {(Object|string|number)} time
  * @param {string} cFormat
  * @returns {string | null}
  */
export function parseTime(time, cFormat) {
  if (arguments.length === 0 || !time) {
    return null
  }
  const format = cFormat || '{y}-{m}-{d} {h}:{i}:{s}'
  let date
  if (typeof time === 'object') {
    date = time
  } else {
    if ((typeof time === 'string')) {
      if ((/^[0-9]+$/.test(time))) {
        // support "1548221490638"
        time = parseInt(time)
      } else {
        // support safari
        // https://stackoverflow.com/questions/4310953/invalid-date-in-safari
        time = time.replace(new RegExp(/-/gm), '/')
      }
    }

    if ((typeof time === 'number') && (time.toString().length === 10)) {
      time = time * 1000
    }
    date = new Date(time)
  }
  const formatObj = {
    y: date.getFullYear(),
    m: date.getMonth() + 1,
    d: date.getDate(),
    h: date.getHours(),
    i: date.getMinutes(),
    s: date.getSeconds(),
    a: date.getDay()
  }
  const time_str = format.replace(/{([ymdhisa])+}/g, (result, key) => {
    const value = formatObj[key]
    // Note: getDay() returns 0 on Sunday
    if (key === 'a') { return ['日', '一', '二', '三', '四', '五', '六'][value ] }
    return value.toString().padStart(2, '0')
  })
  return time_str
}

/**
  * @param {number} time
  * @param {string} option
  * @returns {string}
  */
export function formatTime(time, option) {
  if (('' + time).length === 10) {
    time = parseInt(time) * 1000
  } else {
    time = +time
  }
  const d = new Date(time)
  const now = Date.now()

  const diff = (now - d) / 1000

  if (diff < 30) {
    return '刚刚'
  } else if (diff < 3600) {
    // less 1 hour
    return Math.ceil(diff / 60) + '分钟前'
  } else if (diff < 3600 * 24) {
    return Math.ceil(diff / 3600) + '小时前'
  } else if (diff < 3600 * 24 * 2) {
    return '1天前'
  }
  if (option) {
    return parseTime(time, option)
  } else {
    return (
      d.getMonth() +
       1 +
       '月' +
       d.getDate() +
       '日' +
       d.getHours() +
       '时' +
       d.getMinutes() +
       '分'
    )
  }
}

/**
  * @param {string} url
  * @returns {Object}
  */
export function param2Obj(url) {
  const search = decodeURIComponent(url.split('?')[1]).replace(/\+/g, ' ')
  if (!search) {
    return {}
  }
  const obj = {}
  const searchArr = search.split('&')
  searchArr.forEach(v => {
    const index = v.indexOf('=')
    if (index !== -1) {
      const name = v.substring(0, index)
      const val = v.substring(index + 1, v.length)
      obj[name] = val
    }
  })
  return obj
}
/**
  * @Time    :   2020/11/11 10:32:35
  * @Author  :   wangZhixin
  * @Desc    :   接口地址
  */
export function getApiUrl(apiName) {
  return process.env.VUE_APP_BASE_API + '/' + apiName + '.html'
}
/**
  * @Time    :   2020/12/07 17:00:09
  * @Author  :   wangZhixin
  * @Desc    :   上传文件时的headers
  */
export function getUploadFileHeaders() {
  var header = {}
  header[getTokenKey()] = getToken()
  return header
}
/**
  * @Time    :   2021/03/03 10:58:43
  * @Author  :   wangZhixin
  * @Desc    :   判断是否是超级管理员账户
  */
export function isAdmin() {
  var is = false
  var roles = store.getters.roles
  roles.forEach(eachRoles => {
    if (eachRoles === 'admin') {
      is = true
    }
  })
  return is
}
export function getNowFormatDate(thisDate = '') {
  var date = new Date()
  if (thisDate) {
    date = thisDate
  }
  var seperator1 = '-'
  var year = date.getFullYear()
  var month = date.getMonth() + 1
  var strDate = date.getDate()
  if (month >= 1 && month <= 9) {
    month = '0' + month
  }
  if (strDate >= 0 && strDate <= 9) {
    strDate = '0' + strDate
  }
  var currentdate = year + seperator1 + month + seperator1 + strDate
  return currentdate
}

