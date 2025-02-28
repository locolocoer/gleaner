// 页码缓存处理
const storageKey = 'carousel_current_page';
const urlParams = new URLSearchParams(window.location.search);
const currentPageFromURL = urlParams.get('page');


if(!localStorage.getItem(storageKey)){
    localStorage.setItem(storageKey, 1);
    window.location.href = '?page=1';
}
// 如果URL中没有页码参数，且localStorage中有存储的页码，则重定向
if (!currentPageFromURL && localStorage.getItem(storageKey)) {
    const savedPage = localStorage.getItem(storageKey);
    if (!currentPageFromURL) {
        window.location.href = '?page=' + savedPage;
    } 
}


document.addEventListener('DOMContentLoaded', function() {
    const slides = document.querySelectorAll('.carousel-slide');
    const prevBtn = document.querySelector('.prev-btn');
    const nextBtn = document.querySelector('.next-btn');
    const playPauseBtn = document.querySelector('.play-pause-btn');
    const progressBar = document.querySelector('.progress-bar');
    const slideCounter = document.querySelector('.slide-counter .current');
    const totalSlides = slides.length;
    
    let currentSlide = 0;
    let isAnimating = false;
    let isPlaying = true;
    let slideInterval;
    let progressInterval;
    const slideDuration = 5000; // 轮播间隔时间


    // 更新进度条
    function updateProgress(reset = false) {
        if (reset) {
            progressBar.style.width = '0%';
            return;
        }
        // console.log("updateProgress");
        let startTime = Date.now();
        clearInterval(progressInterval);
        
        progressBar.style.width = '0%';
        progressBar.classList.remove('paused');
        
        progressInterval = setInterval(() => {
            const elapsed = Date.now() - startTime;
            
            const progress = (elapsed / slideDuration) * 100;
            // console.log(progress);
            if (progress <= 100) {
                progressBar.style.width = progress + '%';
            } else {
                clearInterval(progressInterval);
            }
        }, 16);
    }

    // 开始自动播放
    function startAutoPlay() {
        stopAutoPlay();
        if (isPlaying) {
            updateProgress();
            slideInterval = setInterval(() => {
                showSlide(currentSlide + 1, 'next');
                // console.log("showSlide")
                // console.log(Date.now());
            }, slideDuration);
        }
    }

    // 停止自动播放
    function stopAutoPlay() {
        if (slideInterval) {
            clearInterval(slideInterval);
            clearInterval(progressInterval);
            slideInterval = null;
            progressBar.classList.add('paused');
        }
    }

    // 更新播放/暂停按钮
    function updatePlayPauseButton() {
        const icon = playPauseBtn.querySelector('i');
        const text = playPauseBtn.querySelector('span');
        if (isPlaying) {
            icon.className = 'fas fa-pause';
            text.textContent = '暂停';
        } else {
            icon.className = 'fas fa-play';
            text.textContent = '播放';
        }
    }

    // 切换播放状态
    function togglePlayPause() {
        isPlaying = !isPlaying;
        updatePlayPauseButton();
        if (isPlaying) {
            progressBar.classList.remove('paused');
            startAutoPlay();
        } else {
            progressBar.classList.add('paused');
            stopAutoPlay();
        }
    }

    // 切换幻灯片
    function showSlide(n, direction = 'next') {
        if (isAnimating) return;
        isAnimating = true;
        
        //console.log(Date.now());
        updateProgress(true);
        if (isPlaying) {
            updateProgress();
        }
        const oldSlide = slides[currentSlide];
        currentSlide = (n + slides.length) % slides.length;
        const newSlide = slides[currentSlide];

        updateCounter(currentSlide);
        loadImage(newSlide);

        const nextIndex = (currentSlide + 1) % slides.length;
        loadImage(slides[nextIndex]);

        oldSlide.classList.add('moving');
        newSlide.classList.add('moving');

        if (direction === 'next') {
            newSlide.style.transform = 'translateX(100%)';
            newSlide.style.opacity = '0.3';
            
            void newSlide.offsetWidth;
            
            requestAnimationFrame(() => {
                oldSlide.style.transform = 'translateX(-100%)';
                oldSlide.style.opacity = '0';
                
                setTimeout(() => {
                    newSlide.style.transform = 'translateX(0)';
                    newSlide.style.opacity = '1';
                }, 200);
            });
        } else {
            newSlide.style.transform = 'translateX(-100%)';
            newSlide.style.opacity = '0.3';
            
            void newSlide.offsetWidth;
            
            requestAnimationFrame(() => {
                oldSlide.style.transform = 'translateX(100%)';
                oldSlide.style.opacity = '0';
                
                setTimeout(() => {
                    newSlide.style.transform = 'translateX(0)';
                    newSlide.style.opacity = '1';
                }, 200);
            });
        }
        
        setTimeout(() => {
            slides.forEach(slide => {
                slide.classList.remove('moving');
                if (slide !== newSlide) {
                    slide.style.transform = '';
                    slide.style.opacity = '';
                }
            });
            newSlide.classList.add('active');
            oldSlide.classList.remove('active');
            isAnimating = false;
            
        }, 1000);
    }

    // 波纹效果
    function createRipple(event) {
        const button = event.currentTarget;
        const ripple = document.createElement('span');
        const diameter = Math.max(button.clientWidth, button.clientHeight);
        const radius = diameter / 2;

        const rect = button.getBoundingClientRect();
        const x = event.clientX - rect.left - radius;
        const y = event.clientY - rect.top - radius;

        ripple.style.width = ripple.style.height = `${diameter}px`;
        ripple.style.left = `${x}px`;
        ripple.style.top = `${y}px`;
        ripple.className = 'ripple';

        const oldRipple = button.querySelector('.ripple');
        if (oldRipple) {
            oldRipple.remove();
        }

        button.appendChild(ripple);

        ripple.addEventListener('animationend', () => {
            ripple.remove();
        });
    }

    // 加载图片
    function loadImage(slide) {
        const img = slide.querySelector('img');
        if (img && img.dataset.src && !img.src.includes(img.dataset.src)) {
            img.src = img.dataset.src;
        }
    }

    // 更新计数器
    function updateCounter(index) {
        slideCounter.textContent = index + 1;
    }

    // 事件监听器
    prevBtn.addEventListener('click', (e) => {
        createRipple(e);
        stopAutoPlay();
        showSlide(currentSlide - 1, 'prev');
        startAutoPlay();
    });

    nextBtn.addEventListener('click', (e) => {
        createRipple(e);
        stopAutoPlay();
        showSlide(currentSlide + 1, 'next');
        startAutoPlay();
    });

    playPauseBtn.addEventListener('click', togglePlayPause);

    // 初始化
    loadImage(slides[0]);
    // console.log(slides[0].querySelector('img').src);
    slides[0].classList.add('active');
            setTimeout(() => {
                slides[0].classList.remove('initial');
            }, 100);
            updateCounter(0);
            startAutoPlay();
    
}); 