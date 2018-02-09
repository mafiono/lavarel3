export default class {
  constructor (fields) {
    this.fields = fields
    fields.forEach(field => { this[field] = '' })
  }

  has (field) {
    return this[field]
  }

  get (field) {
    return this[field]
  }

  set (field, error) {
    this[field] = error
  }

  unset (field) {
    this[field] = ''
  }

  some (fields) {
    for (let field in fields) {
      if (this[fields[field]] !== '') {
        return true
      }
    }

    return false
  }

  clear () {
    for (let field in this.fields) {
      this[this.fields[field]] = ''
    }
  }
}
