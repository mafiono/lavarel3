import Errors from './Errors'
import validations from './validations'

export default class {
  constructor (data, rules, extraValidations = {}) {
    this.defaultData = {}

    if (data) {
      Object.assign(this.defaultData, data)

      Object.keys(data).forEach(key => { this[key] = data[key] })
    }

    this.errors = new Errors(Object.keys(this.defaultData))

    this.validations = validations(this)

    if (extraValidations) {
      Object.assign(this.validations, extraValidations)
    }

    this.rules = rules || {}
  }

  reset () {
    Object.keys(this.defaultData).forEach(key => { this[key] = this.defaultData[key] })
  }

  validate (field) {
    if (!this.rules[field]) {
      return
    }

    let currentPromise = new Promise((resolve) => resolve());

    this.rules[field].forEach((rule) => {
      let validationName = Object.keys(rule)[0]

      let validation = this.validations[validationName]

      currentPromise = currentPromise.then(() => validation(field, rule[validationName]))
    })

    currentPromise.then(() => {this.errors[field] = ''})
  }


  data () {
    let data = {}

    Object.keys(this.defaultData).forEach(key => { data[key] = this[key] })

    return data
  }

  validateSome (fields) {
    fields.forEach(field => { this.validate(field) })
  }

  submit (requestType, url) {
    return $[requestType](url, this.data())
  }
}
