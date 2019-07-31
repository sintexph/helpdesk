<template>
    <div>
        <div class="form-group">
            <label>Set Name</label>
            <input type="text" v-model="field_data.label" class="form-control">
        </div>
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

            <button type="button" class="btn btn-default btn-xs" @click.prevent="show_add_field('DateOnly')">
                Date Only
            </button>
        </div>
        <br><br>

        <form @submit.prevent="save">
            <div class="box box-solid">
                <div class="box-header">
                    <h3 class="box-title">Add {{ field.type }}</h3>
                </div>
                <div class="box-body">
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
                    <template v-else-if="field.type==='DateOnly'">
                        <form-date-only v-model="field.data"></form-date-only>
                    </template>

                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary btn-sm">Save Field</button>
                </div>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Unique Id</th>
                        <th>Field</th>
                        <th>Label</th>
                        <th>Required</th>
                        <th>Name</th>
                        <th>Id</th>
                    </tr>
                </thead>
                <draggable tag="tbody" v-model="field_data.firstItem.fields" group="fields" @start="drag=true"
                    @end="drag=false">
                    <tr style="" v-for="(value,key) in field_data.firstItem.fields" :key="key">
                        <td v-text="key"></td>
                        <td class="fit" v-text="value.id"></td>
                        <td v-text="value.type"></td>
                        <td class="fit" v-text="value.data.label"></td>
                        <td v-text="value.data.required"></td>
                        <td class="fit" v-text="value.data.name"></td>
                        <td v-text="value.data.id"></td>
                        <td class="fit">
                            <a href="#" class="text-red" @click.prevent="remove_field(key)"><i class="fa fa-trash"
                                    aria-hidden="true"></i> Delete</a>
                            <span>&nbsp;&nbsp;</span>
                            <a href="#" class="text-yellow" @click.prevent="edit_field(key)"><i class="fa fa-pencil"
                                    aria-hidden="true"></i> Edit</a>
                        </td>
                    </tr>
                </draggable>

            </table>
        </div>
    </div>
</template>
<script>
    import formText from "./text";
    import formLongText from "./long_text";
    import formSelect from "./select";
    import formRadio from "./radio";
    import dateOnly from "./date_only";
    export default {
        components: {
            'form-text': formText,
            'form-long-text': formLongText,
            'form-select': formSelect,
            'form-radio': formRadio,
            'form-date-only': dateOnly,
        },
        props: {
            value: {
                required: true,

                type: [Object, Array],
                default () {
                    return new Set;
                }
            }
        },
        data() {
            return {
                field: new Field,
                field_data: new Set,
                edit: false,
                index_edit: null,
            }
        },
        methods: {


            show_add_field(field) {
                this.field.type = field;

            },
            edit_field(index) {
                this.index_edit = index;
                this.edit = true;
                this.field = this.field_data.firstItem.fields[this.index_edit];
            },
            save() {
                if (this.edit === true)
                    this.field_data.firstItem.fields[this.index_edit] = this.field;
                else
                    this.field_data.firstItem.fields.push(this.field);

                // Reset variables
                this.field = new Field;
                this.index_edit = null;
                this.edit = false;
            },
            remove_field(key) {
                this.field_data.firstItem.fields.splice(key, 1);
            }

        },
        watch: {
            value: {
                deep: true,
                handler(value) {
                    this.field_data = value;

                }
            },
            field_data: {
                deep: true,
                handler(field_data) {

                    this.$emit('input', field_data);


                }
            },
        },
        mounted() {
            this.$nextTick(function () {
                if (this.value instanceof Set)
                    this.field_data = this.value;
                else
                    this.field_data = new Set;
            });
        }
    }

</script>
<style>
    .modal-body {
        min-height: inherit;
    }

</style>
