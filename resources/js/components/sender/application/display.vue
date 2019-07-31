<template>
    <div class="box box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">{{ application.name }}</h3>
        </div>
        <div class="box-body">
            <div class="form-group" v-if="application_list.length>1">
                <p>Please select at least one system.</p>
                <label v-for="(value,key) in application_list" :key="key" class="control-label">
                    &nbsp;&nbsp;<icheck-checkbox @checked="add_application(value)"
                        @unchecked="remove_application_value(value)"></icheck-checkbox>&nbsp;&nbsp;{{ value }}
                </label>
            </div>
            <template v-for="(value,key) in application.fields">
                <field-text v-model="application.fields[key].data" v-if="value.type==='Text'" :key="key"></field-text>
                <field-long-text v-model="application.fields[key].data" v-else-if="value.type==='LongText'" :key="key">
                </field-long-text>
                <field-radio v-model="application.fields[key].data" v-else-if="value.type==='Radio'" :key="key">
                </field-radio>
                <field-select v-model="application.fields[key].data" v-else-if="value.type==='Select'" :key="key">
                </field-select>
                <field-set v-model="application.fields[key].data" v-else-if="value.type==='Set'" :key="key">
                </field-set>

                <field-date-only v-model="application.fields[key].data" v-else-if="value.type==='DateOnly'" :key="key">
                </field-date-only>


            </template>
        </div>
    </div>
</template>
<script>
    import longText from './fields/long_text';
    import text from './fields/text';
    import radio from './fields/radio';
    import select from './fields/select';
    import set from './fields/set';
    import dateOnly from './fields/date_only';
    export default {
        components: {
            'field-text': text,
            'field-long-text': longText,
            'field-radio': radio,
            'field-select': select,
            'field-set': set,
            'field-date-only': dateOnly,
        },
        props: {
            value: {
                required: true,
                default () {
                    return new Application;
                }
            }
        },
        data() {
            return {
                application: new Application,
                application_list: [],
            }
        },
        methods: {
            add_application(value) {
                if (this.application.selected_applications.indexOf(value) === -1) {
                    this.application.selected_applications.push(value);
                }
            },
            remove_application_value(value) {
                this.application.selected_applications.splice(this.application.selected_applications.indexOf(value), 1);
            },
        },
        watch: {
            value: {
                deep: true,
                handler(value) {
                    this.application = value;
                }
            },
            application: {
                deep: true,
                handler(application) {
                    this.$emit('input', application);
                }
            }
        },
        mounted() {
            this.$nextTick(function () {
                if (this.value instanceof Application)
                    this.application = this.value;
                else {
                    if (this.value !== null) {
                        this.application = this.convert_application(this.value);
                    }
                }


                // Load up the application list in separate list so later on it can be manipulated based on user needs
                this.application.applications.forEach(element => {
                    this.application_list.push(element);
                });

                // instantiate new property that can be populated based on user needs
                this.application.selected_applications = [];

                // If the application is only 1 then set as default that it was selected already
                if (this.application_list.length == 1)
                    this.add_application(this.application_list[0]);
            });
        }
    }

</script>
