<script>
    import {
        Bar
    } from 'vue-chartjs'

    export default {
        props: {
            date_from: {
                required: true,
            },
            date_to: {
                required: true,
            },
            support: {
                required: true,
            },
        },
        extends: Bar,
        data() {
            return {
                chartData: {
                    labels: ['No Star', '1 Star', '2 Star', '3 Star', '4 Star', '5 Star'],
                    datasets: [{
                        label: 'Rating',
                        data: [12, 19, 3, 5, 2, 3],
                        backgroundColor: [
                            'rgb(92, 92, 92,0.2)',
                            'rgba(250, 150, 150,0.2)',
                            'rgba(247, 156, 77,0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(132, 196, 14,0.2)',

                            'rgba(51, 179, 39,0.2)',
                        ],
                        borderColor: [
                            'rgba(64, 64, 64,1)',
                            'rgba(252, 124, 124,1)',
                            'rgba(227, 137, 59,1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(110, 163, 11,1)',

                            'rgba(39, 135, 30,1)',
                        ],
                        borderWidth: 1
                    }]
                },

            }
        },
        methods: {
            load_data() {
                var vm = this;
                axios.post('/report/tickets/rating-summary', {
                    from: vm.date_from,
                    to: vm.date_to,
                    support: vm.support,
                }).then(response => {
                    var ratings = response.data;
                    vm.chartData.datasets[0].data = [
                        ratings.no,
                        ratings.one,
                        ratings.two,
                        ratings.three,
                        ratings.four,
                        ratings.five,
                    ];

                    let options = {
                        title: {
                            display: true,
                            text: 'Overall User Rating'
                        },


                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    stepSize: 10
                                }
                            }]
                        }
                    };
                    vm.renderChart(vm.chartData, options);

                });
            }
        },
        watch: {
            date_from: function () {
                this.load_data();
            },
            date_to: function () {
                this.load_data();
            },
              support: function () {
                this.load_data();
            },
        },
        mounted() {

            this.$nextTick(function () {
                this.load_data();
            });
        }
    }

</script>
