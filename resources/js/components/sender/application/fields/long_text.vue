<template>
    <div class="form-group">
        <label class="control-label">{{ field_data.label }}
            <span v-if="field_data.required===true" class="text-red">*</span>
        </label>
        <textarea v-if="field_data.advance===false" class="form-control" v-model="field_data.value"
            rows="10" :required="field_data.required"></textarea>
        <tiny-editor v-else-if="field_data.advance===true" v-model="field_data.value"
            :paste_image="field_data.paste_image">
        </tiny-editor>
    </div>
</template>

<script>
    export default {
        props: {
            value: {
                required: true,
                type: [Object, Array],
                default () {
                    return new LongText;
                }
            }
        },
        data() {
            return {
                field_data: new LongText,
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
