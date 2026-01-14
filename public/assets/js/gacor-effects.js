/**
 * GACOR Effects - Advanced JavaScript Effects for APAO Landing Page
 */

// Typing Effect for Hero Title
function initTypingEffect() {
    const text = "APAO Polibatam";
    const element = document.querySelector('.typing-animation');
    if (!element) return;
    
    element.innerHTML = '';
    let i = 0;
    
    function typeWriter() {
        if (i < text.length) {
            element.innerHTML += text.charAt(i);
            i++;
            setTimeout(typeWriter, 150);
        } else {
            // Add blinking cursor
            setTimeout(() => {
                element.style.borderRight = '3px solid transparent';
                setTimeout(() => {
                    element.style.borderRight = '3px solid #fff';
                }, 500);
            }, 1000);
        }
    }
    
    setTimeout(typeWriter, 1000);
}

// 3D Tilt Effect for Cards
function init3DTilt() {
    const cards = document.querySelectorAll('.modern-card');
    
    cards.forEach(card => {
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            
            const rotateX = (y - centerY) / 10;
            const rotateY = (centerX - x) / 10;
            
            card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.05, 1.05, 1.05)`;
        });
        
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)';
        });
    });
}

// Glitch Effect for Text
function initGlitchEffect() {
    const glitchElements = document.querySelectorAll('.glitch-text');
    
    glitchElements.forEach(element => {
        const text = element.textContent;
        
        setInterval(() => {
            if (Math.random() > 0.95) {
                element.textContent = text.split('').map(char => 
                    Math.random() > 0.9 ? String.fromCharCode(33 + Math.floor(Math.random() * 94)) : char
                ).join('');
                
                setTimeout(() => {
                    element.textContent = text;
                }, 100);
            }
        }, 100);
    });
}

// Neon Glow Pulse Effect
function initNeonPulse() {
    const neonElements = document.querySelectorAll('.neon-button, .gradient-text');
    
    neonElements.forEach(element => {
        setInterval(() => {
            element.style.filter = `brightness(${1 + Math.random() * 0.3})`;
            setTimeout(() => {
                element.style.filter = 'brightness(1)';
            }, 100);
        }, 2000 + Math.random() * 3000);
    });
}

// Floating Particles Effect
function createFloatingParticles() {
    const container = document.querySelector('.hero-section');
    if (!container) return;
    
    for (let i = 0; i < 20; i++) {
        const particle = document.createElement('div');
        particle.className = 'floating-particle';
        particle.style.cssText = `
            position: absolute;
            width: ${2 + Math.random() * 4}px;
            height: ${2 + Math.random() * 4}px;
            background: rgba(255, 255, 255, ${0.3 + Math.random() * 0.7});
            border-radius: 50%;
            left: ${Math.random() * 100}%;
            top: ${Math.random() * 100}%;
            animation: floatParticle ${10 + Math.random() * 20}s linear infinite;
            pointer-events: none;
            z-index: 1;
        `;
        
        container.appendChild(particle);
    }
    
    // Add CSS animation for particles
    if (!document.querySelector('#particle-styles')) {
        const style = document.createElement('style');
        style.id = 'particle-styles';
        style.textContent = `
            @keyframes floatParticle {
                0% {
                    transform: translateY(100vh) rotate(0deg);
                    opacity: 0;
                }
                10% {
                    opacity: 1;
                }
                90% {
                    opacity: 1;
                }
                100% {
                    transform: translateY(-100px) rotate(360deg);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    }
}

// Sound Effects (Optional)
function initSoundEffects() {
    // Create audio context for button hover sounds
    let audioContext;
    
    try {
        audioContext = new (window.AudioContext || window.webkitAudioContext)();
    } catch (e) {
        console.log('Web Audio API not supported');
        return;
    }
    
    function playHoverSound() {
        if (!audioContext) return;
        
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();
        
        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);
        
        oscillator.frequency.setValueAtTime(800, audioContext.currentTime);
        oscillator.frequency.exponentialRampToValueAtTime(400, audioContext.currentTime + 0.1);
        
        gainNode.gain.setValueAtTime(0.1, audioContext.currentTime);
        gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.1);
        
        oscillator.start(audioContext.currentTime);
        oscillator.stop(audioContext.currentTime + 0.1);
    }
    
    // Add sound to buttons
    document.querySelectorAll('.glow-button, .neon-button').forEach(button => {
        button.addEventListener('mouseenter', playHoverSound);
    });
}

