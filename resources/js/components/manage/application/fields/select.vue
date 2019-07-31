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
        <div class="col-sm-12">

            <div class="form-group">
                <label class="control-label"><input type="checkbox" v-model="field_data.required"> Required</label>
            </div>

            <div class="form-group">
                <label class="control-label"><input type="checkbox" v-model="field_data.advance"> Advance
                    Selection</label>
            </div>

            <div class="box box-solid">
                <div class="box-header">
                    <h3 class="box-title">Data Source</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <div class="btn-group">
                                    <button type="button" @click.prevent="set_source('list')"
                                        :class="(field_data.source==='list'?'active':'') + ' btn btn-default btn-xs '">
                                        List
                                    </button>
                                    <button type="button" @click.prevent="set_source('url')"
                                        :class="(field_data.source==='url'?'active':'') + ' btn btn-default btn-xs '">
                                        Url
                                    </button>
                                    <button type="button" @click.prevent="set_source('raw')"
                                        :class="(field_data.source==='raw'?'active':'') + ' btn btn-default btn-xs '">
                                        Raw
                                    </button>
                                </div>
                            </div>
                        </div>
                        <template v-if="field_data.source==='url'">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label">URL</label>
                                    <input type="text" v-model="field_data.dataSource.url" class="form-control"
                                        required>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">Label</label>
                                    <input type="text" v-model="field_data.dataSource.label" class="form-control"
                                        required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">Value</label>
                                    <input type="text" v-model="field_data.dataSource.value" class="form-control"
                                        required>
                                </div>
                            </div>
                        </template>
                        <template v-else-if="field_data.source==='list'">
                            <div class="col-sm-12">
                                <button class="btn btn-primary btn-xs pull-right" type="button"
                                    @click.prevent="field_data.dataSource.insert_item">Add Item</button>
                            </div>
                            <template v-for="(value,index) in field_data.dataSource.items">
                                <div class="col-sm-6" :key="'text'+index">
                                    <div class="form-group">
                                        <label class="control-label">Text</label>
                                        <input type="text" v-model="field_data.dataSource.items[index].text"
                                            class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-sm-6" :key="'id'+index">
                                    <div class="form-group">
                                        <label class="control-label">ID</label>
                                        <input type="text" v-model="field_data.dataSource.items[index].id"
                                            class="form-control" required>
                                        <a href="#" @click.prevent="field_data.dataSource.remove_item(index)">Remove</a>
                                    </div>
                                </div>
                            </template>
                        </template>
                        <template v-else-if="field_data.source==='raw'">
                            <div class="col-sm-12">
                                <label class="control-label">Raw Text</label>
                                <textarea class="form-control" v-model="field_data.dataSource.raw" rows="5"
                                    required></textarea>
                            </div>
                        </template>
                    </div>
                </div>
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
                    return new Select;
                }
            }
        },
        data() {
            return {
                field_data: new Select,
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
        methods: {
            set_source(source) {
                this.field_data.source = source;
                switch (source) {
                    case 'url':
                        this.field_data.dataSource = new SourceUrl;
                        break;
                    case 'raw':
                        this.field_data.dataSource = new SourceRaw;
                        break;
                    case 'list':
                        this.field_data.dataSource = new SourceList;
                        break;
                }
            }
        },
        mounted() {
            
            this.$nextTick(function () {
                if (this.value instanceof Select)
                    this.field_data = this.value;
                else
                    this.field_data = new Select;
            });
        }
    }

</script>
