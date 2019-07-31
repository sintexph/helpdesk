import {
    SourceList
} from './SourceList';


export class SourceUrl extends SourceList {

    constructor(dataSource, url, label, value) {
        super(dataSource);

        if (url !== null && url !== undefined)
            this._url = url;
        else
            this._url = '';

        if (label !== null && label !== undefined)
            this._label = label;
        else
            this._label = '';
        if (value !== null && value !== undefined)
            this._value = value;
        else
            this._value = '';
    }


    get url() {
        return this._url;
    }
    set url(url) {
        this._url = url;
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


}
