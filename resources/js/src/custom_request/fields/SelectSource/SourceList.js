export class SourceList {

    constructor(items) {
        if (items !== null && items !== undefined)
            this._items = items;
        else
            this._items = [{
                id: '',
                text: '',
            }];
    }

    get items() {
        return this._items;
    }
    set items(items) {
        this._items = items;
    }


    insert_item(text, id) {

        if (text !== undefined && id !== undefined && text !== null && id !== null) {
            this._items.push({
                text: text,
                id: id
            });
        } else {
            this._items.push({
                text: '',
                id: ''
            });
        }

    }
    remove_item(index) {

        this._items.splice(index, 1);
    }
}
