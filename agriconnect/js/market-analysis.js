// Initialize charts when the DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize charts with default data
    initializePriceTrendsChart();
    initializeSupplyDemandChart();
    initializeAnalysisChart();

    // Handle filter changes
    const updateAnalysis = document.getElementById('updateAnalysis');
    updateAnalysis.addEventListener('click', updateAllCharts);

    // Handle report view buttons
    const reportButtons = document.querySelectorAll('.view-report');
    reportButtons.forEach(button => {
        button.addEventListener('click', function() {
            const reportTitle = this.parentElement.querySelector('h3').textContent;
            const reportType = reportTitle === 'Daily Market Report' ? 'daily' : 
                             (reportTitle === 'Trend Analysis' ? 'trend' : 'regional');
            showReportModal(reportTitle, reportType);
        });
    });
});

// Initialize price trends chart
async function initializePriceTrendsChart() {
    try {
        const response = await fetch('../php/services/market_analysis.php?action=price_trends');
        const data = await response.json();

        const ctx = document.getElementById('priceTrendsChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.map(item => item.date),
                datasets: [{
                    label: 'Price Trends',
                    data: data.map(item => item.price),
                    borderColor: '#4CAF50',
                    tension: 0.1,
                    fill: false
                }]
            },
            options: getChartOptions()
        });
    } catch (error) {
        console.error('Error loading price trends:', error);
    }
}

// Initialize supply & demand chart
async function initializeSupplyDemandChart() {
    try {
        const response = await fetch('../php/services/market_analysis.php?action=supply_demand');
        const data = await response.json();

        const ctx = document.getElementById('supplyDemandChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.supply.map(item => item.date),
                datasets: [{
                    label: 'Supply',
                    data: data.supply.map(item => item.value),
                    borderColor: '#2196F3',
                    backgroundColor: 'rgba(33, 150, 243, 0.2)',
                    fill: true
                },
                {
                    label: 'Demand',
                    data: data.demand.map(item => item.value),
                    borderColor: '#FF5722',
                    backgroundColor: 'rgba(255, 87, 34, 0.2)',
                    fill: true
                }]
            },
            options: getChartOptions()
        });
    } catch (error) {
        console.error('Error loading supply & demand data:', error);
    }
}

// Initialize analysis chart
async function initializeAnalysisChart() {
    try {
        const cropType = document.getElementById('cropType').value;
        const response = await fetch(`../php/services/market_analysis.php?action=insights&crop_type=${cropType}`);
        const data = await response.json();

        const ctx = document.getElementById('analysisChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Current Price', 'Average Price'],
                datasets: [{
                    label: 'Price Comparison',
                    data: [data.current_price, data.average_price],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)'
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: getChartOptions()
        });

        // Update insights section
        updateInsights(data);
    } catch (error) {
        console.error('Error loading analysis data:', error);
    }
}

// Update all charts based on filter values
async function updateAllCharts() {
    const cropType = document.getElementById('cropType').value;
    const timeRange = document.getElementById('timeRange').value;
    const region = document.getElementById('region').value;

    // Show loading state
    document.querySelectorAll('.chart-container').forEach(container => {
        container.style.opacity = '0.5';
    });

    try {
        await Promise.all([
            updatePriceTrends(cropType, timeRange, region),
            updateSupplyDemand(cropType, timeRange),
            updateAnalysis(cropType)
        ]);
    } catch (error) {
        console.error('Error updating charts:', error);
        showError('Failed to update market data. Please try again.');
    }

    // Reset loading state
    document.querySelectorAll('.chart-container').forEach(container => {
        container.style.opacity = '1';
    });
}

// Update price trends chart
async function updatePriceTrends(cropType, timeRange, region) {
    try {
        const response = await fetch(`../php/services/market_analysis.php?action=price_trends&crop_type=${cropType}&time_range=${timeRange}&region=${region}`);
        const data = await response.json();

        const chart = Chart.getChart('priceTrendsChart');
        chart.data.labels = data.map(item => item.date);
        chart.data.datasets[0].data = data.map(item => item.price);
        chart.update();
    } catch (error) {
        throw new Error('Failed to update price trends');
    }
}

// Update supply & demand chart
async function updateSupplyDemand(cropType, timeRange) {
    try {
        const response = await fetch(`../php/services/market_analysis.php?action=supply_demand&crop_type=${cropType}&time_range=${timeRange}`);
        const data = await response.json();

        const chart = Chart.getChart('supplyDemandChart');
        chart.data.labels = data.supply.map(item => item.date);
        chart.data.datasets[0].data = data.supply.map(item => item.value);
        chart.data.datasets[1].data = data.demand.map(item => item.value);
        chart.update();
    } catch (error) {
        throw new Error('Failed to update supply & demand data');
    }
}

// Update analysis chart and insights
async function updateAnalysis(cropType) {
    try {
        const response = await fetch(`../php/services/market_analysis.php?action=insights&crop_type=${cropType}`);
        const data = await response.json();

        const chart = Chart.getChart('analysisChart');
        chart.data.datasets[0].data = [data.current_price, data.average_price];
        chart.update();

        updateInsights(data);
    } catch (error) {
        throw new Error('Failed to update analysis data');
    }
}

