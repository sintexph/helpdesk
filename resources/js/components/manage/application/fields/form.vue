<template>
    <div>
        <div class="btn-group">
            <button type="button" class="btn btn-default btn-xs" @click.prevent="show_add_field('Text')">
                Text
            </button>
            <button type="button" class="btn btn-default btn-xs" @click.prevent="show_add_field('LongText')">
                LongText
            </button>
            <button type="button" class="btn btn-default btn-xs" @click.prevent="show_add_field('Radio')">
                Radio
            </button>
            <button type="button" class="btn btn-default btn-xs" @click.prevent="show_add_field('Select')">
                Selection
            </button>
            <button type="button" class="btn btn-default btn-xs" @click.prevent="show_add_field('Set')">
                Set
            </button>

            <button type="button" class="btn btn-default btn-xs" @click.prevent="show_add_field('DateOnly')">
                Date Only
            </button>
        </div>
        <modal :prevent="true" :extended_width="true" @hidden="reset" name="add-item" ref="addItem">
            <template slot="header">
                Add {{ field.type }}
            </template>
            <template slot="body">

                <template v-if="field.type==='Text'">
                    <form-text v-model="field.data"></form-text>
                </template>
                <template v-else-if="field.type==='LongText'">
                    <form-long-text v-model="field.data"></form-long-text>
                </template>

                <template v-else-if="field.type==='Select'">
                    <form-select v-model="field.data"></form-select>
                </template>
                <template v-else-if="field.type==='Radio'">
                    <form-radio v-model="field.data"></form-radio>
                </template>
                <template v-else-if="field.type==='Set'">
                    <form-set v-model="field.data"></form-set>
                </template>

                <template v-else-if="field.type==='DateOnly'">
                    <form-date-only v-model="field.data"></form-date-only>
                </template>

            </template>

            <template slot="footer">
                <button type="button" class="btn btn-primary btn-sm" @click.prevent="save">Save Field</button>
            </template>
        </modal>
    </div>
</template>
<script>
    import formText from "./text";
    import formLongText from "./long_text";
    import formSelect from "./select";
    import formRadio from "./radio";
    import formSet from "./set";
    import formDateOnly from "./date_only";
    export default {
        components: {
            'form-text': formText,
            'form-long-text': formLongText,
            'form-select': formSelect,
            'form-radio': formRadio,
            'form-set': formSet,
            'form-date-only': formDateOnly,
        },
        data() {
            return {
                id: null,
                field: new Field,
            }
        },

        methods: {
            show_add_field(field) {
                this.reset();
                this.field.type = field;
                this.$refs.addItem.show();
            },
            reset() {
                this.field = new Field;
            },
            save() {
                if (this.id !== null) {
                    this.$emit('updated', this.id, this.field);
                } else {
                    this.$emit('created', this.field);
                }


                this.id = null;
                this.field = new Field;
                this.$refs.addItem.dismiss();

            },
            edit(id, field) {
                this.id = id;
                // Remove reactivity of the field to prevent unwilling updates
                this.field = this.convert_field(JSON.parse(JSON.stringify(field).replace(/"_/g, "\"")));
                this.$refs.addItem.show();
            }
        }
    }

</script>
