<template>
    <div>
        <div>
        <h1 class="text-2xl font-bold mb-4">Analytics Dashboard</h1>
            <canvas id="combinedChart" class="bg-gray-900 p-4 rounded-lg"></canvas>
        </div>
    </div>
</template>

<script>
import { Chart } from 'chart.js/auto';
import 'chartjs-adapter-moment';
import axios from 'axios';

export default {
    data() {
        return {
            statusesData: [],
            usersData: [],
            chartType: 'line',
        };
    },
    mounted() {
        this.fetchAnalyticsData();
    },
    methods: {
        fetchAnalyticsData() {
            axios.get('/analytics')
                .then(response => {
                    this.statusesData = response.data.statusesPerDay;
                    this.usersData = response.data.usersPerDay;
                    this.renderCharts();
                })
                .catch(error => {
                    console.error('Failed to fetch analytics data:', error);
                });
        },
        renderCharts() {
            const combinedCtx = document.getElementById('combinedChart').getContext('2d');
            new Chart(combinedCtx, {
                type: this.chartType,
                data: {
                    labels: this.statusesData.map(data => data.date),
                    datasets: [
                        {
                            label: 'Statuses per Day',
                            data: this.statusesData.map(data => data.count),
                            borderColor: 'rgba(75, 192, 192, 1)',
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            fill: true,
                            tension: 0.4,
                        },
                        {
                            label: 'New Users per Day',
                            data: this.usersData.map(data => data.count),
                            borderColor: 'rgba(153, 102, 255, 1)',
                            backgroundColor: 'rgba(153, 102, 255, 0.2)',
                            fill: true,
                            tension: 0.4,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Statuses and New Users per Day',
                            font: {
                                size: 18
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                        },
                        legend: {
                            display: true,
                            position: 'top',
                        }
                    },
                    scales: {
                        x: {
                            type: 'time',
                            time: {
                                unit: 'day'
                            },
                            grid: {
                                display: true,
                                color: 'rgba(200, 200, 200, 0.2)'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                display: true,
                                color: 'rgba(200, 200, 200, 0.2)'
                            }
                        }
                    },
                    // animations: {
                    //     tension: {
                    //         duration: 1000,
                    //         easing: 'scatter',
                    //         from: 0,
                    //         to: 1,
                    //         loop: true
                    //     }
                    // }
                }
            });
        }
    }
};
</script>
