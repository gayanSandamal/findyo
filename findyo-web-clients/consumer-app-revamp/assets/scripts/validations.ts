export const NAME = /^[A-Za-z]+$/g
export const USERNAME = /^[a-z0-9]+$/g
export const DATE = /\d{4}-\d{2}-\d{2}/
export const ADDRESS = /^[a-zA-Z0-9\s,'-/]*$/g
export const PHONE = /^(?:0|94|\+94)?(?:(11|21|23|24|25|26|27|31|32|33|34|35|36|37|38|41|45|47|51|52|54|55|57|63|65|66|67|81|912)(0|2|3|4|5|7|9)|7(0|1|2|4|5|6|7|8)\d)\d{6}$/
export const EMAIL = /^(([^<>()\\[\]\\.,;:\s@"]+(\.[^<>()\\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/

export const usernameRules = (key: string, value: string, msg: string | null) => {
  if (value) {
    return {
      valid: !!value.match(USERNAME),
      msg: !!value.match(USERNAME) || msg || `Invalid ${key}`
    }
  } else {
    return {
      valid: false,
      msg: ''
    }
  }
}

export const nameRules = (key: string, value: string, msg: string | null) => {
  if (value) {
    return {
      valid: !!value.match(NAME),
      msg: !!value.match(NAME) || msg || `Invalid ${key}`
    }
  } else {
    return {
      valid: false,
      msg: ''
    }
  }
}

export const emailRules = (key: string, value: string, msg: string | null) => {
  if (value) {
    return {
      valid: !!value.match(EMAIL),
      msg: !!value.match(EMAIL) || msg || `Invalid ${key}`
    }
  } else {
    return {
      valid: false,
      msg: ''
    }
  }
}

export const contactNumberRules = (key: string, value: string, msg: string | null) => {
  if (value) {
    return {
      valid: !!value.match(PHONE),
      msg: !!value.match(PHONE) || msg || `Invalid ${key}`
    }
  } else {
    return {
      valid: false,
      msg: ''
    }
  }
}

export const dateRules = (key: string, value: string, msg: string | null) => {
  if (value) {
    return {
      valid: !!value.match(DATE),
      msg: !!value.match(DATE) || msg || `Invalid ${key}`
    }
  } else {
    return {
      valid: false,
      msg: ''
    }
  }
}

export const addressRules = (key: string, value: string, msg: string | null) => {
  if (value) {
    return {
      valid: !!value.match(ADDRESS),
      msg: !!value.match(ADDRESS) || msg || `Invalid ${key}`
    }
  } else {
    return {
      valid: false,
      msg: ''
    }
  }
}
