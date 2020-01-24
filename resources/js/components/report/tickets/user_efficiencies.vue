<script>
    import {
        HorizontalBar
    } from 'vue-chartjs'

    export default {
        extends: HorizontalBar,
        props: {
            date_from: {
                required: true,
            },
            date_to: {
                required: true,
            }
        },
        watch: {
            date_from: function () {
                this.load_data();
            },
            date_to: function () {
                this.load_data();
            }
        },

        data() {
            return {

                options: {
                    tooltip: {
                        isHtml: true
                    },
                    title: {
                        display: true,
                        text: 'Support Efficiency Result'
                    },


                    responsive: true,
                    animation: {
                        animateScale: true
                    },

                    maintainAspectRatio: false,
                    scales: {

                        xAxes: [{
                            ticks: {
                                beginAtZero: true,
                                stepSize: 10
                            },
                            barThickness: 20,
                            gridLines: {
                                offsetGridLines: true
                            },

                        }]
                    }
                }
            }
        },
        methods: {
            chartData(labels, data, bgColors) {
                return {
                    labels: labels,
                    datasets: [{
                        label: 'Percentage',
                        data: data,
                        borderWidth: 2,
                        backgroundColor: bgColors,
                    }]
                }
            },

            load_data() {
                var vm = this;
                axios.post('/report/tickets/users-efficiency', {
                    from: vm.date_from,
                    to: vm.date_to,
                }).then(response => {
                    var users = response.data;

 
                    var data = [];
                    var labels = [];
                    var bgColors = [];
                    users.forEach(element => {

                        labels.push(element.name);
                        data.push(element.rate);
                        bgColors.push(vm.getRandomColor());


                    });

                    vm.renderChart(vm.chartData(labels, data, bgColors), vm.options);


                });
            },
            getRandomColor() {
                return 'rgba(' + (Math.floor(Math.random() * 200)) + ',' + (Math.floor(Math.random() * 200)) + ',' + (Math.floor(Math.random() * 200)) + ', 0.5)';
            }

        },
        mounted() {
            this.$nextTick(function () {
                this.load_data();
            });


        }
    }

</script>
