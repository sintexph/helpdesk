export class SetField {
    constructor() { 
        this._fields = [];
    }
    get fields() {
        return this._fields;
    }
    set fields(fields) {
        this._fields = fields;
    }
}
