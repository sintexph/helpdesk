<template>
    <div v-if="ticket!==null && (ticket.state!== State.CANCELLED && ticket.state!==State.CLOSED) && (ticket.state===State.PENDING || can_update===true)"
        class="box box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">Actions</h3>
        </div>
        <div class="box-body no-padding">
            <ul class="nav nav-pills nav-stacked">

                <li v-if="ticket.state===State.PENDING">
                    <a href="#" @click.prevent="cater_ticket">
                        <i aria-hidden="true" class="fa fa-download"></i>
                        <span>Cater Ticket</span>
                    </a>
                </li>

                <template v-if="can_update===true">

                    <li v-if="ticket.state === State.PROCESSING">
                        <a class="text-yellow" href="#" @click.prevent="custom_progress">
                            <i class="fa fa-cube" aria-hidden="true"></i>
                            <span>Custom Progress</span>
                        </a>
                    </li>

                    <li v-if="ticket.state !== State.SOLVED && ticket.state !== State.PENDING">
                        <a href="#" @click.prevent="escalate_ticket">
                            <i class="fa fa-forward" aria-hidden="true"></i>
                            <span>Escalate Ticket</span>
                        </a>
                    </li>

                    <li v-if="ticket.state === State.CATERED">
                        <a href="#" @click.prevent="process_ticket">
                            <i class="fa fa-spinner" aria-hidden="true"></i>
                            <span>Process Ticket</span>
                        </a>
                    </li>
                    <li v-if="ticket.state === State.PROCESSING">
                        <a href="#" @click.prevent="solve_ticket">
                            <i aria-hidden="true" class="fa fa-star"></i>
                            <span>Solve Ticket</span>
                        </a>
                    </li>
                    <li v-if="ticket.state===State.CATERED || ticket.state===State.PROCESSING">
                        <a href="#" @click.prevent="hold_ticket">
                            <i aria-hidden="true" class="fa fa-file-text-o"></i>
                            <span>Hold Ticket</span>
                        </a>
                    </li>
                    <li v-if="ticket.state===State.HOLD">
                        <a href="#" @click.prevent="un_hold_ticket">
                            <i aria-hidden="true" class="fa fa-pencil"></i>
                            <span>Un Hold Ticket</span>
                        </a>
                    </li>
                    <li v-if="ticket.state===State.SOLVED">
                        <a href="#" @click.prevent="open_ticket">
                            <i class="fa fa-folder-open" aria-hidden="true"></i>
                            <span>Open Ticket</span>
                        </a>
                    </li>
                    <li v-if="ticket.state!==State.PENDING  && ticket.state!== State.SOLVED">
                        <a href="#" @click.prevent="add_reference_ticket">
                            <i class="fa fa-ticket" aria-hidden="true"></i>
                            <span>Add Reference Ticket</span>
                        </a>
                    </li>
                    <li
                        v-if="ticket.state!==State.PENDING  && ticket.state!== State.SOLVED && ticket.state !== State.APPLIED_APPROVAL">
                        <a href="#" @click.prevent="cancel_ticket">
                            <i aria-hidden="true" class="fa fa-undo"></i>
                            <span>Cancel Ticket</span>
                        </a>
                    </li>
                    <template v-if="ticket.state !== State.PENDING && ticket.state!==State.SOLVED">
                        <li v-if="ticket.approver_name===null && ticket.approver_email===null">
                            <a href="#" @click.prevent="apply_approval">
                                <i class="fa fa-thumbs-up" aria-hidden="true"></i>
                                <span>Initiate Approval</span>
                            </a>
                        </li>
                        <template v-else-if="ticket.approved_at===null">
                            <li>
                                <a class="text-grey" href="#" @click.prevent="resend_approval">
                                    <i class="fa fa-envelope-o" aria-hidden="true"></i>
                                    <span>Resend Approval</span>
                                </a>
                            </li>
                            <li>
                                <a class="text-red" href="#" @click.prevent="cancel_approval">
                                    <i class="fa fa-ban" aria-hidden="true"></i>
                                    <span>Cancel Approval</span>
                                </a>
                            </li>
                        </template>
                    </template>
                </template>
            </ul>
            <escalate-action :ticket_id="ticket.id" ref="escalateAction"></escalate-action>
            <solve-action :ticket_id="ticket.id" ref="solveAction"></solve-action>
            <cater-action :ticket_id="ticket.id" ref="caterAction"></cater-action>
            <add-reference-action :ticket_id="ticket.id" ref="addReferenceAction"></add-reference-action>
            <apply-approval :ticket_id="ticket.id" ref="applyApproval"></apply-approval>
            <custom-progress :ticket_id="ticket.id" ref="customProgress"></custom-progress>
        </div>
    </div>
</template>

