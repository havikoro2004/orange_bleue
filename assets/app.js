import './styles/app.scss';
import { Tooltip, Toast, Popover } from 'bootstrap';
import './bootstrap';

// Remove resend forms after refresh page script
if ( window.history.replaceState ) {
    window.history.replaceState( null, null, window.location.href );
}
