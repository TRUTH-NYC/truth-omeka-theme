
/**
* @typedef Shopify
* @type {Object}
* @property {ShopfifyCheckout} Checkout
*/
/**
* @typedef ShopfifyCheckout  
* @property {'contact_information'|'shipping_method'|'payment_method'} step
*/

/**
* @param {string} content HTML or text content for modal body
* */

function createModal(content, options = {}) {
    const modalWrapper = document.createElement('div');
    const modalOffsetContainer = document.createElement('div');
    const modalBackDrop = document.createElement('div');
    const modalElement = document.createElement('div');
    let contentElement = modalElement;

    if (options.isolated) {
        contentElement = modalElement.attachShadow({ mode: 'open' });
    }

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
        background-color: rgba(255,255,255,.8); 
        z-index: 2;
    `);

    modalElement.setAttribute('style', `
        width: 80%;
        max-width: 600px;
        z-index: 3;
        border-radius: 8px;
        overflow-y: auto;
        height: 80vh;
    `);

    modalWrapper.append(modalOffsetContainer);
    modalOffsetContainer.append(modalBackDrop);
    modalOffsetContainer.append(modalElement);
    document.body.append(modalWrapper);

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

    modalBackDrop.addEventListener('click', close);
    return {
        modalElement,
        contentElement,
        open, close,
        setContent,
        setStyles
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

    const shadowStyles = `
    img {
        width: 100%;
    }`;
    const modal = createModal('<p> Loading... </p>', { isolated: 1 });
    modal.setStyles(shadowStyles);

    document.body.addEventListener('modal:close', e => {
        modal.setContent('<p> Loading... </p>');
    });

    [...document.querySelectorAll('.exhibit-gallery-item a, #collection-items a')].forEach(el => {
        el.addEventListener('click', async e => {
            e.preventDefault();
            modal.open();
            const html = await fetch(el.href).then(r => r.text());
            const tm = document.createElement('template');
            tm.innerHTML = html;
            const itemPageContent = tm.content.querySelector('#content').outerHTML;
            modal.setContent(itemPageContent);
        });

    });


});


