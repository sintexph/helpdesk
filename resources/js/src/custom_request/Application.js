export class Application {
    constructor(
        applications,
        fields,
        format,
        field_sender_id_number,
        field_sender_name,
        field_sender_email,
        field_sender_factory,
        field_sender_phone
    ) {

        if (applications !== null && applications !== undefined)
            this._applications = applications;
        else
            this._applications = [''];


        if (fields !== null && fields !== undefined)
            this._fields = fields;
        else
            this._fields = [];

        if (format !== null && format !== undefined)
            this._format = format;
        else
            this._format = '';

        if (field_sender_id_number !== null && field_sender_id_number !== undefined)
            this._field_sender_id_number = field_sender_id_number;
        else
            this._field_sender_id_number = '';

        if (field_sender_name !== null && field_sender_name !== undefined)
            this._field_sender_name = field_sender_name;
        else
            this._field_sender_name = '';

        if (field_sender_email !== null && field_sender_email !== undefined)
            this._field_sender_email = field_sender_email;
        else
            this._field_sender_email = '';

        if (field_sender_factory !== null && field_sender_factory !== undefined)
            this._field_sender_factory = field_sender_factory;
        else
            this._field_sender_factory = '';

        if (field_sender_phone !== null && field_sender_phone !== undefined)
            this._field_sender_phone = field_sender_phone;
        else
            this._field_sender_phone = '';

    }


    get field_sender_id_number() {
        return this._field_sender_id_number;
    }
    set field_sender_id_number(field_sender_id_number) {
        this._field_sender_id_number = field_sender_id_number;
    }


    get field_sender_name() {
        return this._field_sender_name;
    }
    set field_sender_name(field_sender_name) {
        this._field_sender_name = field_sender_name;
    }

    get field_sender_email() {
        return this._field_sender_email;
    }
    set field_sender_email(field_sender_email) {
        this._field_sender_email = field_sender_email;
    }

    get field_sender_factory() {
        return this._field_sender_factory;
    }
    set field_sender_factory(field_sender_factory) {
        this._field_sender_factory = field_sender_factory;
    }

    get field_sender_phone() {
        return this._field_sender_phone;
    }
    set field_sender_phone(field_sender_phone) {
        this._field_sender_phone = field_sender_phone;
    }


    get fields() {
        return this._fields;
    }
    set fields(fields) {
        this._fields = fields;
    }
    get name() {
        var temp_name = '';
        var count = this._applications.length;
        if (count > 2) {
            this._applications.forEach(function (app, key) {
                if (key !== count - 1)
                    temp_name += app + ", ";
            });
            temp_name = temp_name.replace(/,\s*$/, "");
            temp_name += " and " + this._applications[count - 1];
        } else if (count == 2) {
            temp_name = this._applications[0] +
                " and " + this._applications[count - 1];
        } else
            temp_name = this._applications[0];

        this._name = temp_name;
        return this._name;
    }


    get applications() {
        return this._applications;
    }
    set applications(applications) {
        this._applications = applications;
    }

    get format() {
        return this._format;
    }
    set format(format) {
        this._format = format;
    }


    remove_field(index) {
        this._fields.splice(index, 1);
    }


    add_application(app_name) {
        if (app_name !== undefined && app_name !== null) {
            this._applications.push(app_name);
        } else
            this._applications.push('');
    }
    remove_application(index) {
        if (this._applications.length === 1) {
            alert('You must retain at least 1 name.');
            return;
        }
        this._applications.splice(index, 1);
    }

    remove_application_value(value) {
        if (this._applications.length === 1) {
            alert('You must retain at least 1 name.');
            return;
        }

        this._applications.splice(this._applications.indexOf(value), 1);
    }

}
