import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Global Toast Dispatcher
Alpine.store('toast', {
    items: [],
    add(message, type = 'success') {
        const id = Date.now() + Math.random();
        this.items.push({ id, message, type });
        setTimeout(() => {
            this.remove(id);
        }, 4000);
    },
    remove(id) {
        this.items = this.items.filter(item => item.id !== id);
    }
});

// Global Cart Store
Alpine.store('cart', {
    isOpen: false,
    loading: false,
    items: [],
    itemsCount: 0,
    subtotal: 0,
    discount: 0,
    shipping: 0,
    shippingMethod: 'standard',
    tax: 0,
    total: 0,
    couponCode: '',
    freeShippingThreshold: 150,
    freeShippingProgress: 0,
    amountForFreeShipping: 150,
    qualifiesForFreeShipping: false,

    init() {
        this.fetchSummary();
    },

    toggle() {
        this.isOpen = !this.isOpen;
    },

    open() {
        this.isOpen = true;
    },

    close() {
        this.isOpen = false;
    },

    async fetchSummary() {
        try {
            const res = await fetch('/cart/summary', {
                headers: { 'Accept': 'application/json' }
            });
            if (res.ok) {
                const data = await res.json();
                this.updateState(data);
            }
        } catch (e) {
            console.error('Failed to load cart summary', e);
        }
    },

    updateState(data) {
        this.items = data.items || [];
        this.itemsCount = data.items_count || 0;
        this.subtotal = data.subtotal || 0;
        this.discount = data.discount || 0;
        this.shipping = data.shipping || 0;
        this.shippingMethod = data.shipping_method || 'standard';
        this.tax = data.tax || 0;
        this.total = data.total || 0;
        this.freeShippingThreshold = data.free_shipping_threshold || 150;
        this.freeShippingProgress = data.free_shipping_progress || 0;
        this.amountForFreeShipping = data.amount_for_free_shipping || 0;
        this.qualifiesForFreeShipping = data.qualifies_for_free_shipping || false;
        if (data.coupon) {
            this.couponCode = data.coupon.code;
        } else {
            this.couponCode = '';
        }
    },

    async addItem(productId, quantity = 1, variantId = null) {
        this.loading = true;
        try {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const res = await fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: quantity,
                    variant_id: variantId
                })
            });
            const data = await res.json();
            if (data.success) {
                this.updateState(data.cart);
                this.open();
                Alpine.store('toast').add(data.message, 'success');
            } else {
                Alpine.store('toast').add(data.message, 'error');
            }
        } catch (e) {
            Alpine.store('toast').add('Error adding item to bag.', 'error');
        } finally {
            this.loading = false;
        }
    },

    async updateQty(key, qty) {
        this.loading = true;
        try {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const res = await fetch('/cart/update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({ key, quantity: qty })
            });
            const data = await res.json();
            if (data.success) {
                this.updateState(data.cart);
            } else {
                Alpine.store('toast').add(data.message, 'error');
            }
        } catch (e) {
            Alpine.store('toast').add('Error updating quantity.', 'error');
        } finally {
            this.loading = false;
        }
    },

    async removeItem(key) {
        this.loading = true;
        try {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const res = await fetch('/cart/remove', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({ key })
            });
            const data = await res.json();
            if (data.success) {
                this.updateState(data.cart);
                Alpine.store('toast').add(data.message, 'info');
            }
        } catch (e) {
            Alpine.store('toast').add('Error removing item.', 'error');
        } finally {
            this.loading = false;
        }
    },

    async applyCoupon(code) {
        if (!code || !code.trim()) return;
        this.loading = true;
        try {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const res = await fetch('/cart/coupon', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({ code: code.trim() })
            });
            const data = await res.json();
            if (data.success) {
                this.updateState(data.cart);
                Alpine.store('toast').add(data.message, 'success');
            } else {
                Alpine.store('toast').add(data.message, 'error');
            }
        } catch (e) {
            Alpine.store('toast').add('Error applying coupon.', 'error');
        } finally {
            this.loading = false;
        }
    },

    async removeCoupon() {
        this.loading = true;
        try {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const res = await fetch('/cart/coupon', {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                }
            });
            const data = await res.json();
            if (data.success) {
                this.updateState(data.cart);
                Alpine.store('toast').add(data.message, 'info');
            }
        } catch (e) {
            Alpine.store('toast').add('Error removing coupon.', 'error');
        } finally {
            this.loading = false;
        }
    }
});

// Global Wishlist Store
Alpine.store('wishlist', {
    count: 0,
    async toggle(productId) {
        try {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const res = await fetch('/wishlist/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({ product_id: productId })
            });
            const data = await res.json();
            if (data.success) {
                this.count = data.count;
                Alpine.store('toast').add(data.message, data.added ? 'success' : 'info');
                return data.added;
            }
        } catch (e) {
            Alpine.store('toast').add('Could not update wishlist.', 'error');
        }
        return false;
    }
});

Alpine.start();
