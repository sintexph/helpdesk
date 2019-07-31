<template>
    <div class="box box-solid">
        <div class="box-header">
            <h3 class="box-title">
                {{ field_data.label }}
                <span v-if="field_data.required===true" class="text-red">*</span>
            </h3>
            <div class="pull-right">
                <a class="btn btn-xs btn-default" href="#" @click.prevent="add_item" role="button">Add Item</a>
            </div>
        </div>
        <div class="box-body">
            <table>
                <tbody>
                    <tr v-for="(value,item_index) in field_data.items" :key="'item'+item_index">

                        <td style="padding:5px;" v-for="(value,key) in field_data.items[item_index].fields" :key="key">
                            <field-text v-model="field_data.items[item_index].fields[key].data"
                                v-if="value.type==='Text'">
                            </field-text>
                            <field-long-text v-model="field_data.items[item_index].fields[key].data"
                                v-else-if="value.type==='LongText'">
                            </field-long-text>
                            <field-radio v-model="field_data.items[item_index].fields[key].data"
                                v-else-if="value.type==='Radio'">
                            </field-radio>
                            <field-select v-model="field_data.items[item_index].fields[key].data"
                                v-else-if="value.type==='Select'">
                            </field-select>
                            <field-set v-model="field_data.items[item_index].fields[key].data"
                                v-else-if="value.type==='Set'">
                            </field-set>

                            <field-date-only v-model="application.fields[key].data" v-else-if="value.type==='DateOnly'"
                                :key="key">
                            </field-date-only>


                        </td>
                        <td style="padding:5px;">
                            <a class="pull-right text-red" href="#" @click.prevent="remote_item(item_index)"
                                role="button"><i class="fa fa-trash" aria-hidden="true"></i></a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</template>
<script>
    import longText from './long_text';
    import text from './text';
    import radio from './radio';
    import select from './select';
    import dateOnly from './date_only';
    export default {
        components: {
            'field-text': text,
            'field-long-text': longText,
            'field-radio': radio,
            'field-select': select,
            'field-date-only': dateOnly,

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
                field_data: new Set,
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
            }
        },
        methods: {
            add_item() {
                var crop = this.convert_set_field(this.field_data.items[this.field_data.items.length - 1]);
                this.field_data.addSetField(crop);
            },
            remote_item(index) {
                if (this.field_data.items.length == 1) {
                    alert('You cannot the delete the last item');
                    return;
                }
                if (confirm("Are you sure you want to delete the item?") === true) {
                    this.field_data.items.splice(index, 1);
                }

            }
        },
        mounted() {
            this.$nextTick(function () {
                this.field_data = this.value;
            });
        }
    }

</script>
