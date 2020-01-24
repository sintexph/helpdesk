<script>
    import {
        Pie
    } from 'vue-chartjs'

    export default {
        extends: Pie,

        mounted() {
            let vm = this;

            axios.post('/dashboard/category_request').then(response => {

                var data = [];
                var labels = [];

                response.data.forEach(element => {
                    labels.push(element.Category)
                    data.push(element.Number);
                });
                let chartData = {
                    labels:labels,
                    datasets: [{
                        data: data,
                        backgroundColor: [
                            'rgba(54, 69, 59, 1)',
                            'rgba(81, 87, 81, 1)',
                            'rgba(101, 145, 87, 1)',
                            'rgba(245, 249, 233, 1)',
                            'rgba(194, 193, 165, 1)',
                        ],
                    }],
                };
                let options = {
                    title: {
                        display: true,
                        text: 'Weekly Top 5 Request'
                    },
                    legend: {
                        position: 'left',
                        display: true,
                    },

                    responsive: true,
                    animation: {
                        animateScale: true
                    },
                };

                vm.renderChart(chartData, options);

            });
        }
    }

</script>
