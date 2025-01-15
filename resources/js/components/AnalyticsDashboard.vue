<template>
    <div>
        <h1 class="text-2xl font-bold mb-4">Analytics Dashboard</h1>
        <div class="mb-8">
            <canvas id="statusesChart"></canvas>
        </div>
        <div>
            <canvas id="usersChart"></canvas>
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
            const statusesCtx = document.getElementById('statusesChart').getContext('2d');
            new Chart(statusesCtx, {
                type: 'line',
                data: {
                    labels: this.statusesData.map(data => data.date),
                    datasets: [{
                        label: 'Statuses per Day',
                        data: this.statusesData.map(data => data.count),
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        fill: true,
                        tension: 0.4,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Statuses per Day',
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
                    }
                }
            });

            const usersCtx = document.getElementById('usersChart').getContext('2d');
            new Chart(usersCtx, {
                type: 'line',
                data: {
                    labels: this.usersData.map(data => data.date),
                    datasets: [{
                        label: 'New Users per Day',
                        data: this.usersData.map(data => data.count),
                        borderColor: 'rgba(153, 102, 255, 1)',
                        backgroundColor: 'rgba(153, 102, 255, 0.2)',
                        fill: true,
                        tension: 0.4,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: 'New Users per Day',
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
                    }
                }
            });
        }
    }
};
</script>