function buildMasonry(event) {

    if (event && event.detail && event.detail.isLoading === false) {
        imagesLoaded('#tainacan-sescrj-grid-container', () => {
            setTimeout(() => {
                const tainacanItemsListMasonry = new Masonry('#tainacan-sescrj-grid-container', {
                    // options
                    itemSelector: '.tainacan-sescrj-grid-item',
                    columnWidth: 222,
                    gutter: 32,
                    originLeft: false
                });
            }, 450);
        });
    }
}

document.addEventListener('tainacan-items-list-is-loading-items', buildMasonry);
