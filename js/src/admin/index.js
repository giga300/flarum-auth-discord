import app from 'flarum/app';

import DiscordSettingsModal from './components/DiscordSettingsModal';

app.initializers.add('giga300-auth-discord', () => {
    app.extensionSettings['giga300-auth-discord'] = () => app.modal.show(new DiscordSettingsModal());
});