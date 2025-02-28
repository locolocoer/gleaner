document.addEventListener('DOMContentLoaded', function() {
    const slides = document.querySelectorAll('.carousel-slide');
    const prevBtn = document.querySelector('.prev-btn');
    const nextBtn = document.querySelector('.next-btn');
    const playPauseBtn = document.querySelector('.play-pause-btn');
    const progressBar = document.querySelector('.progress-bar');
    const slideCounter = document.querySelector('.slide-counter .current');
    const totalSlides = slides.length;
    
    // 使用不同的storageKey
    const storageKey = 'articles_page_current';
    const urlParams = new URLSearchParams(window.location.search);
    const currentPageFromURL = urlParams.get('page');
    
    // 只在页面首次加载时执行一次重定向
    if (!currentPageFromURL && !window.pageRedirected) {
        const savedPage = localStorage.getItem(storageKey);
        if (savedPage && savedPage !== '1') {
            window.pageRedirected = true;
            window.location.replace('?page=' + savedPage);
            return;
        }
    }

    let currentSlide = 0;
    let isAnimating = false;
    let isPlaying = true;
    let slideInterval;
    let progressInterval;
    const slideDuration = 3000;

    // 从这里开始复制carousel.js中的所有函数和事件监听器
    function updateProgress(reset = false) {
        if (reset) {
            progressBar.style.width = '0%';
            return;
        }
        
        let startTime = Date.now();
        clearInterval(progressInterval);
        
        progressBar.style.width = '0%';
        progressBar.classList.remove('paused');
        
        progressInterval = setInterval(() => {
            const elapsed = Date.now() - startTime;
            const progress = (elapsed / slideDuration) * 100;
            
            if (progress <= 100) {
                progressBar.style.width = progress + '%';
            } else {
                clearInterval(progressInterval);
            }
        }, 16);
    }

    // ... 复制其他所有函数 ...
    // 包括 startAutoPlay, stopAutoPlay, updatePlayPauseButton, togglePlayPause, 
    // showSlide, createRipple, loadImage, updateCounter 等所有函数

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
    slides[0].querySelector('img').addEventListener('load', function() {
        if (this.src === this.dataset.src) {
            slides[0].classList.add('active');
            slides[0].style.opacity = '1';
            slides[0].style.visibility = 'visible';
            updateCounter(0);
            startAutoPlay();
        }
    });
}); 