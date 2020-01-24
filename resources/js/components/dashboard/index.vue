<template>
    <div>
        <div class="statistic-box row">
            <div class="col-sm-3">
                <div class="info-box">
                    <span class="info-box-icon">
                        <i class="fa fa-user" aria-hidden="true"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Users</span>
                        <span class="info-box-number" v-text="statistics.users"></span>
                    </div>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="info-box">
                    <span class="info-box-icon">
                        <i class="fa fa-ticket" aria-hidden="true"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Tickets</span>
                        <span class="info-box-number" v-text="statistics.tickets">90</span>
                    </div>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="info-box">
                    <span class="info-box-icon">
                        <i class="fa fa-ticket" aria-hidden="true"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Pending Tickets</span>
                        <span class="info-box-number" v-text="statistics.pending_tickets">90</span>
                    </div>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="info-box">
                    <span class="info-box-icon">
                        <i class="fa fa-ticket" aria-hidden="true"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Closed Tickets</span>
                        <span class="info-box-number" v-text="statistics.closed_tickets">90</span>
                    </div>
                </div>
            </div>


        </div>



        <div class="box box-solid">
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                        <weekly-rating style="width:420px;"></weekly-rating>
                    </div>
                    <div class="col-sm-6">
                        <top-five-category style="width:380px;"></top-five-category>
                    </div>
                </div>
            </div>
        </div>


        <div class="box box-solid">
            <div class="box-body">
                <staff-performance style="width:100%;"></staff-performance>
            </div>
        </div>

    </div>
</template>
<script>
    import WeeklyRating from "./charts/WeeklyRating";
    import TopFiveCategory from "./charts/TopFiveCategory";
    import StaffPerformance from "./charts/StaffPerformance";

    export default {
        components: {
            'weekly-rating': WeeklyRating,
            'top-five-category': TopFiveCategory,
            'staff-performance': StaffPerformance,
        },
        data() {
            return {
                statistics: {
                    users: 0,
                    tickets: 0,
                    pending_tickets: 0,
                    closed_tickets: 0,
                }
            }
        },
        methods:{
            load_statistics()
            {
                let vm=this;
                axios.post('/dashboard/statistics').then(response=>{
                    vm.statistics=response.data;
                });
            }
        },
        mounted()
        {
            this.load_statistics();
        }
    }

</script>

<style>
    .statistic-box .box {
        padding: 10px;
    }

    .statistic-box .box .fa {
        width: 1.28571429em;
        text-align: center;
    }

</style>
