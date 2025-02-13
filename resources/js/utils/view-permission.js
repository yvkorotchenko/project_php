export function getPermissionRouteView(permissions) {
  return {
    permissionRouteView: function(name) {
      return permissions[name].permissions.route.view;
    },
  };
}

export function getPermissionControlViewElement(permissions, view) {
  return {
    permissionControlView(name) {
      return permissions[view].permissions.controls[name];
    },
  };
}
