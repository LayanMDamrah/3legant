const params = new URLSearchParams(window.location.search);

document.getElementById('orderCodeValue').textContent = "#" + (params.get('code') || 'N/A');
document.getElementById('orderDateValue').textContent = params.get('date') || 'N/A';
document.getElementById('orderTotalValue').textContent = "$" + (params.get('total') || '0.00');
document.getElementById('orderPaymentValue').textContent = params.get('payment') || 'N/A';
