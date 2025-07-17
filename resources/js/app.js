import './bootstrap';
import interact from 'interactjs';

console.log('app.js loaded!');
console.log('interact:', typeof interact);

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOMContentLoaded!');
    interact('.window').draggable({
        allowFrom: '.window-header',
        modifiers: [
            interact.modifiers.restrictRect({
                restriction: '#desktop-container', // restrict to desktop
                endOnly: true
            })
        ],
        listeners: {
            move (event) {
                const target = event.target;
                let left = parseFloat(target.style.left) || 0;
                let top = parseFloat(target.style.top) || 0;
                left += event.dx;
                top += event.dy;
                target.style.left = `${left}px`;
                target.style.top = `${top}px`;
                console.log(`Moved to left=${left}, top=${top}`);

                if (target._xLivewireComponent) {
                    target._xLivewireComponent.set('x', left);
                    target._xLivewireComponent.set('y', top);
                }
            },
        }
    })
    .resizable({
        edges: { left: false, right: true, bottom: true, top: false },
        modifiers: [
            interact.modifiers.restrictEdges({
                outer: '#desktop-container'
            }),
            interact.modifiers.restrictSize({
                min: { width: 200, height: 100 },
                max: { width: 800, height: 600 }
            })
        ],
        listeners: {
            move (event) {
                const target = event.target;
                target.style.width = `${event.rect.width}px`;
                target.style.height = `${event.rect.height}px`;

                if (target._xLivewireComponent) {
                    target._xLivewireComponent.set('width', event.rect.width);
                    target._xLivewireComponent.set('height', event.rect.height);
                }
            }
        }
    });
});
