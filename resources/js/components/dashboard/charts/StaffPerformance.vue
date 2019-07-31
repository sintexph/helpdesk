<script>
    import {
        HorizontalBar
    } from 'vue-chartjs'

    export default {
        extends: HorizontalBar,
        data() {
            return {

                options: {

                    title: {
                        display: true,
                        text: 'Weekly Top 8 Support Staff Performances'
                    },


                    responsive: true,
                    animation: {
                        animateScale: true
                    },

                    maintainAspectRatio: false,
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                            },
                            barThickness: 8,

                            gridLines: {
                                offsetGridLines: true
                            }
                        }]
                    }
                }
            }
        },
        methods: {
               chartData(labels,data) {
                return {
                    labels: labels,
                    datasets: [{
                        label: 'Performance %',
                        data: data,
                        backgroundColor: [
                            'rgba(34, 92, 186,0.5)',
                            'rgba(34, 92, 186,0.5)',
                            'rgba(34, 92, 186,0.5)',
                            'rgba(34, 92, 186,0.5)',
                            'rgba(34, 92, 186,0.5)',
                            'rgba(34, 92, 186,0.5)',
                        ],
                        borderWidth: 1
                    }]
                }
            },

            load_data() {
                var vm = this;
                axios.post('/dashboard/staff_performance').then(response => {
                    var users = response.data;
                    var data=[];
                    var labels=[];
                    users.forEach(element => {
                       
                        labels.push(element.name);
                        data.push(0);
                       

                    });

                     vm.renderChart(vm.chartData(labels,data), vm.options);


                });
            }
        }, 
        mounted() {

            this.$nextTick(function () {
                this.load_data();
            });
        }
    }

</script>
