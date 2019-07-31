<script>
    import {
        Pie
    } from 'vue-chartjs'

    export default {
        extends: Pie,
        data() {
            return {
                chartData: {
                    labels: [
                        'PMS Account Request',
                        'Domain Account',
                        'Hardware Concern',
                        'System Application',
                        'Email Database',
                    ],
                    datasets: [{
                        data: [12, 19, 3, 5, 2],
                        backgroundColor: [
                            'rgba(54, 69, 59, 1)',
                            'rgba(81, 87, 81, 1)',
                            'rgba(101, 145, 87, 1)',
                            'rgba(245, 249, 233, 1)',
                            'rgba(194, 193, 165, 1)',
                        ],


                    }],


                },
                options: {
                    title: {
                        display: true,
                        text: 'Summary Ticket Requested'
                    },
                    legend: {
                        position: 'left',
                        display: true,
                    },

                    responsive: true,
                    animation: {
                        animateScale: true
                    },
                }
            }
        },
        mounted() {
            this.renderChart(this.chartData, this.options)
        }
    }

</script>
