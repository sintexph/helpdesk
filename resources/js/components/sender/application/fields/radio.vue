<template>
    <div class="form-group">
        <label class="control-label">{{ field_data.label }}
            <span v-if="field_data.required===true" class="text-red">*</span>
        </label>
        <br>
        <template v-for="(value,index) in field_data.list">
            <label :key="index">
                <input v-model="field_data.value" :value="value.value" type="radio" :name="radio_name">
                {{ value.name }} &nbsp;&nbsp;
            </label>
        </template>
    </div>
</template>

<script>
    export default {
        props: {
            value: {
                required: true,
                type: [Object, Array],
                default () {
                    return new Radio;
                }
            }
        },
        data() {
            return {
                field_data: new Radio,
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
        computed: {
            radio_name() {
                return (this.field_data.label).toLowerCase();
            }
        },
        mounted() {
            this.$nextTick(function () {
                this.field_data = this.value;
            });
        }
    }

</script>
