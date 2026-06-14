importScripts('https://unpkg.com/jsqr/dist/jsQR.js');

self.onmessage = function(event) {
    const { width, height, imageBuffer } = event.data;

    try {
        const pixels = new Uint8ClampedArray(imageBuffer);
        const code = jsQR(pixels, width, height, { inversionAttempts: 'attemptBoth' });

        if (code && code.data) {
            postMessage({ success: true, data: code.data });
        } else {
            postMessage({ success: false });
        }
    } catch (error) {
        postMessage({ success: false });
    }
};
