/**
* @param {string} content HTML or text content for modal body
* */

function createModal(content, options = {}) {
    const modalWrapper = document.createElement('div');
    const modalOffsetContainer = document.createElement('div');
    const modalBackDrop = document.createElement('div');
    const modalElement = document.createElement('div');
    const arrowRight = document.createElement('div');
    const arrowRight_inner = document.createElement('div');
    const arrowLeft = document.createElement('div');
    const arrowLeft_inner = document.createElement('div');
    const closeButton = document.createElement('div');

    closeButton.innerHTML = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <line x1="18" y1="6" x2="6" y2="18"/>
        <line x1="6" y1="6" x2="18" y2="18"/>
    </svg>`;
    let contentElement = modalElement;

    if (options.isolated) {
        contentElement = modalElement.attachShadow({ mode: 'open' });
    }
    
    arrowRight.classList.add('modal-arrow-right');
    arrowRight_inner.classList.add('modal-arrow-right__inner');
    arrowLeft.classList.add('modal-arrow-left');
    arrowLeft_inner.classList.add('modal-arrow-left__inner');
    closeButton.classList.add('modal-close');

    modalWrapper.setAttribute('style', `
        position: fixed;
        width: 100%;
        height: 100vh;
        top: 0;
        left: 0;
        z-index: 2;
        display: none;
    `);
    modalOffsetContainer.setAttribute('style', `
        position: relative;
        width: 100%;
        height: 100vh;
        top: 0;
        display: flex;
        justify-content: center;
        align-items: center;
    `);

    modalBackDrop.setAttribute('style', `
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100vh;
        background-color: rgba(255,255,255,.93); 
        z-index: 2;
    `);
    modalElement.classList.add('modal-element');
    modalElement.setAttribute('style', `
        width: 80%;
        max-width: 780px;
        z-index: 3;
        border-radius: 8px;
        overflow: hidden;
        height: 80vh;
    `);

    modalWrapper.append(modalOffsetContainer);
    modalOffsetContainer.append(modalBackDrop);
    modalOffsetContainer.append(modalElement);
    document.body.append(modalWrapper);
    
    modalWrapper.append(arrowRight);
    arrowRight.append(arrowRight_inner);
    modalWrapper.append(arrowLeft);
    arrowLeft.append(arrowLeft_inner);
    modalWrapper.append(closeButton);

    contentElement.innerHTML = content;

    function open() {
        modalWrapper.style.display = '';
        document.body.dispatchEvent(new CustomEvent('modal:open', { detail: modalWrapper }));
    }

    function close() {
        modalWrapper.style.display = 'none';
        document.body.dispatchEvent(new CustomEvent('modal:close', { detail: modalWrapper }));
    }
    function setContent(ct) {
        contentElement.innerHTML = ct;
        setStyles();
    }
    let stylesSet = '';
    function setStyles(st) {
        stylesSet = stylesSet || st;
        const styleNode = document.createElement("style");
        styleNode.textContent = stylesSet;
        contentElement.appendChild(styleNode);
    }
    function _arrowRightClick() {
        arrowRightClickCallbacks.forEach(fn => fn.apply(this, arguments));
    }
    function _arrowLeftClick() {
        arrowLeftClickCallbacks.forEach(fn => fn.apply(this, arguments));
    }
    let arrowRightClickCallbacks = [];
    let arrowLeftClickCallbacks = [];
    function arrowRightClick(fn) {
        arrowRightClickCallbacks.push(fn);
    }
    function arrowLeftClick(fn) {
        arrowLeftClickCallbacks.push(fn);
    }

    function hideArrowRight() {
        arrowRight.style.display = 'none';
    }
    
    function hideArrowLeft() {
        arrowLeft.style.display = 'none';
    }

    function showArrowRight() {
        arrowRight.style.display = 'block';
    }
    
    function showArrowLeft() {
        arrowLeft.style.display = 'block';
    }

    function showArrows() {
        showArrowRight();
        showArrowLeft();
    }

    function hideArrows() {
        hideArrowRight();
        hideArrowLeft();
    }

    closeButton.addEventListener('click', close);
    modalBackDrop.addEventListener('click', close);
    arrowRight.addEventListener('click', _arrowRightClick);
    arrowLeft.addEventListener('click', _arrowLeftClick);

    return {
        modalElement,
        contentElement,
        open, close,
        setContent,
        setStyles,
        arrowRightClick,
        arrowLeftClick,
        hideArrowRight,
        hideArrowLeft,
        showArrows,
        hideArrows
    };
}
document.addEventListener('DOMContentLoaded', function () {
    window.yearsSlider = {
        slider: document.querySelector('.years-slider'),
        nextSlide: function () {
            this.slider.scrollTo({ left: this.slider.scrollLeft + (this.slider.clientWidth / 4), behavior: 'smooth' });
        },
        prevSlide: function () {
            this.slider.scrollTo({ left: this.slider.scrollLeft - (this.slider.clientWidth / 4), behavior: 'smooth' });
        }
    };

    const galleryLinks = [...document.querySelectorAll('.exhibit-gallery-item a, #collection-items a')];
    let currentItem, subGalleryLinks;

    const shadowStyles = `
    h1 {
        font: normal normal normal 40px/42px EB Garamond;
        margin-top: 50px;
    }
    div, p {
        font: normal normal normal 18px/28px EB Garamond;
    }
    img {
        width: auto;
        height: 100%;
    }
    figure {
        margin: 0;
    }
    #content {
        height: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .item-content {
        overflow: auto;
    }
    .item.hentry {
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    .item-img {
        height: 80%;
        display: flex;
        justify-content: center;
        flex-direction: column;
    }
    .item-img a {
        display: flex;
        height: 90%;
        justify-content: center;
    }
    `;
    const modal = createModal('<div style="width: 100%; height: 100%; display: flex; justify-content: center; align-items: center;"><p style="font-size: 28px;"> Loading... </p><div>', { isolated: 1 });
    modal.setStyles(shadowStyles);

    document.body.addEventListener('modal:close', e => {
        modal.setContent('<div style="width: 100%; height: 100%; display: flex; justify-content: center; align-items: center;"><p style="font-size: 28px;"> Loading... </p><div>');
    });

    modal.arrowLeftClick(() => {
        const prevItem = currentItem;
        const links = (subGalleryLinks || galleryLinks).map(a => a.href ? new URL(a.href).pathname : a);
        const link = currentItem.href ? new URL(currentItem.href).pathname : currentItem;
        currentItem = links[links.indexOf(link) - 1] ? links[links.indexOf(link) - 1] : currentItem;
        if (prevItem !== currentItem) {
            modalLoadCurrentItem();
        }
    });
    modal.arrowRightClick(() => {
        const prevItem = currentItem;
        const links = (subGalleryLinks || galleryLinks).map(a => a.href ? new URL(a.href).pathname : a);
        const link = currentItem.href ? new URL(currentItem.href).pathname : currentItem;
        currentItem = links[links.indexOf(link) + 1] ? links[links.indexOf(link) + 1] : currentItem;
        if (prevItem !== currentItem) {
            modalLoadCurrentItem();
        }
    });
    
    galleryLinks.forEach(el => {
        el.addEventListener('click', async e => {
            e.preventDefault();
            modal.open();
            currentItem = el;
            try {
                subGalleryLinks = JSON.parse(el.dataset.galleryLinks);
            } catch (err) {
                subGalleryLinks = null;
            }
            modalLoadCurrentItem();
        });

    });

    async function modalLoadCurrentItem() {
        const links = (subGalleryLinks || galleryLinks).map(a => a.href ? new URL(a.href).pathname : a);
        const link = currentItem.href ? new URL(currentItem.href).pathname : currentItem;
        const first = links.indexOf(link) == 0;
        const last = links.indexOf(link) == links.length - 1;
        
        if (links.length == 1) {
            modal.hideArrows();
        } else {
            modal.showArrows();
            if (first) {
                modal.hideArrowLeft();
            } else if (last) {
                modal.hideArrowRight();
            }
        }

        const html = await fetch(link).then(r => r.text());
        const tm = document.createElement('template');
        tm.innerHTML = html;
        const itemPageContent = tm.content.querySelector('#content').outerHTML;
        modal.setContent(itemPageContent);
    }

});

document.addEventListener('DOMContentLoaded', () => {
    const searchForm = document.querySelector('#search-form');
    searchForm.addEventListener('submit', e => {
        const isMobile = searchForm?.closest('.nav')?.matches('.mobile')
        if(searchForm.parentElement.matches('.search-hidden') && !isMobile) {
            e.preventDefault();
            searchForm.parentElement.classList.remove('search-hidden');
        }
    });

    document.addEventListener('click', ev => {
        if ((ev.path && !ev.path.some(e => e.matches && e.matches('#search-form')) && !searchForm?.parentElement.classList.contains('search-hidden'))) {
            searchForm.parentElement.classList.add('search-hidden');
        }
    });
    function resizeCb() {

        if(window.innerWidth < 900) {
            document.querySelector('.mobile-nav-panel.nav')?.append(searchForm?.parentElement);
        } else {

        }
    }
    window.addEventListener('resize', resizeCb);
    
    resizeCb();

    // Adjust max height
    [...document.querySelectorAll('ul.nav > li')].forEach(el => {
        el.addEventListener('mouseover', e => {
            const subMenu = el.querySelector('ul');
            if (subMenu) {
                el.setAttribute('style', `--height: ${subMenu.scrollHeight}px;`);
            }
        });
        
    });

    [...document.querySelectorAll('figure')].forEach(el => {
        const caption = el.querySelector('figcaption');
        if (caption) {
            el.setAttribute('style', `--caption-size: ${caption.scrollHeight}px;`);
        }
    });
});