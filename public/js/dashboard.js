document.addEventListener('DOMContentLoaded', function () {

    const {
        pendapatanLabels,
        pendapatanValues,
        produkTerlarisLabels,
        produkTerlarisValues,
        membershipLabels,
        membershipValues,
        kategoriLabels,
        kategoriValues,
        metodePembayaranLabels,
        metodePembayaranJumlahTransaksi,
        metodePembayaranTotalUang
    } = chartData;

    const brandColors = {
        primary: {
            blue: 'rgba(59, 130, 246, 0.8)',
            green: 'rgba(16, 185, 129, 0.8)',
            yellow: 'rgba(255, 205, 86, 0.8)',
            purple: 'rgba(168, 85, 247, 0.8)',
            pink: 'rgba(236, 72, 153, 0.8)',
            gray: 'rgba(107, 114, 128, 0.8)',
            orange: 'rgba(251, 146, 60, 0.8)',
            red: 'rgba(239, 68, 68, 0.8)',
            indigo: 'rgba(99, 102, 241, 0.8)'
        },
        hover: {
            blue: 'rgba(59, 130, 246, 1)',
            green: 'rgba(16, 185, 129, 1)',
            yellow: 'rgba(255, 205, 86, 1)',
            purple: 'rgba(168, 85, 247, 1)',
            pink: 'rgba(236, 72, 153, 1)',
            gray: 'rgba(107, 114, 128, 1)',
            orange: 'rgba(251, 146, 60, 1)',
            red: 'rgba(239, 68, 68, 1)',
            indigo: 'rgba(99, 102, 241, 1)'
        }
    };

    // Store chart instances
    let pendapatanChartInstance = null;
    let produkTerlarisChartInstance = null;
    let metodePembayaranChartInstance = null;
    let membershipChartInstance = null;
    let kategoriChartInstance = null;

    // Fungsi untuk membuat warna gradien pada bar
    function createGradient(ctx, chartArea, color) {
        const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
        gradient.addColorStop(0, color);
        gradient.addColorStop(1, 'white');
        return gradient;
    }

    // Fungsi untuk inisialisasi Chart Pendapatan
    function initPendapatanChart(labels, values) {
        const ctxPendapatan = document.getElementById('pendapatanChart')?.getContext('2d');
        if (ctxPendapatan) {
            if (pendapatanChartInstance) {
                pendapatanChartInstance.destroy();
            }
            pendapatanChartInstance = new Chart(ctxPendapatan, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Pendapatan',
                        data: values,
                        backgroundColor: function(context) {
                            const chart = context.chart;
                            const { chartArea } = chart;
                            if (!chartArea) return;
                            return createGradient(ctxPendapatan, chartArea, brandColors.primary.blue);
                        },
                        borderColor: brandColors.primary.blue,
                        borderWidth: 1,
                        borderRadius: 5,
                        hoverBackgroundColor: brandColors.hover.blue,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(200, 200, 200, 0.2)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }
    }

    // Fungsi untuk inisialisasi Chart Produk Terlaris
    function initProdukTerlarisChart(labels, values) {
        const ctxProdukTerlaris = document.getElementById('produkTerlarisChart')?.getContext('2d');
        if (ctxProdukTerlaris) {
            if (produkTerlarisChartInstance) {
                produkTerlarisChartInstance.destroy();
            }
            produkTerlarisChartInstance = new Chart(ctxProdukTerlaris, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Jumlah Terjual',
                        data: values,
                        backgroundColor: function(context) {
                            const chart = context.chart;
                            const { chartArea } = chart;
                            if (!chartArea) return;
                            return createGradient(ctxProdukTerlaris, chartArea, brandColors.primary.green);
                        },
                        borderColor: brandColors.primary.green,
                        borderWidth: 1,
                        borderRadius: 5,
                        hoverBackgroundColor: brandColors.hover.green,
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(200, 200, 200, 0.2)'
                            }
                        },
                        y: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }
    }

    // Fungsi untuk inisialisasi Chart Transaksi per Metode Pembayaran
    function initMetodePembayaranChart(labels, jumlahTransaksi, totalUang) {
        const ctxMetodePembayaran = document.getElementById('metodePembayaranChart')?.getContext('2d');
        if (ctxMetodePembayaran) {
            if (metodePembayaranChartInstance) {
                metodePembayaranChartInstance.destroy();
            }
            metodePembayaranChartInstance = new Chart(ctxMetodePembayaran, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Total Uang',
                        data: totalUang,
                        borderColor: brandColors.primary.blue,
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 3,
                        pointBackgroundColor: brandColors.primary.blue,
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    layout: {
                        padding: {
                            left: 10,
                            right: 10,
                            top: 10,
                            bottom: 5
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.9)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: 'rgba(255, 255, 255, 0.2)',
                            borderWidth: 1,
                            cornerRadius: 8,
                            displayColors: false,
                            titleFont: {
                                size: 13,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 12
                            },
                            padding: 12,
                            callbacks: {
                                title: function(context) {
                                    return context[0].label;
                                },
                                label: function(context) {
                                    const index = context.dataIndex;
                                    const jmlTransaksi = jumlahTransaksi[index];
                                    const ttlUang = totalUang[index];
                                    
                                    return [
                                        'Total: Rp ' + new Intl.NumberFormat('id-ID').format(ttlUang),
                                        'Transaksi: ' + jmlTransaksi
                                    ];
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(200, 200, 200, 0.2)',
                                drawBorder: false
                            },
                            ticks: {
                                font: {
                                    size: 10
                                },
                                maxTicksLimit: 5,
                                padding: 5,
                                callback: function(value) {
                                    return 'Rp ' + new Intl.NumberFormat('id-ID', {
                                        notation: 'compact',
                                        compactDisplay: 'short'
                                    }).format(value);
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false,
                                drawBorder: false
                            },
                            ticks: {
                                font: {
                                    size: 11,
                                    weight: 'bold'
                                },
                                color: '#374151',
                                maxRotation: 0,
                                padding: 5
                            }
                        }
                    },
                    elements: {
                        point: {
                            hoverBackgroundColor: brandColors.hover.blue,
                        }
                    },
                    onHover: function(event, elements) {
                        event.native.target.style.cursor = elements.length > 0 ? 'pointer' : 'default';
                    }
                }
            });
        }
    }

    // Inisialisasi Chart Membership
    const ctxMembership = document.getElementById('membershipChart')?.getContext('2d');
    if (ctxMembership) {
        membershipChartInstance = new Chart(ctxMembership, {
            type: 'pie',
            data: {
                labels: membershipLabels,
                datasets: [{
                    label: 'Distribusi Membership',
                    data: membershipValues,
                    backgroundColor: [
                        brandColors.primary.yellow,
                        brandColors.primary.blue,
                        brandColors.primary.green,
                        brandColors.primary.purple,
                    ],
                    borderColor: 'white',
                    borderWidth: 4,
                    hoverBackgroundColor: [
                        brandColors.hover.yellow,
                        brandColors.hover.blue,
                        brandColors.hover.green,
                        brandColors.hover.purple,
                    ],
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.raw !== null) {
                                    const total = membershipValues.reduce((sum, value) => sum + value, 0);
                                    const percentage = (context.raw / total * 100).toFixed(2);
                                    label += `${context.raw} anggota (${percentage}%)`;
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    }

    // Inisialisasi Chart Produk per Kategori
    const ctxKategori = document.getElementById('kategoriChart')?.getContext('2d');
    if (ctxKategori) {
        kategoriChartInstance = new Chart(ctxKategori, {
            type: 'doughnut',
            data: {
                labels: kategoriLabels,
                datasets: [{
                    label: 'Jumlah Produk',
                    data: kategoriValues,
                    backgroundColor: [
                        brandColors.primary.yellow,
                        brandColors.primary.blue,
                        brandColors.primary.green,
                        brandColors.primary.purple,
                        brandColors.primary.pink,
                        brandColors.primary.gray
                    ],
                    borderColor: 'white',
                    borderWidth: 4,
                    hoverBackgroundColor: [
                        brandColors.hover.yellow,
                        brandColors.hover.blue,
                        brandColors.hover.green,
                        brandColors.hover.purple,
                        brandColors.hover.pink,
                        brandColors.hover.gray
                    ],
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                }
            }
        });
    }

    // Initialize charts
    initPendapatanChart(pendapatanLabels, pendapatanValues);
    initProdukTerlarisChart(produkTerlarisLabels, produkTerlarisValues);
    initMetodePembayaranChart(metodePembayaranLabels, metodePembayaranJumlahTransaksi, metodePembayaranTotalUang);

    // Event Listener untuk filter tahun (AJAX)
    const tahunFilter = document.getElementById('tahunFilter');
    if (tahunFilter) {
        tahunFilter.addEventListener('change', function() {
            const tahun = this.value;
            const chartTitle = document.querySelector('#pendapatanChart').closest('.bg-white').querySelector('h2');
            chartTitle.textContent = `Pendapatan Bulanan Tahun ${tahun}`;
            
            // Show loading state
            const canvas = document.getElementById('pendapatanChart');
            canvas.style.opacity = '0.5';
            
            fetch(`/dashboard/pendapatan?tahun=${tahun}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                initPendapatanChart(data.labels, data.values);
                canvas.style.opacity = '1';
            })
            .catch(error => {
                console.error('Error:', error);
                canvas.style.opacity = '1';
            });
        });
    }

    // Event Listener untuk filter bulan (AJAX)
    const bulanFilter = document.getElementById('bulanFilter');
    if (bulanFilter) {
        bulanFilter.addEventListener('change', function() {
            const bulan = this.value;
            const tahunFilter = document.getElementById('tahunFilter');
            const tahun = tahunFilter ? tahunFilter.value : new Date().getFullYear();
            
            // Show loading state
            const canvas = document.getElementById('metodePembayaranChart');
            canvas.style.opacity = '0.5';
            
            fetch(`/dashboard/metode-pembayaran?bulan=${bulan}&tahun=${tahun}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                initMetodePembayaranChart(data.labels, data.jumlahTransaksi, data.totalUang);
                canvas.style.opacity = '1';
            })
            .catch(error => {
                console.error('Error:', error);
                canvas.style.opacity = '1';
            });
        });
    }

    // Event Listener untuk filter kategori (AJAX)
    const kategoriFilter = document.getElementById('kategoriFilter');
    if (kategoriFilter) {
        kategoriFilter.addEventListener('change', function() {
            const kategoriId = this.value;
            
            // Show loading state
            const canvas = document.getElementById('produkTerlarisChart');
            canvas.style.opacity = '0.5';
            
            fetch(`/dashboard/produk-terlaris?kategori_id=${kategoriId}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                initProdukTerlarisChart(data.labels, data.values);
                canvas.style.opacity = '1';
            })
            .catch(error => {
                console.error('Error:', error);
                canvas.style.opacity = '1';
            });
        });
    }
});