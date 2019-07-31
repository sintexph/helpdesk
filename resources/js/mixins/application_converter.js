import {
    SourceList
} from "../src/custom_request/fields/SelectSource/SourceList";
import {
    SourceRaw
} from "../src/custom_request/fields/SelectSource/SourceRaw";

export default {
    methods: {
        convert_application(data) {
            var application = new Application(
                data.applications,
                null,
                data.format,
                data.field_sender_id_number,
                data.field_sender_name,
                data.field_sender_email,
                data.field_sender_factory,
                data.field_sender_phone
            );

            data.fields.forEach(field => {
                application.fields.push(this.convert_field(field));
            });

            return application;
        },

        convert_field(data) {
            var field = new Field;
            field.type = data.type;
            field.id = data.id;
            switch (field.type) {
                case 'Text':
                    field.data = this.convert_text(data.data);
                    break;

                case 'LongText':
                    field.data = this.convert_long_text(data.data);
                    break;

                case 'Radio':
                    field.data = this.convert_radio(data.data);
                    break;

                case 'Select':
                    field.data = this.convert_select(data.data);

                    break;

                case 'Set':
                    field.data = this.convert_set(data.data);
                    break;

                case 'DateOnly':
                    field.data = this.convert_date_only(data.data);
                    break;
            }

            return field;
        },
        convert_default(default_data, data) {

            default_data.name = data.name;
            default_data.id = data.id;

            if (data.value !== null && data.value !== undefined)
                default_data.value = data.value;

            default_data.label = data.label;
            default_data.required = data.required;

            return default_data;
        },
        convert_text(data) {
            var obj = new Text;
            return this.convert_default(obj, data);
        },
        convert_date_only(data) {
            var obj = new DateOnly;
            return this.convert_default(obj, data);
        },
        convert_long_text(data) {
            var obj = new LongText;
            obj.paste_image = data.paste_image;
            obj.advance = data.advance;
            return this.convert_default(obj, data);
        },
        convert_radio(data) {
            var obj = new Radio;
            obj.list = data.list;

            return this.convert_default(obj, data);
        },
        convert_select(data) {
            var obj = new Select;

            obj.source = data.source;
            obj.advance = data.advance;

            switch (obj.source) {
                case 'url':
                    obj.dataSource = this.convert_select_source_url(data.dataSource);
                    break;
                case 'raw':
                    obj.dataSource = this.convert_select_source_raw(data.dataSource);
                    break;
                case 'list':
                    obj.dataSource = this.convert_select_source_list(data.dataSource);
                    break;
            }




            return this.convert_default(obj, data);
        },

        convert_set(data) {
            var obj = new Set;
            obj.items = [];

            data.items.forEach(li => {
                obj.items.push(this.convert_set_field(li));
            });

            return this.convert_default(obj, data);
        },
        convert_select_source_list(data) {
            var obj = new SourceList;
            obj.items = data.items;
            return obj;
        },
        convert_select_source_url(data) {
            var obj = new SourceUrl;
            obj.items = data.items;
            obj.url = data.url;
            obj.label = data.label;
            obj.value = data.value;

            return obj;
        },
        convert_select_source_raw(data) {
            var obj = new SourceRaw;
            obj.items = data.items;
            obj.raw = data.raw;
            return obj;
        },

        convert_set_field(data) {
            var obj = new SetField;
            data.fields.forEach(field => {
                obj.fields.push(this.convert_field(field));
            });
            return obj;
        },



    }
}
