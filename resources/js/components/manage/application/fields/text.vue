<template>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label class="control-label">Label</label>
                <input type="text" v-model="field_data.label" class="form-control" required>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label class="control-label">Name</label>
                <input type="text" v-model="field_data.name" class="form-control">
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label class="control-label">ID</label>
                <input type="text" v-model="field_data.id" class="form-control">
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label class="control-label"><input type="checkbox" v-model="field_data.required"> Required</label>
            </div>
        </div>
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
                if (this.value instanceof Text)
                    this.field_data = this.value;
                else
                    this.field_data = new Text;
            });

        }
    }

</script>
