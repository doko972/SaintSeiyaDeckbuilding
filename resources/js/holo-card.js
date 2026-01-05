document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.interactive .holo-card');
    
    cards.forEach(card => {
        let rect;
        let isHovering = false;
        
        card.addEventListener('mouseenter', function(e) {
            isHovering = true;
            rect = card.getBoundingClientRect();
            card.classList.add('tilting');
        });
        
        card.addEventListener('mousemove', function(e) {
            if (!isHovering) return;
            
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            
            const percentX = (x - centerX) / centerX;
            const percentY = (y - centerY) / centerY;
            
            const maxTilt = 15;
            const tiltX = -percentY * maxTilt;
            const tiltY = percentX * maxTilt;
            
            card.style.transform = `perspective(1000px) rotateX(${tiltX}deg) rotateY(${tiltY}deg) scale(1.02)`;
            
            // Déplacer les effets holo
            const holoOverlay = card.querySelector('.holo-overlay');
            const holoSparkle = card.querySelector('.holo-sparkle');
            
            if (holoOverlay) {
                const bgPosX = 50 + (percentX * 30);
                const bgPosY = 50 + (percentY * 30);
                holoOverlay.style.backgroundPosition = `${bgPosX}% ${bgPosY}%`;
            }
            
            if (holoSparkle) {
                const sparklePosX = 50 + (percentX * 10);
                const sparklePosY = 50 + (percentY * 10);
                holoSparkle.style.backgroundPosition = `${sparklePosX}% ${sparklePosY}%`;
            }
        });
        
        card.addEventListener('mouseleave', function(e) {
            isHovering = false;
            card.classList.remove('tilting');
            card.style.transform = '';
            
            const holoOverlay = card.querySelector('.holo-overlay');
            const holoSparkle = card.querySelector('.holo-sparkle');
            
            if (holoOverlay) {
                holoOverlay.style.backgroundPosition = '';
            }
            if (holoSparkle) {
                holoSparkle.style.backgroundPosition = '';
            }
        });
        
        // Support tactile
        card.addEventListener('touchmove', function(e) {
            if (!rect) rect = card.getBoundingClientRect();
            
            const touch = e.touches[0];
            const x = touch.clientX - rect.left;
            const y = touch.clientY - rect.top;
            
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            
            const percentX = Math.max(-1, Math.min(1, (x - centerX) / centerX));
            const percentY = Math.max(-1, Math.min(1, (y - centerY) / centerY));
            
            const maxTilt = 10;
            const tiltX = -percentY * maxTilt;
            const tiltY = percentX * maxTilt;
            
            card.style.transform = `perspective(1000px) rotateX(${tiltX}deg) rotateY(${tiltY}deg)`;
        });
        
        card.addEventListener('touchend', function() {
            card.style.transform = '';
        });
    });
});