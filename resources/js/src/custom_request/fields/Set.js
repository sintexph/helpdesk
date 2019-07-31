import {
    Default
}
from "./Default";


export class Set extends Default {
    constructor() {
        super();
        this._items = [new SetField];
    }
    get items() {
        return this._items;
    }
    set items(items) {
        this._items = items;
    }

    get firstItem() {
        return this._items[0];
    }
    addSetField(setField) {
        return this._items.push(setField);
    }

}
