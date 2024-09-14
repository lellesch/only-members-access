import './index.scss'
import domReady from '@wordpress/dom-ready';
import { createRoot } from '@wordpress/element';
import { SettingsPage } from './components/SettingsPage';

domReady( () => {
    const root = createRoot(
        document.getElementById( 'only-members-access-settings' )
    );

    root.render( <SettingsPage /> );
} );
