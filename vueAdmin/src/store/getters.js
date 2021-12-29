const getters = {
  sidebar: state => state.app.sidebar,
  device: state => state.app.device,
  token: state => state.user.token,
  avatar: state => state.user.avatar,
  name: state => state.user.name,
  roles: state => state.user.roles,
  permission_routes: state => state.permission.routes,
  storeId: state => state.user.storeId,
  planLabel: state => state.user.planLabel,
  adminUserId: state => state.user.adminUserId,
  store_chat_status: state => state.user.store_chat_status
}
export default getters
