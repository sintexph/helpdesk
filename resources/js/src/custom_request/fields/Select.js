import {
    Default
}
from "./Default";


export class Select extends Default {
    constructor(dataSource, source, advance) {
        super();

        if (source !== null && source !== undefined)
            this._source = source;
        else
            this._source = 'list';

        if (dataSource !== null && dataSource !== undefined)
            this._dataSource = dataSource;
        else
            this._dataSource = new SourceList;

        if (advance !== null && advance !== undefined)
            this._advance = advance;
        else
            this._advance = false;


    }

    get advance() {
        return this._advance;
    }
    set advance(advance) {
        this._advance = advance;
    }


    get source() {
        return this._source;
    }
    set source(source) {
        this._source = source;
    }


    get dataSource() {
        return this._dataSource;
    }
    set dataSource(dataSource) {
        this._dataSource = dataSource;
    }

}
