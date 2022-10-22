/**
* @param {string} content HTML or text content for modal body
* */

function createModal(content, options = {}) {
    const modalWrapper = document.createElement('div');
    const modalOffsetContainer = document.createElement('div');
    const modalBackDrop = document.createElement('div');
    const modalElement = document.createElement('div');
    const arrowRight = document.createElement('div');
    const arrowLeft = document.createElement('div');
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
    arrowLeft.classList.add('modal-arrow-left');
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
        overflow-y: auto;
        height: 80vh;
    `);

    modalWrapper.append(modalOffsetContainer);
    modalOffsetContainer.append(modalBackDrop);
    modalOffsetContainer.append(modalElement);
    document.body.append(modalWrapper);
    
    modalWrapper.append(arrowRight);
    modalWrapper.append(arrowLeft);
    modalWrapper.append(closeButton);

    contentElement.innerHTML = content;

    function open() {
        modalWrapper.style.display = 'block';
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
        showArrows
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
    let currentItem;

    const shadowStyles = `
    h1 {
        font: normal normal normal 40px/42px EB Garamond;
        margin-top: 50px;
    }
    div, p {
        font: normal normal normal 18px/28px EB Garamond;
    }
    img {
        width: 100%;
    }
    figure {
        margin: 0;
    }`;
    const modal = createModal('<div style="width: 100%; height: 100%; display: flex; justify-content: center; align-items: center;"><p style="font-size: 28px;"> Loading... </p><div>', { isolated: 1 });
    modal.setStyles(shadowStyles);

    document.body.addEventListener('modal:close', e => {
        modal.setContent('<div style="width: 100%; height: 100%; display: flex; justify-content: center; align-items: center;"><p style="font-size: 28px;"> Loading... </p><div>');
    });

    modal.arrowLeftClick(() => {
        const prevItem = currentItem;
        currentItem = galleryLinks[galleryLinks.indexOf(currentItem) - 1] ? galleryLinks[galleryLinks.indexOf(currentItem) - 1] : currentItem;
        if (prevItem !== currentItem) {
            modalLoadCurrentItem();
        }
    });
    modal.arrowRightClick(() => {
        const prevItem = currentItem;
        currentItem = galleryLinks[galleryLinks.indexOf(currentItem) + 1] ? galleryLinks[galleryLinks.indexOf(currentItem) + 1] : currentItem;
        if (prevItem !== currentItem) {
            modalLoadCurrentItem();
        }
    });
    
    galleryLinks.forEach(el => {
        el.addEventListener('click', async e => {
            e.preventDefault();
            modal.open();
            currentItem = el;
            modalLoadCurrentItem();
        });

    });

    async function modalLoadCurrentItem() {
        const first = galleryLinks.indexOf(currentItem) == 0;
        const last = galleryLinks.indexOf(currentItem) == galleryLinks.length - 1;
        if (first) {
            modal.hideArrowLeft();
        } else if (last) {
            modal.hideArrowRight();
        } else {
            modal.showArrows();
        }
        const html = await fetch(currentItem.href).then(r => r.text());
        const tm = document.createElement('template');
        tm.innerHTML = html;
        const itemPageContent = tm.content.querySelector('#content').outerHTML;
        modal.setContent(itemPageContent);
    }

});


