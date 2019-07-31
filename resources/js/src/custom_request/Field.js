export class Field {
    constructor(data, type) {
        if (data !== undefined && data !== null)
            this._data = data;
        else {
            // Set the data by default into text
            this._data = new Text;
        }

        if (type !== undefined && type !== null)
            this._type = type;
        else
            this._type = 'Text';


        this._id = null;
    }

    get id() {
        this._id = this.generate_unique_id();
        return this._id;
    }

    set id(id) {
        this._id = id;
    }

    get data() {
        return this._data;
    }
    set data(data) {
        this._data = data;
    }
    get type() {
        return this._type;
    }
    set type(type) {
        this._type = type;
    }

    generate_unique_id() {
        var temp = this.data.label.toLowerCase().replace(/ /g, "_");
        temp = temp.replace(/[^\w\s]/gi, '_');
        return temp;
    }
}
