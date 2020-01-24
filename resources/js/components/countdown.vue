<template>
    <div class="box box-solid">
        <div class="box-body">
            <div class="timer-container">
                <center>
                    <table>
                        <tbody>
                            <tr>
                                <td colspan="4" class="head" v-text="header_text">Countdown</td>
                            </tr>
                            <tr>
                                <td v-text="days"></td>
                                <td v-text="hours"></td>
                                <td v-text="minutes"></td>
                                <td v-text="seconds"></td>
                            </tr>
                            <tr>
                                <th>DAYS</th>
                                <th>HR.</th>
                                <th>MIN.</th>
                                <th>SEC.</th>
                            </tr>
                        </tbody>
                    </table>
                </center>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                days: 0,
                hours: 0,
                minutes: 0,
                seconds: 0,
                header_text: 'Countdown',
            }
        },
        props: {
            ticket_id: {
                required: true,
            },
        },
        methods: {
            countdown(expired_at) {
                var vm = this;

                // Set the date we're counting down to
                var countDownDate = new Date(expired_at).getTime();

                // Update the count down every 1 second
                var x = setInterval(function () {

                    // Get today's date and time
                    var now = new Date().getTime();

                    // Find the distance between now and the count down date
                    var distance = countDownDate - now;

                    // Time calculations for days, hours, minutes and seconds
                    vm.days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    vm.hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    vm.minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    vm.seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    // If the count down is finished, write some text
                    if (distance < 0) {
                        clearInterval(x);
                        //document.getElementById("demo").innerHTML = "EXPIRED";
                    }
                    
                }, 1000);
            },
            get_countdown() {
                var vm = this;
                axios.post('/utility/ticket-countdown/' + vm.ticket_id).then(response => {
                    vm.countdown(response.data.countdown_expired_at);
                    vm.header_text = response.data.countdown_message;
                });
            }
        },
        mounted() {
            this.get_countdown();
        }
    }

</script>

<style>
    .timer-container {
        padding: 10px;
        color: rgb(49, 49, 49);
        text-align: center;
    }

    .timer-container .head {
        font-weight: 800;
        font-size: 1em;
        text-transform: uppercase;
        color: rgb(206, 52, 52);
        padding-bottom: 10px;
    }

    .timer-container table td {
        color: rgb(173, 8, 8);
        font-weight: 800;
        font-size: 1.5em;
    }

    .timer-container table td,
    .timer-container table th {
        text-align: center;
        padding: 0 10px 0 10px;

    }

</style>
