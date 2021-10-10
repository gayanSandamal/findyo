import { IUser } from '~/interfaces/user'

export const shortNameGenerator = (user: IUser) => {
  if (user.firstname && user.lastname) {
    return `${user.firstname.charAt(0)}${user.lastname.charAt(0)}`
  } else if (user.firstname) {
    return user.firstname.charAt(0)
  } else {
    return ':)'
  }
}

export const fullNameGenerator = (user: IUser) => {
  if (user.firstname && user.lastname) {
    return `${user.firstname} ${user.lastname}`
  } else if (user.firstname) {
    return user.firstname
  } else {
    return 'New user'
  }
}
