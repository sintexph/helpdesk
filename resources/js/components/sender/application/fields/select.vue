<template>
    <div class="form-group">
        <label class="control-label">{{ field_data.label }}
            <span v-if="field_data.required===true" class="text-red">*</span>
        </label>
        <select v-if="field_data.advance===false" class="form-control" v-model="field_data.value"
            :required="field_data.required">
            <option value="">-- SELECT --</option>
            <option v-for="(value,key) in selection" :key="key" :value="value.id">{{ value.text }}</option>
        </select>
        <select3 style="width:100%;" v-else v-model="field_data.value" :options="selection"></select3>
    </div>
</template>
<script>
    export default {
        props: {
            value: {
                required: true,
                type: [Object, Array],
                default () {
                    return new Select;
                }
            }
        },
        data() {
            return {
                field_data: new Select,
                selection: [],
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

                switch (this.field_data.source) {
                    case 'list':
                        this.selection = this.field_data.dataSource.items;
                        break;
                    case 'url':
                        this.load_data();
                        break;
                    case 'raw':
                        this.selection = this.field_data.dataSource.items;
                        break;
                }
            });
        },
        methods: {
            load_data() {
                let vm = this;
                axios.post(vm.field_data.dataSource.url).then(response => {
                    response.data.forEach(function (data) {
                        vm.selection.push({
                            text: data[vm.field_data.dataSource.label],
                            id: data[vm.field_data.dataSource.value],
                        });
                    });
                });
            }
        }
    }

</script>
