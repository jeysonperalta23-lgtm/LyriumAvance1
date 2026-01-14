
document.addEventListener('DOMContentLoaded', function () {
    // Mock Data
    const totalUsers = { count: 1250 };
    const totalSales = { amount: 75230.50 };
    const newOrders = { count: 350 };
    const pendingTasks = { count: 15 };

    const recentSales = [
        { id: 'ORD-001', user: 'Juan Pérez', date: '2025-12-14', total: 150.00, status: 'Completado' }, // Corregido a 'Completado'
        { id: 'ORD-002', user: 'Ana Gómez', date: '2025-12-14', total: 200.50, status: 'Pendiente' }, // Corregido a 'Pendiente'
        { id: 'ORD-003', user: 'Carlos Ruiz', date: '2025-12-13', total: 75.20, status: 'Completado' },
        { id: 'ORD-004', user: 'María López', date: '2025-12-13', total: 500.00, status: 'Enviado' },
        { id: 'ORD-005', user: 'Luis Fernandez', date: '2025-12-12', total: 320.75, status: 'Cancelado' },
    ];

    const salesByMonth = {
        labels: ['Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        data: [12000, 19000, 15000, 21000, 17000, 25000]
    };

    // Update Metric Cards
    document.getElementById('total-users').textContent = totalUsers.count.toLocaleString();
    document.getElementById('total-sales').textContent = `$${totalSales.amount.toLocaleString('es-ES', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
    document.getElementById('new-orders').textContent = newOrders.count.toLocaleString();
    document.getElementById('pending-tasks').textContent = pendingTasks.count.toLocaleString();

    // Populate Recent Sales Table
    const recentSalesTbody = document.getElementById('recent-sales-tbody');
    recentSales.forEach(sale => {
        const row = document.createElement('tr');

        const statusClass = {
            'Completado': 'status-completed',
            'Pendiente': 'status-pending',
            'Enviado': 'status-shipped',
            'Cancelado': 'status-cancelled'
        }[sale.status] || '';

        row.innerHTML = `
            <td>${sale.id}</td>
            <td>${sale.user}</td>
            <td>${new Date(sale.date).toLocaleDateString('es-ES')}</td>
            <td>$${sale.total.toFixed(2)}</td>
            <td><span class="status ${statusClass}">${sale.status}</span></td>
        `;
        recentSalesTbody.appendChild(row);
    });

    // Render Sales Chart
    const salesChartCtx = document.getElementById('sales-chart').getContext('2d');
    new Chart(salesChartCtx, {
        type: 'line',
        data: {
            labels: salesByMonth.labels,
            datasets: [{
                label: 'Ventas Mensuales',
                data: salesByMonth.data,
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
