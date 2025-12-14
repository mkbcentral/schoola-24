// Import critique - chargé immédiatement
import "./bootstrap.js";
import "../js/toats.js";
import './chart.js';
import './main.js';
import './theme-switcher.js';



// jQuery Mask Plugin - chargé uniquement si des champs avec masque sont présents
if (document.querySelector('[data-mask]') || document.querySelector('.mask-input')) {
    import("jquery-mask-plugin/dist/jquery.mask.js").then(() => {
        console.log('jQuery Mask loaded');
    }).catch(err => console.error('Error loading jQuery Mask:', err));
}

// Modal drag - à décommenter si nécessaire
// if (document.querySelector('.draggable-modal')) {
//     import('./modal-grag.js').then(() => {
//         console.log('Modal drag loaded');
//     }).catch(err => console.error('Error loading modal drag:', err));
// }
