export default class Observable {
  constructor (value, targetNode) {
    this.myValue = value
    this.targetNode = targetNode
  }

  get value () {
    return this.myValue
  }

  set value (value) {
    this.myValue = value
    const event = new Event('render')
    this.targetNode.dispatchEvent(event)
  }
}
