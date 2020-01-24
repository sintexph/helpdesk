<template>
    <div class="row">
        <div class="col-md-3">
            <div class="box box-solid">
                <div class=" box-header with-border">
                    <h3 class="box-title">Logged In As</h3>
                </div>
                <div class="box-body">

                    <div class="caterer">
                        <div class="sintex-circle-image">
                            <a class="venobox" :href="auth_user.photo">
                                <img :src="auth_user.photo" alt="User Image" />
                            </a>
                        </div>
                        <span class="caterer-name">{{ auth_user.name }}</span>
                        <span class="caterer-position"><a :title="auth_user.email"
                                :href="'mailto:'+auth_user.email">{{ auth_user.email }}</a></span>
                    </div>
                </div>
                <div class="box-body no-padding">

                    <ul class="nav nav-pills nav-stacked tab-default">

                        <router-link tag="li" to="/" exact>
                            <a><i class="fa fa-ticket"></i> Tickets</a>
                        </router-link>
                        <router-link tag="li" to="/create" exact>
                            <a><i class="fa fa-plus"></i> Create Ticket</a>
                        </router-link>
                        <router-link tag="li" to="/application" exact>
                            <a><i class="fa fa-paper-plane-o"></i> Request Application</a>
                        </router-link>

                    </ul>
                </div>
            </div>

            <template v-if="$route.path==='/'">
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">State</h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label class="control-label">
                                <input type="checkbox" autocomplete="off" v-model="state.PENDING"> PENDING
                            </label><br>
                            <label class="control-label">
                                <input type="checkbox" autocomplete="off" v-model="state.CATERED"> CATERED
                            </label><br>
                            <label class="control-label">
                                <input type="checkbox" autocomplete="off" v-model="state.PROCESSING"> PROCESSING
                            </label><br>
                            <label class="control-label">
                                <input type="checkbox" autocomplete="off" v-model="state.SOLVED"> SOLVED
                            </label><br>
                            <label class="control-label">
                                <input type="checkbox" autocomplete="off" v-model="state.CLOSED"> CLOSED
                            </label><br>
                            <label class="control-label">
                                <input type="checkbox" autocomplete="off" v-model="state.HOLD"> HOLD
                            </label><br>
                            <label class="control-label">
                                <input type="checkbox" autocomplete="off" v-model="state.CANCELLED"> CANCELLED
                            </label>
                        </div>
                    </div>
                </div>


                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Urgency</h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label class="control-label">
                                <input type="checkbox" v-model="urgency.LOW" autocomplete="off"> LOW
                            </label><br>
                            <label class="control-label">
                                <input type="checkbox" v-model="urgency.HIGH" autocomplete="off"> HIGH
                            </label><br>
                            <label class="control-label">
                                <input type="checkbox" v-model="urgency.NORMAL" autocomplete="off"> NORMAL
                            </label>
                        </div>
                    </div>
                </div>

            </template>


        </div>
        <div class="col-md-9">
            <router-view :filter_urgency="urgency" :filter_state="state"></router-view>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            ticket_count: {
                required: true,
                default () {
                    return 0;
                }
            },
            auth_user: {
                required: true,
                default () {
                    return {
                        name: '',
                        photo: '',
                        email: '',
                        position: '',
                        factory: '',
                    }
                }
            }
        },
        data() {
            return {
                state: {
                    PENDING: true,
                    CATERED: true,
                    PROCESSING: true,
                    SOLVED: true,
                    CLOSED: true,
                    HOLD: true,
                    CANCELLED: false,
                },
                urgency: {
                    LOW: false,
                    HIGH: false,
                    NORMAL: false,
                },
            }
        }

    }

</script>
