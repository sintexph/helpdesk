<template>
    <div>
        <div v-if="next===false" class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Application forms <small>Check the right application based on your needs</small>
                </h3>
            </div>
            <div class="box-body">
                <template v-if="application_list.length!==0">
                    <div class="row">
                        <template v-for="(chunk,chunkKey) in chunkedFields">

                            <div class="col-sm-6" :key="'application-chunk-'+chunkKey">

                                <div v-for="(value,key) in chunk" :key="'application-list-'+key" class="form-group">
                                    <label class="control-label" :key="key">
                                        <icheck-checkbox v-model="value.selected"></icheck-checkbox>
                                        &nbsp;&nbsp;{{ value.data.name }}
                                    </label>
                                </div>
                            </div>


                        </template>
                    </div>
                </template>
                <template v-else>
                    <h3 class="text-gray text-center">Sorry!
                        <br>
                        <small>There are no available system to be applied.</small>
                    </h3>
                </template>
            </div>
            <div class="box-footer">

                <div class="pull-right">
                    <button type="button" class="btn btn-sm btn-default" @click.prevent="proceed_next">Next
                        Process</button>
                </div>
            </div>
        </div>
        <form @submit.prevent="submit" v-if="next===true">
            <template v-for="(value,key) in application_list">
                <application v-if="value.selected===true" :key="'application-'+key"
                    v-model="application_list[key].data"></application>
            </template>
            <button type="button" @click.prevent="next=false" class="btn btn-sm btn-danger">Back to List</button>
            <button type="submit" class="btn btn-sm btn-success">Submit Application</button>
        </form>
    </div>
</template>
<script>
    import applicationDisplay from './display';

    export default {
        components: {
            'application': applicationDisplay,
        },
        data() {
            return {
                application: new Application,
                application_list: [],
                next: false,
                submitted: false,
            }
        },
        methods: {
            field_added(field) {
                this.application.fields.push(field);
            },
            proceed_next() {

                if (this.selected_applications.length !== 0)
                    this.next = true;
                else
                    alert('Please select at least one from the list.');
            },
            submit() {
                var vm = this;
                if (vm.submitted === false) {
                    vm.submitted = true;
                    vm.show_wait("Please wait while the system is submitting your request.....");

                    axios.put('/user/application/save', {
                        applications: vm.selected_applications
                    }).then(response => {
                        vm.hide_wait();
                        vm.show_wait_success(response.data.message);

                        // Re instantiate the application list so the popup warning will not show up
                        vm.application_list = [];
                        vm.next = false;
                        setTimeout(function () {
                            location.reload();
                        }, 3000);

                    }).catch(error => {
                        vm.alert_failed(error);
                        vm.hide_wait();
                        vm.submitted = false;

                    });
                }
            },
            get_applications() {
                var vm = this;
                axios.post('/utility/applications').then(response => {

                    response.data.forEach(app => {
                        vm.application_list.push({
                            selected: false,
                            data: app,
                        })
                    });

                });
            }
        },
        computed: {
            chunkedFields() {
                var newArr = [];
                var size = 2;
                for (var i = 0; i < this.application_list.length; i += size) {
                    newArr.push(this.application_list.slice(i, i + size));
                }
                return newArr;
            },
            selected_applications() {
                return this.application_list.filter(application => application.selected === true);
            }
        },
        mounted() {

            this.get_applications();
            this.$nextTick(function () {
                var vm = this;
                window.onbeforeunload = function () {
                    // Check if there are applications selected before refresh page
                    if (vm.selected_applications.length !== 0)
                        return "Data will be lost if you leave the page, are you sure?";
                    else
                        return null;
                };
            });
        }
    }

</script>
