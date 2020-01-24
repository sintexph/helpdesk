<template>
    <div>
        <div class="form-group">
            <label class="control-label">Name</label>
            <input class="form-control  text-uppercase" v-model="account.name" required>
        </div>
        <div class="form-group">
            <label class="control-label">Id Number</label>
            <input type="text" class="form-control" v-model="account.id_number" required>
            <label class="control-label"><a href="#" @click.prevent="load_employee()">Load Data</a></label>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">Email</label>
                    <input type="email" class="form-control" v-model="account.email" required>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">Contact</label>
                    <input type="text" class="form-control" v-model="account.contact" required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">Factory</label>
                    <select class="form-control" v-model="account.factory" required>
                        <option value="">-- SELECT FACTORY --</option>
                        <option v-for="(value,key) in factories" :key="'factory-'+key" :value="value.id">
                            {{ value.text }}</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">Position</label>
                    <input type="text" class="form-control" v-model="account.position" required>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">Username</label>
            <input type="text" class="form-control" v-model="account.username" required>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">Password</label>
                    <input type="password" class="form-control" v-model="account.password" required>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">Password Confirmation</label>
                    <input type="password" class="form-control" v-model="account.password_confirmation" required>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label class="control-label">Shift Start (24 HOUR)</label>
                    <select2 :options="shift" style="width:100%" v-model="account.shift_start"></select2>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label class="control-label">Shift End (24 HOUR)</label>
                    <select2 :options="shift" style="width:100%" v-model="account.shift_end"></select2>
                </div>
            </div>
             <div class="col-sm-4">
                <div class="form-group">
                    <label class="control-label">Break Time (Hour)</label>
                    <input type="number" v-model="account.break_time" class="form-control">
                </div>
            </div>

        </div>

        <div class="form-group">
            <label class="control-label">Role</label>
            <select class="form-control" v-model="account.role" required>
                <option value="">-- SELECT ROLE --</option>

                <option :value="ROLE.SENDER">SENDER</option>
                <option :value="ROLE.SUPPORT">SUPPORT</option>
                <option :value="ROLE.MODERATOR">MODERATOR</option>
                <option :value="ROLE.ADMINISTRATOR">ADMINISTRATOR</option>

            </select>
        </div>

        <div class="form-group">
            <label class="control-label"><input type="checkbox" v-model="account.active">
                Active</label>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['value'],
        data: function () {
            return {
                account: new User,
                factories: [],
                submitted: false,
                shift: [{
                    text: 'NONE',
                    id: '',
                }],
            }
        },
        watch: {
            value: {
                deep: true,
                handler: function (val) {
                    this.account = val;
                }
            },
            account: {
                deep: true,
                handler: function (val) {
                    this.$emit('input', val);
                }
            }
        },
        methods: {
            load_employee() {
                let par = this;
                if (par.submitted == false) {
                    par.show_wait("Please wait while the system is processing your request....");
                    axios.post('/utility/suggestions/employee', {
                        id_number: par.account.id_number
                    }).then(response => {
                        par.hide_wait();
                        if (response.data) {
                            var employee = response.data[0];

                            par.account.name = employee.full_name;
                            par.account.factory = employee.factory;
                            par.account.position = employee.position;
                        }

                    });
                }

            },
            get_factories() {
                var par = this;
                axios.post('/utility/factories').then(response => {
                    response.data.forEach(element => {
                        par.factories.push({
                            id: element.name,
                            text: element.name
                        });
                    });
                });
            }
        },
        mounted() {
            this.get_factories();
            for (let i = 1; i <=24; i++) {
                console.log(i);
                this.shift.push({
                    text: i,
                    id: i,
                });
            }
        }


    }

</script>
