export default class Observable{
	constructor(value,rootNode){
		this.myValue = value;
		this.rootNode = rootNode;
	}
	get value(){
		return this.myValue;
	}
	set value(value){
		this.myValue = value;
		let event = new Event('render');
		this.rootNode.dispatchEvent(event);
	}
}
