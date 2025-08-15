import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// تحميل الاقتباس كصورة
function downloadQuoteAsImage(quoteId) {
    // يمكن استخدام مكتبة html2canvas لتحويل الاقتباس إلى صورة
    const quoteElement = document.querySelector(`[data-quote-id="${quoteId}"]`);
    if (quoteElement) {
        html2canvas(quoteElement).then(canvas => {
            const link = document.createElement('a');
            link.download = `quote-${quoteId}.png`;
            link.href = canvas.toDataURL();
            link.click();
        });
    }
}

// الإبلاغ عن اقتباس
function reportQuote(quoteId) {
    if (confirm('هل أنت متأكد من رغبتك في الإبلاغ عن هذا الاقتباس؟')) {
        fetch(`/quotes/${quoteId}/report`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        }).then(response => {
            if (response.ok) {
                alert('تم الإبلاغ بنجاح');
            }
        });
    }
}

// حظر مستخدم
function blockUser(userId) {
    if (confirm('هل أنت متأكد من رغبتك في حظر هذا المستخدم؟')) {
        fetch(`/users/${userId}/block`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        }).then(response => {
            if (response.ok) {
                alert('تم حظر المستخدم بنجاح');
                location.reload();
            }
        });
    }
}