// Matrix Digital Rain Effect
function initMatrixRain() {
    const canvas = document.createElement('canvas');
    canvas.id = 'matrix-canvas';
    canvas.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 0;
        opacity: 0.1;
    `;
    
    document.body.appendChild(canvas);
    
    const ctx = canvas.getContext('2d');
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
    
    const matrix = "ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789@#$%^&*()*&^%+-/~{[|`]}";
    const matrixArray = matrix.split("");
    
    const fontSize = 10;
    const columns = canvas.width / fontSize;
    const drops = [];
    
    for (let x = 0; x < columns; x++) {
        drops[x] = 1;
    }
    
    function drawMatrix() {
        ctx.fillStyle = 'rgba(0, 0, 0, 0.04)';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        
        ctx.fillStyle = '#00ff00';
        ctx.font = fontSize + 'px monospace';
        
        for (let i = 0; i < drops.length; i++) {
            const text = matrixArray[Math.floor(Math.random() * matrixArray.length)];
            ctx.fillText(text, i * fontSize, drops[i] * fontSize);
            
            if (drops[i] * fontSize > canvas.height && Math.random() > 0.975) {
                drops[i] = 0;
            }
            drops[i]++;
        }
    }
    
    setInterval(drawMatrix, 35);
    
    // Resize canvas on window resize
    window.addEventListener('resize', () => {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
    });
}

// Cyberpunk Loading Effect
function initCyberpunkLoader() {
    const loader = document.createElement('div');
    loader.id = 'cyberpunk-loader';
    loader.innerHTML = `
        <div class="loader-content">
            <div class="loader-text">INITIALIZING APAO SYSTEM</div>
            <div class="loader-bar">
                <div class="loader-progress"></div>
            </div>
            <div class="loader-status">Loading quantum encryption...</div>
        </div>
    `;
    
    loader.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(45deg, #000, #001122);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10000;
        color: #00ff00;
        font-family: 'Courier New', monospace;
    `;
    
    const style = document.createElement('style');
    style.textContent = `
        .loader-content {
            text-align: center;
        }
        
        .loader-text {
            font-size: 1.5rem;
            margin-bottom: 2rem;
            animation: loaderGlow 2s ease-in-out infinite alternate;
        }
        
        .loader-bar {
            width: 300px;
            height: 4px;
            background: rgba(0, 255, 0, 0.2);
            margin: 0 auto 1rem;
            overflow: hidden;
        }
        
        .loader-progress {
            height: 100%;
            background: linear-gradient(90deg, transparent, #00ff00, transparent);
            animation: loaderProgress 2s ease-in-out;
            transform: translateX(-100%);
        }
        
        .loader-status {
            font-size: 0.9rem;
            opacity: 0.7;
        }
        
        @keyframes loaderGlow {
            0% { text-shadow: 0 0 5px #00ff00; }
            100% { text-shadow: 0 0 20px #00ff00, 0 0 30px #00ff00; }
        }
        
        @keyframes loaderProgress {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(400%); }
        }
    `;
    
    document.head.appendChild(style);
    document.body.appendChild(loader);
    
    // Remove loader after animation
    setTimeout(() => {
        loader.style.opacity = '0';
        loader.style.transition = 'opacity 0.5s ease';
        setTimeout(() => {
            if (loader.parentNode) {
                loader.remove();
            }
        }, 500);
    }, 3000);
}

// Initialize all effects
document.addEventListener('DOMContentLoaded', function() {
    // Show cyberpunk loader first
    initCyberpunkLoader();
    
    // Initialize other effects after loader
    setTimeout(() => {
        initTypingEffect();
        init3DTilt();
        initGlitchEffect();
        initNeonPulse();
        createFloatingParticles();
        initSoundEffects();
        initMatrixRain();
    }, 3500);
});

// Export functions for external use
window.GacorEffects = {
    initTypingEffect,
    init3DTilt,
    initGlitchEffect,
    initNeonPulse,
    createFloatingParticles,
    initSoundEffects,
    initMatrixRain,
    initCyberpunkLoader
};