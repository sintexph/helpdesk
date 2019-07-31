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
        <div class="col-sm-12">
            <button class="pull-right btn btn-xs btn-default" @click.prevent="field_data.insert_item">Add Item</button>
        </div>
        <template v-for="(value,index) in field_data.list">
            <div class="col-sm-6" :key="'label'+index">
                <div class="form-group">
                    <label class="control-label">Label</label>
                    <input type="text" v-model="field_data.list[index].name" class="form-control">
                </div>
            </div>
            <div class="col-sm-6" :key="'value'+index">
                <div class="form-group">
                    <label class="control-label">Value</label>
                    <input type="text" v-model="field_data.list[index].value" class="form-control">
                    <a href="#" @click.prevent="field_data.remove_item(index)">Remove</a>
                </div>
            </div>
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
        mounted() {
            this.$nextTick(function () {
                if (this.value instanceof Radio)
                    this.field_data = this.value;
                else
                    this.field_data = new Radio;
            });
        }
    }

</script>
