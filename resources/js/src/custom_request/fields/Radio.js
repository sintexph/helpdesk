import {
    Default
}
from "./Default";

export class Radio extends Default {
    constructor(list) {
        super();
        if (list !== undefined && list !== null)
            this._list = list;
        else
            this._list = [{
                name: '',
                value: '',
            }];
    }

    get list() {
        return this._list;
    }
    set list(list) {
        this._list = list;
    }

    insert_item(name, value) {

        if (name !== null && name !== undefined && value !== null && value !== undefined) {
            this._list.push({
                name: name,
                value: value,
            });
        } else {
            this._list.push({
                name: '',
                value: '',
            });
        }

    }
    remove_item(index)
    {
        this._list.splice(index,1);
    }
}
