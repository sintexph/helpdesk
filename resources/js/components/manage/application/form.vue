<template>
    <div class="row">
        <div class="col-sm-12">

            <div class="form-group">
                <label class="control-label">Unique Identifier</label>
                <input type="text" v-model="application.name" class="form-control" readonly>
            </div>
            <div v-for="(value,key) in application.applications" :key="key" class="form-group">
                <label class="control-label">Application Name</label>
                <div class="input-group">
                    <input type="text" v-model="application.applications[key]" class="form-control">
                    <span class="input-group-addon"><a href="#" class="text-red"
                            @click.prevent="application.remove_application(key)"><i class="fa fa-trash"></i></a></span>
                </div>
            </div>
            <div class="form-group">
                <a href="#" @click.prevent="application.add_application()" class="pull-left">Additional
                    Name</a>
                <a href="#" @click.prevent="view_raw===true?view_raw=false:view_raw=true" class="pull-right">Change Raw
                    Data</a>
                <div class="clearfix"></div>
            </div>
        </div>
        <template v-if="view_raw===true">
            <div class="col-sm-12">
                <div class="box box-solid">
                    <div class="box-header">
                        <h3 class="box-title">RAW Data</h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <textarea v-model="raw_data" class="form-control" paste_image="10"></textarea>
                            <span class="alert-custom alert-custom-warning">Be careful in changin the raw data you may
                                ruin the
                                application form structure.</span>
                        </div>
                        <div class="pull-right">
                            <button class="btn btn-warning btn-sm" @click.prevent="view_raw=false"
                                type="button">Cancel</button>
                            <button class="btn btn-primary btn-sm" @click.prevent="update_raw"
                                type="button">Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
        <div class="col-sm-6">

            <div class="box box-solid">
                <div class="box-header">
                    <h3 class="box-title">For Ticket Details</h3>
                </div>
                <div class="box-body">

                    <div class="form-group">
                        <label class="control-label">Sender ID Number</label>
                        <select v-model="application.field_sender_id_number" class="form-control" required>
                            <option value="">-- SELECT --</option>
                            <option v-for="(value,key) in application.fields" :key="key" :value="value.id">
                                {{ value.data.label }}
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="control-label">Sender Name</label>
                        <select v-model="application.field_sender_name" class="form-control" required>
                            <option value="">-- SELECT --</option>
                            <option v-for="(value,key) in application.fields" :key="key" :value="value.id">
                                {{ value.data.label }}
                            </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Sender Email</label>
                        <select v-model="application.field_sender_email" class="form-control" required>
                            <option value="">-- SELECT --</option>
                            <option v-for="(value,key) in application.fields" :key="key" :value="value.id">
                                {{ value.data.label }}
                            </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Sender Factory</label>
                        <select v-model="application.field_sender_factory" class="form-control" required>
                            <option value="">-- SELECT --</option>
                            <option v-for="(value,key) in application.fields" :key="key" :value="value.id">
                                {{ value.data.label }}
                            </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Sender Phone/Extension</label>
                        <select v-model="application.field_sender_phone" class="form-control" required>
                            <option value="">-- SELECT --</option>
                            <option v-for="(value,key) in application.fields" :key="key" :value="value.id">
                                {{ value.data.label }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">

            <div class="box box-solid">
                <div class="box-body">
                    <field-form ref="fieldForm" @created="field_created" @updated="field_updated"></field-form>
                </div>
                <div class="box-body">


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

                            <draggable tag="tbody" v-model="application.fields" group="fields" @start="drag=true"
                                @end="drag=false">
                                <tr style="" v-for="(value,key) in application.fields" :key="key">
                                    <td v-text="key"></td>
                                    <td class="fit" v-text="value.id"></td>
                                    <td v-text="value.type"></td>
                                    <td class="fit" v-text="value.data.label"></td>
                                    <td v-text="value.data.required"></td>
                                    <td class="fit" v-text="value.data.name"></td>
                                    <td v-text="value.data.id"></td>
                                    <td class="fit">
                                        <a href="#" class="text-red" @click.prevent="remove_field(key)"><i
                                                class="fa fa-trash" aria-hidden="true"></i> Delete</a>
                                        <span>&nbsp;&nbsp;</span>
                                        <a href="#" class="text-yellow" @click.prevent="edit_field(key)"><i
                                                class="fa fa-pencil" aria-hidden="true"></i> Edit</a>
                                    </td>
                                </tr>
                            </draggable>

                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>
<script>
    import fieldForm from "./fields/form";
    export default {
        components: {
            'field-form': fieldForm,
        },
        props: {
            value: {
                type: [Object, Array],
                default () {
                    return new Application;
                }
            }
        },
        data() {
            return {
                application: new Application,
                view_raw: false,
                raw_data: '',
            }
        },
        methods: {
            update_raw() {
                if (confirm("Are you sure you want to update the raw data?") === true) {
                    this.application = this.convert_application(JSON.parse(this.raw_data));
                    this.$forceUpdate();
                    this.view_raw = false;
                }
            },
            field_created(field) {
                this.application.fields.push(field);
            },
            field_updated(id, field) {
                this.application.fields[id] = field;
                // Force update the fields since from editing, the reactivity was removed
                this.$forceUpdate();
            },
            remove_field(index) {
                if (confirm("Do you want to remove the field?") === true) {
                    this.application.remove_field(index);
                }
            },
            edit_field(index) {
                this.$refs.fieldForm.edit(index, this.application.fields[index]);
            }
        },
        watch: {
            application: {
                deep: true,
                handler(application) {
                    this.$emit('input', application);
                    var raw = JSON.stringify(this.application);
                    this.raw_data = raw.replace(/"_/g, "\"");
                }
            },
            value: {
                deep: true,
                handler(value) {
                    this.application = value;
                }
            }
        },
        mounted() {
            this.$nextTick(function () {
                if (this.value instanceof Application)
                    this.application = this.value;
                else
                    this.application = new Application;
            });
        }

    }

</script>
