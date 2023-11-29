const hasRole = (user = null, roles = '') => {
  if (user == null || roles == '' || user?.roles == undefined) {
    return false
  }

  let found = false

  let rolesToCheck = roles.split('|')

  // console.log({
  //   user_roles: user?.roles,
  //   rolesToCheck
  // })


  for (let userRole of user?.roles) {
    if (rolesToCheck?.includes(userRole?.name)) {
      found = true
    }
  }

  return found
}

export { hasRole }