import './bootstrap';

import Alpine from 'alpinejs';
import interact from 'interactjs';

window.Alpine = Alpine; // Ensure only one instance of Alpine is initialized

Alpine.start();


document.addEventListener('DOMContentLoaded', () => {
    window.desktopManager = function() {
        return {
            menuOpen: false,
            toggleMenu() {
                this.menuOpen = !this.menuOpen;
            }
        };
    };
    const position = { x: 0, y: 0 };

    interact('.draggable .window-header').draggable({
        listeners: {
            start(event) {
                console.log(event.type, event.target);
            },
            move(event) {
                // Get the parent `.window` element
                const target = event.target.closest('.window');

                // Update the position
                position.x += event.dx;
                position.y += event.dy;

                // Apply the transform to the parent `.window`
                target.style.transform = `translate(${position.x}px, ${position.y}px)`;
            },
        }
    });

    interact('.resizable').resizable({
        edges: {
            top: '.resize-handle-top',    // Resize only when interacting with the top border
            left: '.resize-handle-left',  // Resize only when interacting with the left border
            bottom: '.resize-handle-bottom', // Resize only when interacting with the bottom border
            right: '.resize-handle-right' // Resize only when interacting with the right border
        },
        listeners: {
            move(event) {
                // Get the target element
                const target = event.target;

                // Update the size of the window
                Object.assign(target.style, {
                    width: `${event.rect.width}px`,
                    height: `${event.rect.height}px`
                });

                // Synchronize the position object with the current transform values
                const transform = target.style.transform.match(/translate\((.*)px, (.*)px\)/);
                if (transform) {
                    position.x = parseFloat(transform[1]);
                    position.y = parseFloat(transform[2]);
                }

                // Reapply the transform to maintain the current position
                target.style.transform = `translate(${position.x}px, ${position.y}px)`;
            },
            end(event) {
                // Synchronize the position object with the current transform values
                const target = event.target;
                const transform = target.style.transform.match(/translate\((.*)px, (.*)px\)/);
                if (transform) {
                    position.x = parseFloat(transform[1]);
                    position.y = parseFloat(transform[2]);
                }
            }
        },
        modifiers: [
            interact.modifiers.restrictSize({
                min: { width: 200, height: 150 }, // Minimum size
                max: { width: 800, height: 600 }  // Maximum size
            })
        ]
    });
});