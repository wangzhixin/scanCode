import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

/* Layout */
import Layout from '@/layout'

/**
 * Note: sub-menu only appear when route children.length >= 1
 * Detail see: https://panjiachen.github.io/vue-element-admin-site/guide/essentials/router-and-nav.html
 *
 * hidden: true                   if set true, item will not show in the sidebar(default is false)
 * alwaysShow: true               if set true, will always show the root menu
 *                                if not set alwaysShow, when item has more than one children route,
 *                                it will becomes nested mode, otherwise not show the root menu
 * redirect: noRedirect           if set noRedirect will no redirect in the breadcrumb
 * name:'router-name'             the name is used by <keep-alive> (must set!!!)
 * meta : {
    roles: ['admin','editor']    control the page roles (you can set multiple roles)
    title: 'title'               the name show in sidebar and breadcrumb (recommend set)
    icon: 'svg-name'/'el-icon-x' the icon show in the sidebar
    breadcrumb: false            if set false, the item will hidden in breadcrumb(default is true)
    activeMenu: '/example/list'  if set path, the sidebar will highlight the path you set
  }
 */

/**
 * constantRoutes
 * a base page that does not have permission requirements
 * all roles can be accessed
 */
export const constantRoutes = [
  {
    path: '/login',
    component: () => import('@/views/login/index'),
    hidden: true
  },

  {
    path: '/404',
    component: () => import('@/views/404'),
    hidden: true
  },

  {
    path: '/',
    component: Layout,
    redirect: '/setting',
    children: [{
      path: 'setting',
      name: 'setting',
      component: () => import('@/views/setting/index'),
      meta: { title: '基础设置', icon: 'setting' }
    }]
  },
  {
    path: '/problem',
    component: Layout,
    redirect: '/problem',
    children: [{
      path: 'problem',
      name: 'problem',
      component: () => import('@/views/problem/index'),
      meta: { title: '筛查问题', icon: 'problem' }
    }]
  },
  {
    path: '/user',
    component: Layout,
    redirect: '/user',
    children: [{
      path: 'user',
      name: 'user',
      component: () => import('@/views/user/index'),
      meta: { title: '用户管理', icon: 'user' }
    }]
  },
  {
    path: '/hospital',
    component: Layout,
    redirect: '/hospital',
    children: [{
      path: 'hospital',
      name: 'hospital',
      component: () => import('@/views/hospital/index'),
      meta: { title: '医院管理', icon: 'hospital' }
    }]
  },
  {
    path: '/department',
    component: Layout,
    redirect: '/department/department',
    hidden: true,
    children: [{
      path: 'department',
      name: 'department',
      component: () => import('@/views/hospital/department'),
      meta: { title: '科室管理', activeMenu: '/hospital/hospital' }
    }]
  },
  {
    path: '/userData',
    component: Layout,
    redirect: '/userData',
    children: [{
      path: 'userData',
      name: 'userData',
      component: () => import('@/views/userData/index'),
      meta: { title: '填报信息', icon: 'userData' }
    }]
  },
  {
    path: '/adminUser',
    component: Layout,
    redirect: '/adminUser',
    children: [{
      path: 'adminUser',
      name: 'adminUser',
      component: () => import('@/views/adminUser/index'),
      meta: { title: '管理员配置', icon: 'adminuser' }
    }]
  },

  // 404 page must be placed at the end !!!
  { path: '*', redirect: '/404', hidden: true }
]

const createRouter = () => new Router({
  // mode: 'history', // require service support
  scrollBehavior: () => ({ y: 0 }),
  routes: constantRoutes
})

const router = createRouter()

// Detail see: https://github.com/vuejs/vue-router/issues/1234#issuecomment-357941465
export function resetRouter() {
  const newRouter = createRouter()
  router.matcher = newRouter.matcher // reset router
}

export default router
