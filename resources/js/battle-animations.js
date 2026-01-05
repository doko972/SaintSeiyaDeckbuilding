/**
 * ===========================================
 * SAINT SEIYA DECKBUILDING - BATTLE ANIMATIONS
 * ===========================================
 * Système d'animations pour les combats PvP
 */

class BattleAnimations {
    constructor() {
        this.particleContainer = this.createParticleContainer();
    }

    /**
     * Crée le container de particules
     */
    createParticleContainer() {
        let container = document.getElementById('particleContainer');
        if (!container) {
            container = document.createElement('div');
            container.id = 'particleContainer';
            container.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                pointer-events: none;
                z-index: 9999;
            `;
            document.body.appendChild(container);
        }
        return container;
    }

    /**
     * Animation : Jouer une carte depuis la main
     */
    async playCardAnimation(cardElement, targetPosition) {
        return new Promise((resolve) => {
            // Clone de la carte pour l'animation
            const clone = cardElement.cloneNode(true);
            clone.style.position = 'fixed';
            clone.style.zIndex = '1000';
            
            const startRect = cardElement.getBoundingClientRect();
            clone.style.left = startRect.left + 'px';
            clone.style.top = startRect.top + 'px';
            clone.style.width = startRect.width + 'px';
            clone.style.height = startRect.height + 'px';
            
            document.body.appendChild(clone);

            // Cacher l'original
            cardElement.style.opacity = '0';

            // Animation
            setTimeout(() => {
                clone.style.transition = 'all 0.6s cubic-bezier(0.34, 1.56, 0.64, 1)';
                clone.style.left = targetPosition.x + 'px';
                clone.style.top = targetPosition.y + 'px';
                clone.style.transform = 'scale(1.1) rotateY(360deg)';
                
                // Effet glow
                clone.style.boxShadow = '0 0 30px rgba(16, 185, 129, 0.8)';
            }, 50);

            setTimeout(() => {
                clone.style.transform = 'scale(1)';
                this.createSparkles(targetPosition.x + 65, targetPosition.y + 80, '#10B981');
            }, 400);

            setTimeout(() => {
                clone.remove();
                cardElement.style.opacity = '1';
                resolve();
            }, 800);
        });
    }

    /**
     * Animation : Attaque d'une carte vers une cible
     */
    async attackAnimation(attackerElement, targetElement, attackData) {
        return new Promise(async (resolve) => {
            // Clone pour l'animation
            const clone = attackerElement.cloneNode(true);
            clone.style.position = 'fixed';
            clone.style.zIndex = '1001';
            
            const startRect = attackerElement.getBoundingClientRect();
            const targetRect = targetElement.getBoundingClientRect();
            
            clone.style.left = startRect.left + 'px';
            clone.style.top = startRect.top + 'px';
            clone.style.width = startRect.width + 'px';
            clone.style.height = startRect.height + 'px';
            
            document.body.appendChild(clone);

            // Phase 1 : La carte fonce vers la cible
            setTimeout(() => {
                clone.style.transition = 'all 0.3s ease-in';
                clone.style.left = (targetRect.left - 20) + 'px';
                clone.style.top = (targetRect.top - 20) + 'px';
                clone.style.transform = 'scale(1.2) rotate(-5deg)';
            }, 50);

            // Phase 2 : Impact
            setTimeout(() => {
                // Shake de la cible
                this.shakeElement(targetElement);
                
                // Particules d'impact
                const impactX = targetRect.left + targetRect.width / 2;
                const impactY = targetRect.top + targetRect.height / 2;
                this.createImpactParticles(impactX, impactY, attackData.element);
                
                // Flash
                this.flashElement(targetElement, '#EF4444');
                
                // Son (si disponible)
                this.playSound('impact');
            }, 350);

            // Phase 3 : Retour de l'attaquant
            setTimeout(() => {
                clone.style.transition = 'all 0.2s ease-out';
                clone.style.left = startRect.left + 'px';
                clone.style.top = startRect.top + 'px';
                clone.style.transform = 'scale(1) rotate(0deg)';
            }, 450);

            // Nettoyage
            setTimeout(() => {
                clone.remove();
                resolve();
            }, 700);
        });
    }

    /**
     * Animation : Afficher les dégâts
     */
    showDamage(targetElement, damage, type = 'damage') {
        const rect = targetElement.getBoundingClientRect();
        const x = rect.left + rect.width / 2;
        const y = rect.top + rect.height / 2;

        const damageText = document.createElement('div');
        damageText.textContent = type === 'heal' ? `+${damage}` : `-${damage}`;
        damageText.style.cssText = `
            position: fixed;
            left: ${x}px;
            top: ${y}px;
            font-size: 2rem;
            font-weight: 900;
            color: ${type === 'heal' ? '#10B981' : '#EF4444'};
            text-shadow: 0 0 10px rgba(0, 0, 0, 0.8);
            z-index: 2000;
            pointer-events: none;
            transform: translate(-50%, -50%);
        `;
        
        document.body.appendChild(damageText);

        // Animation d'envol
        setTimeout(() => {
            damageText.style.transition = 'all 1s ease-out';
            damageText.style.top = (y - 100) + 'px';
            damageText.style.opacity = '0';
            damageText.style.transform = 'translate(-50%, -50%) scale(1.5)';
        }, 50);

        setTimeout(() => damageText.remove(), 1100);
    }

    /**
     * Animation : Shake (tremblement)
     */
    shakeElement(element, intensity = 10) {
        const keyframes = [
            { transform: 'translateX(0)' },
            { transform: `translateX(-${intensity}px) rotate(-2deg)` },
            { transform: `translateX(${intensity}px) rotate(2deg)` },
            { transform: `translateX(-${intensity}px) rotate(-1deg)` },
            { transform: `translateX(${intensity}px) rotate(1deg)` },
            { transform: 'translateX(0) rotate(0deg)' },
        ];

        element.animate(keyframes, {
            duration: 400,
            easing: 'ease-in-out'
        });
    }

    /**
     * Animation : Flash de couleur
     */
    flashElement(element, color = '#FFFFFF') {
        const overlay = document.createElement('div');
        overlay.style.cssText = `
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: ${color};
            opacity: 0.6;
            border-radius: inherit;
            pointer-events: none;
            z-index: 10;
        `;
        
        element.style.position = 'relative';
        element.appendChild(overlay);

        setTimeout(() => {
            overlay.style.transition = 'opacity 0.3s';
            overlay.style.opacity = '0';
        }, 50);

        setTimeout(() => overlay.remove(), 400);
    }

    /**
     * Particules d'impact selon l'élément
     */
    createImpactParticles(x, y, element = 'fire') {
        const colors = {
            fire: ['#EF4444', '#F59E0B', '#FCD34D'],
            ice: ['#06B6D4', '#3B82F6', '#FFFFFF'],
            thunder: ['#EAB308', '#FBBF24', '#FFFFFF'],
            water: ['#3B82F6', '#60A5FA', '#93C5FD'],
            light: ['#FCD34D', '#FBBF24', '#FFFFFF'],
            darkness: ['#1F2937', '#4B5563', '#6B7280'],
            default: ['#EF4444', '#F97316', '#FBBF24']
        };

        const particleColors = colors[element] || colors.default;
        const particleCount = 20;

        for (let i = 0; i < particleCount; i++) {
            this.createParticle(x, y, particleColors[Math.floor(Math.random() * particleColors.length)]);
        }
    }

    /**
     * Créer une particule individuelle
     */
    createParticle(x, y, color) {
        const particle = document.createElement('div');
        const size = Math.random() * 8 + 4;
        const angle = Math.random() * Math.PI * 2;
        const velocity = Math.random() * 100 + 50;
        const vx = Math.cos(angle) * velocity;
        const vy = Math.sin(angle) * velocity;

        particle.style.cssText = `
            position: absolute;
            left: ${x}px;
            top: ${y}px;
            width: ${size}px;
            height: ${size}px;
            background: ${color};
            border-radius: 50%;
            pointer-events: none;
            box-shadow: 0 0 ${size * 2}px ${color};
        `;

        this.particleContainer.appendChild(particle);

        // Animation
        const startTime = Date.now();
        const duration = 800;

        const animate = () => {
            const elapsed = Date.now() - startTime;
            const progress = elapsed / duration;

            if (progress >= 1) {
                particle.remove();
                return;
            }

            const currentX = x + vx * progress;
            const currentY = y + vy * progress + (progress * progress * 200); // Gravité
            const opacity = 1 - progress;

            particle.style.left = currentX + 'px';
            particle.style.top = currentY + 'px';
            particle.style.opacity = opacity;
            particle.style.transform = `scale(${1 - progress * 0.5})`;

            requestAnimationFrame(animate);
        };

        requestAnimationFrame(animate);
    }

    /**
     * Sparkles (étincelles)
     */
    createSparkles(x, y, color = '#FFD700') {
        for (let i = 0; i < 12; i++) {
            setTimeout(() => {
                const sparkle = document.createElement('div');
                const angle = (Math.PI * 2 * i) / 12;
                const distance = 40;
                const targetX = x + Math.cos(angle) * distance;
                const targetY = y + Math.sin(angle) * distance;

                sparkle.style.cssText = `
                    position: absolute;
                    left: ${x}px;
                    top: ${y}px;
                    width: 6px;
                    height: 6px;
                    background: ${color};
                    border-radius: 50%;
                    pointer-events: none;
                    box-shadow: 0 0 10px ${color};
                `;

                this.particleContainer.appendChild(sparkle);

                setTimeout(() => {
                    sparkle.style.transition = 'all 0.5s ease-out';
                    sparkle.style.left = targetX + 'px';
                    sparkle.style.top = targetY + 'px';
                    sparkle.style.opacity = '0';
                }, 50);

                setTimeout(() => sparkle.remove(), 600);
            }, i * 30);
        }
    }

    /**
     * Animation : Carte détruite (KO)
     */
    async destroyCardAnimation(cardElement) {
        return new Promise((resolve) => {
            // Flash rouge intense
            this.flashElement(cardElement, '#DC2626');

            // Shake violent
            this.shakeElement(cardElement, 15);

            // Particules de destruction
            setTimeout(() => {
                const rect = cardElement.getBoundingClientRect();
                const x = rect.left + rect.width / 2;
                const y = rect.top + rect.height / 2;
                
                for (let i = 0; i < 30; i++) {
                    this.createParticle(x, y, '#1F2937');
                }
            }, 200);

            // Disparition
            setTimeout(() => {
                cardElement.style.transition = 'all 0.5s ease-out';
                cardElement.style.transform = 'scale(0) rotate(180deg)';
                cardElement.style.opacity = '0';
            }, 400);

            setTimeout(() => {
                resolve();
            }, 1000);
        });
    }

    /**
     * Animation : Mise à jour de la barre de vie
     */
    updateHealthBar(hpBarElement, currentHp, maxHp, animated = true) {
        const percent = Math.max(0, Math.min(100, (currentHp / maxHp) * 100));
        
        if (animated) {
            hpBarElement.style.transition = 'width 0.5s ease-out, background 0.3s';
        } else {
            hpBarElement.style.transition = 'none';
        }

        hpBarElement.style.width = percent + '%';

        // Couleur selon le pourcentage
        if (percent > 50) {
            hpBarElement.style.background = 'linear-gradient(90deg, #22C55E, #16A34A)';
        } else if (percent > 25) {
            hpBarElement.style.background = 'linear-gradient(90deg, #EAB308, #F59E0B)';
        } else {
            hpBarElement.style.background = 'linear-gradient(90deg, #EF4444, #DC2626)';
        }
    }

    /**
     * Animation : Effet de buff
     */
    buffEffect(element, color = '#10B981') {
        const glow = document.createElement('div');
        glow.style.cssText = `
            position: absolute;
            top: -5px;
            left: -5px;
            right: -5px;
            bottom: -5px;
            background: ${color};
            opacity: 0;
            border-radius: inherit;
            pointer-events: none;
            z-index: -1;
            filter: blur(10px);
        `;

        element.style.position = 'relative';
        element.appendChild(glow);

        // Animation de pulsation
        let pulses = 0;
        const pulseInterval = setInterval(() => {
            glow.style.transition = 'opacity 0.3s';
            glow.style.opacity = '0.6';
            
            setTimeout(() => {
                glow.style.opacity = '0';
            }, 300);

            pulses++;
            if (pulses >= 3) {
                clearInterval(pulseInterval);
                setTimeout(() => glow.remove(), 600);
            }
        }, 600);
    }

    /**
     * Animation : Effet de debuff
     */
    debuffEffect(element) {
        this.buffEffect(element, '#6B7280');
        
        // Assombrissement temporaire
        const overlay = document.createElement('div');
        overlay.style.cssText = `
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            border-radius: inherit;
            pointer-events: none;
            z-index: 5;
        `;

        element.style.position = 'relative';
        element.appendChild(overlay);

        setTimeout(() => {
            overlay.style.transition = 'opacity 1s';
            overlay.style.opacity = '0';
        }, 1000);

        setTimeout(() => overlay.remove(), 2000);
    }

    /**
     * Son (placeholder - à implémenter si besoin)
     */
    playSound(soundName) {
        // TODO: Implémenter les sons si nécessaire
        // const audio = new Audio(`/sounds/${soundName}.mp3`);
        // audio.volume = 0.3;
        // audio.play();
    }

    /**
     * Animation : Pioche de carte
     */
    async drawCardAnimation(startPosition, endPosition) {
        return new Promise((resolve) => {
            const cardBack = document.createElement('div');
            cardBack.style.cssText = `
                position: fixed;
                left: ${startPosition.x}px;
                top: ${startPosition.y}px;
                width: 80px;
                height: 110px;
                background: linear-gradient(135deg, #7C3AED, #5B21B6);
                border: 2px solid rgba(255, 255, 255, 0.3);
                border-radius: 8px;
                z-index: 1002;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            `;

            document.body.appendChild(cardBack);

            setTimeout(() => {
                cardBack.style.transition = 'all 0.6s cubic-bezier(0.34, 1.56, 0.64, 1)';
                cardBack.style.left = endPosition.x + 'px';
                cardBack.style.top = endPosition.y + 'px';
                cardBack.style.transform = 'rotateY(180deg)';
            }, 50);

            setTimeout(() => {
                this.createSparkles(endPosition.x + 40, endPosition.y + 55, '#7C3AED');
                cardBack.remove();
                resolve();
            }, 700);
        });
    }
}

// Export global
window.BattleAnimations = new BattleAnimations();