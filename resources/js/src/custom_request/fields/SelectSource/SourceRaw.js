import {
    SourceList
} from './SourceList';

export class SourceRaw extends SourceList {

    constructor(dataSource, raw) {
        super(dataSource);
        if (raw !== null && raw !== undefined)
            this._raw = raw;
        else
            this._raw = '';
    }


    get raw() {
        return this._raw;
    }
    set raw(raw) {

        // Change the items based on raw value
        this._items = [];
        raw.split("\n").forEach(element => {
            this._items.push({
                text: element,
                id: element,
            });
        });

        this._raw = raw;
    }




}