<script>
    import solveAction from './solved';
    import caterAction from './cater';
    import escalateAction from './escalate';
    import applyApproval from './apply_approval';
    import addReferenceAction from './add_reference';
    import customProgress from './custom_progress';

    export default {

        components: {
            'custom-progress': customProgress,
            'solve-action': solveAction,
            'cater-action': caterAction,
            'escalate-action': escalateAction,
            'add-reference-action': addReferenceAction,
            'apply-approval': applyApproval,
        },
        props: {
            can_update: {
                required: true,
                type: Boolean,
                default () {
                    return false;
                }
            },
            ticket: {
                type: [Object, Array],
                required: true,
            },
        },
        data() {
            return {
                submitted: false,
            }
        },
        methods: {
            custom_progress() {
                if (this.ticket.state !== State.SOLVED && this.ticket.state !== State.CLOSED && this.ticket
                    .state !== State
                    .CANCELLED && this.ticket.state !== State.PENDING) {
                    this.$refs.customProgress.show();
                }
            },




            resend_approval() {

                var par = this;
                if (par.submitted === false) {
                    if (confirm('Are you sure you want to resend the approval?') === true) {
                        par.submitted = true;
                        par.show_wait("Please wait while the system is processing your request.....");

                        axios.post('/tickets/resend_approval/' + par.ticket.id).then(function (response) {
                            par.hide_wait();
                            par.alert_success(response);
                            location.reload();
                        }).catch(function (error) {
                            par.hide_wait();
                            par.submitted = false;
                            par.alert_failed(error);
                        });


                    }
                }

            },


            cancel_approval() {

                var par = this;
                if (par.submitted === false) {
                    if (confirm('Are you sure you want to cancel the approval?') === true) {
                        par.submitted = true;
                        par.show_wait("Please wait while the system is processing your request.....");

                        axios.delete('/tickets/cancel_approval/' + par.ticket.id).then(function (response) {
                            par.hide_wait();
                            par.alert_success(response);
                            location.reload();
                        }).catch(function (error) {
                            par.hide_wait();
                            par.submitted = false;
                            par.alert_failed(error);
                        });


                    }
                }

            },

            apply_approval() {
                this.$refs.applyApproval.show();
            },
            escalate_ticket() {
                if (this.ticket.state !== State.SOLVED && this.ticket.state !== State.CLOSED && this.ticket
                    .state !== State
                    .CANCELLED && this.ticket.state !== State.PENDING) {
                    this.$refs.escalateAction.show();
                }
            },

            add_reference_ticket() {
                if (this.ticket.state !== State.PENDING) {
                    this.$refs.addReferenceAction.show();
                }
            },
            cater_ticket() {
                if (this.ticket.state === State.PENDING) {
                    this.$refs.caterAction.show();
                }
            },
            solve_ticket() {
                if (this.ticket.state === State.PROCESSING) {
                    this.$refs.solveAction.show();
                }
            },
            open_ticket() {

                var par = this;
                if (par.submitted === false) {
                    if (confirm('Do you want to re-open the ticket?') === true) {
                        var reason = prompt("Reason for opening the ticket", "");
                        if (reason !== null) {
                            par.submitted = true;
                            par.show_wait("Please wait while the system is processing your request.....");
                            axios.patch('/tickets/open/' + par.ticket.id, {
                                reason: reason
                            }).then(function (response) {
                                par.hide_wait();
                                par.alert_success(response);
                                location.reload();
                            }).catch(function (error) {
                                par.hide_wait();
                                par.submitted = false;
                                par.alert_failed(error);
                            });
                        }

                    }
                }
            },
            hold_ticket() {
                var par = this;
                if (par.submitted === false) {
                    if (confirm('Do you want to hold the ticket?') === true) {
                        par.submitted = true;
                        par.show_wait("Please wait while the system is processing your request.....");
                        axios.patch('/tickets/hold/' + par.ticket.id).then(function (response) {
                            par.hide_wait();
                            par.alert_success(response);
                            location.reload();
                        }).catch(function (error) {
                            par.hide_wait();
                            par.submitted = false;
                            par.alert_failed(error);
                        });
                    }
                }

            },
            un_hold_ticket() {
                var par = this;
                if (par.submitted === false) {
                    if (confirm('Do you want to un hold the ticket?') === true) {
                        par.submitted = true;
                        par.show_wait("Please wait while the system is processing your request.....");
                        axios.patch('/tickets/un_hold/' + par.ticket.id).then(function (response) {
                            par.hide_wait();
                            par.alert_success(response);
                            location.reload();
                        }).catch(function (error) {
                            par.hide_wait();
                            par.submitted = false;
                            par.alert_failed(error);
                        });
                    }
                }

            },
            process_ticket() {
                var par = this;
                if (par.submitted === false) {
                    if (confirm('Do you want to process now the ticket?') === true) {
                        par.submitted = true;
                        par.show_wait("Please wait while the system is processing your request.....");
                        axios.patch('/tickets/processing/' + par.ticket.id).then(function (response) {
                            par.hide_wait();
                            par.alert_success(response);
                            location.reload();
                        }).catch(function (error) {
                            par.hide_wait();
                            par.submitted = false;
                            par.alert_failed(error);
                        });
                    }
                }
            },
            cancel_ticket() {

                var par = this;
                if (par.submitted === false) {
                    if (confirm('Do you want to cancel the ticket?') === true) {
                        var reason = prompt("Please put cancellation reason", "");
                        if (reason !== null) {
                            par.submitted = true;
                            par.show_wait("Please wait while the system is processing your request.....");

                            axios.patch('/tickets/cancel/' + par.ticket.id, {
                                cancellation_reason: reason
                            }).then(function (response) {
                                par.hide_wait();
                                par.alert_success(response);
                                location.reload();
                            }).catch(function (error) {
                                par.submitted = false;
                                par.hide_wait();
                                par.alert_failed(error);
                            });
                        }

                    }
                }




            }
        },


    }

</script>
