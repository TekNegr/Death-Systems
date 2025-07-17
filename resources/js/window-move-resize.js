import interact from 'interactjs';

document.addEventListener('DOMContentLoaded', () => {
    const position = { x: 0, y: 0 };

    interact('.draggable .window-header').draggable({
        listeners: {
            start(event) {
                // console.log('drag start', event.target);
            },
            move(event) {
                const target = event.target.closest('.window');
                position.x += event.dx;
                position.y += event.dy;
                target.style.transform = `translate(${position.x}px, ${position.y}px)`;
            },
            end(event) {
                const target = event.target.closest('.window');
                const transform = target.style.transform.match(/translate\((.*)px, (.*)px\)/);
                if (transform) {
                    const x = parseFloat(transform[1]);
                    const y = parseFloat(transform[2]);
                    // Call Livewire method to update position
                    if (target.__livewire) {
                        target.__livewire.call('updatePositionFromJs', x, y);
                    }
                }
            }
        }
    });

    interact('.resizable').resizable({
        edges: {
            top: '.resize-handle-top',
            left: '.resize-handle-left',
            bottom: '.resize-handle-bottom',
            right: '.resize-handle-right'
        },
        listeners: {
            move(event) {
                const target = event.target;
                Object.assign(target.style, {
                    width: `${event.rect.width}px`,
                    height: `${event.rect.height}px`
                });
                const transform = target.style.transform.match(/translate\((.*)px, (.*)px\)/);
                if (transform) {
                    position.x = parseFloat(transform[1]);
                    position.y = parseFloat(transform[2]);
                }
                target.style.transform = `translate(${position.x}px, ${position.y}px)`;
            },
            end(event) {
                const target = event.target;
                const width = target.offsetWidth;
                const height = target.offsetHeight;
                // Call Livewire method to update size
                if (target.__livewire) {
                    target.__livewire.call('updateSizeFromJs', width, height);
                }
            }
        },
        modifiers: [
            interact.modifiers.restrictSize({
                min: { width: 200, height: 150 },
                max: { width: 800, height: 600 }
            })
        ]
    });
});
