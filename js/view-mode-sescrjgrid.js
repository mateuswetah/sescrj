function buildMasonry(event) {

    if (event && event.detail && event.detail.isLoading === false) {
            setTimeout(() => {
                window.tainacanItemsListMasonry = new Masonry('#tainacan-sescrj-grid-container', {
                    // options
                    itemSelector: '.tainacan-sescrj-grid-item',
                    columnWidth: 222,
                    gutter: 32,
                    originLeft: false
                });
                imagesLoaded(document.getElementById('tainacan-sescrj-grid-container'), () => {
                    window.tainacanItemsListMasonry.layout();
                });
            }, 100);
    }
}

document.addEventListener('tainacan-items-list-is-loading-items', buildMasonry);
