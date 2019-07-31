export class Default {
    constructor() {
        this._name = '';
        this._id = '';
        this._value = '';
        this._label = '';
        this._required = true;
    }

    get name() {
        return this._name;
    }
    set name(name) {
        this._name = name;
    }
    get id() {
        return this._id;
    }
    set id(id) {
        this._id = id;
    }
    get value() {
        return this._value;
    }
    set value(value) {
        this._value = value;
    }
    get label() {
        return this._label;
    }
    set label(label) {
        this._label = label;
    }
    get required() {
        return this._required;
    }
    set required(required) {
        this._required = required;
    }
}