// Update insights section
function updateInsights(data) {
    const trendIcon = data.price_trend === 'increasing' ? '↑' : '↓';
    const trendColor = data.price_trend === 'increasing' ? '#4CAF50' : '#FF5722';

    document.querySelector('.market-insights').innerHTML += `
        <div class="insight-summary">
            <div class="price-trend" style="color: ${trendColor}">
                <span class="trend-icon">${trendIcon}</span>
                <span class="trend-value">${data.price_change}%</span>
            </div>
            <div class="recommendations">
                <h4>Recommendations:</h4>
                <ul>
                    ${data.recommendations.map(rec => `<li>${rec}</li>`).join('')}
                </ul>
            </div>
        </div>
    `;
}

// Show report modal
async function showReportModal(reportTitle, reportType) {
    try {
        const response = await fetch(`../php/services/market_analysis.php?action=reports&report_type=${reportType}`);
        const reportData = await response.json();

        const modal = document.createElement('div');
        modal.className = 'report-modal';
        modal.innerHTML = `
            <div class="modal-content">
                <span class="close-modal">&times;</span>
                <h2>${reportTitle}</h2>
                <div class="report-content">
                    ${generateReportContent(reportData)}
                </div>
            </div>
        `;

        // Add modal styles
        const styles = document.createElement('style');
        styles.textContent = `
            .report-modal {
                display: block;
                position: fixed;
                z-index: 1000;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0,0,0,0.5);
                animation: fadeIn 0.3s ease;
            }
            .modal-content {
                background-color: white;
                margin: 15% auto;
                padding: 20px;
                border-radius: 10px;
                width: 70%;
                max-width: 800px;
                position: relative;
                animation: slideIn 0.3s ease;
            }
            .close-modal {
                position: absolute;
                right: 20px;
                top: 10px;
                font-size: 28px;
                cursor: pointer;
                transition: color 0.3s ease;
            }
            .close-modal:hover {
                color: #FF5722;
            }
            .report-content {
                margin-top: 20px;
            }
            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
            @keyframes slideIn {
                from { transform: translateY(-20px); opacity: 0; }
                to { transform: translateY(0); opacity: 1; }
            }
        `;

        document.head.appendChild(styles);
        document.body.appendChild(modal);

        // Handle close button
        const closeBtn = modal.querySelector('.close-modal');
        closeBtn.onclick = function() {
            modal.remove();
        };

        // Close on outside click
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.remove();
            }
        };
    } catch (error) {
        console.error('Error loading report:', error);
        showError('Failed to load report. Please try again.');
    }
}

// Generate report content based on report type
function generateReportContent(reportData) {
    if (!reportData) return '<p>Report data not available</p>';

    let content = '';

    if (reportData.date) {
        content += `<p class="report-date">Date: ${reportData.date}</p>`;
    }

    if (reportData.summary) {
        content += `<div class="report-summary">
            <h3>Summary</h3>
            <p>${reportData.summary}</p>
        </div>`;
    }

    if (reportData.key_points) {
        content += `<div class="key-points">
            <h3>Key Points</h3>
            <ul>
                ${reportData.key_points.map(point => `<li>${point}</li>`).join('')}
            </ul>
        </div>`;
    }

    if (reportData.patterns) {
        content += `<div class="patterns">
            <h3>Market Patterns</h3>
            <ul>
                ${reportData.patterns.map(pattern => `<li>${pattern}</li>`).join('')}
            </ul>
        </div>`;
    }

    if (reportData.regions) {
        content += `<div class="regional-data">
            <h3>Regional Analysis</h3>
            <div class="regions-grid">
                ${Object.entries(reportData.regions).map(([region, data]) => `
                    <div class="region-card">
                        <h4>${region}</h4>
                        <ul>
                            <li>Demand: ${data.demand}</li>
                            <li>Supply: ${data.supply}</li>
                            <li>Price Trend: ${data.price_trend}</li>
                        </ul>
                    </div>
                `).join('')}
            </div>
        </div>`;
    }

    return content;
}

// Show error message
function showError(message) {
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.textContent = message;
    errorDiv.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background-color: #ff5722;
        color: white;
        padding: 15px 25px;
        border-radius: 5px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        z-index: 1000;
        animation: slideIn 0.3s ease;
    `;

    document.body.appendChild(errorDiv);

    setTimeout(() => {
        errorDiv.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => errorDiv.remove(), 300);
    }, 3000);
}

// Get common chart options
function getChartOptions() {
    return {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top',
            },
            title: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: 'rgba(0, 0, 0, 0.1)'
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        },
        animation: {
            duration: 1000,
            easing: 'easeInOutQuart'
        },
        elements: {
            line: {
                tension: 0.4
            },
            point: {
                radius: 3,
                hoverRadius: 6
            }
        }
    };
} 