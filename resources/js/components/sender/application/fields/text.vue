<template>
    <div class="form-group">
        <label class="control-label">{{ field_data.label }}
            <span v-if="field_data.required===true" class="text-red">*</span>
        </label>
        <input type="text" v-model="field_data.value" class="form-control" :required="field_data.required">
    </div>
</template>

<script>
    export default {
        props: {
            value: {
                required: true,
                type: [Object, Array],
                default () {
                    return new Text;
                }
            }
        },
        data() {
            return {
                field_data: new Text,
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
        mounted() {
            this.$nextTick(function () {
                this.field_data = this.value;
            });
        }
    }

</script>
