import Sortable from 'sortablejs'

document.addEventListener('alpine:initializing', () => {
    window.Alpine.data('navigationSortableContainer', ({ statePath }) => ({
        statePath,
        sortable: null,
        init() {
            this.sortable = new Sortable(this.$el, {
                group: 'nested',
                animation: 150,
                fallbackOnBody: true,
                swapThreshold: 0.50,
                draggable: '[data-sortable-item]',
                handle: '[data-sortable-handle]',
                onSort: () => {
                    this.sorted()
                }
            })
        },
        sorted() {
            this.$wire.sortNavigation(this.statePath, this.sortable.toArray())
        }
    }))
})

