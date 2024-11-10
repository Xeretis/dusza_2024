import './bootstrap';

const original = window.history.replaceState;
let timer = Date.now();

// Livewire is an evil piece of ... software. It's a great idea, but it's implemented in a way that it's very hard to work with.
// - minigyima
// Source: https://github.com/livewire/livewire/discussions/5923
let timeout = null;
let lastArgs = null;

window.history.replaceState = function (...args) {
    const time = Date.now();

    if (time - timer < 300) {
        lastArgs = args;

        if (timeout) {
            clearTimeout(timeout);
        }

        timeout = setTimeout(() => {
            original.apply(this, lastArgs);

            timeout = null;
            lastArgs = null;
        }, 100);

        return;
    }

    timer = time;

    original.apply(this, args);
};
