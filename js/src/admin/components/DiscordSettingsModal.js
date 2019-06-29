import SettingsModal from 'flarum/components/SettingsModal';

export default class DiscordSettingsModal extends SettingsModal {
    className() {
        return 'DiscordSettingsModal Modal--small';
    }

    title() {
        return app.translator.trans('giga300-auth-discord.admin.discord_settings.title');
    }

    form() {
        return [
            <div className="Form-group">
                <label>{app.translator.trans('giga300-auth-discord.admin.discord_settings.client_id_label')}</label>
                <input className="FormControl" bidi={this.setting('giga300-auth-discord.client_id')}></input>
            </div>,

            <div className="Form-group">
                <label>{app.translator.trans('giga300-auth-discord.admin.discord_settings.client_secret_label')}</label>
                <input className="FormControl" bidi={this.setting('giga300-auth-discord.client_secret')}></input>
            </div>
        ];
    }
}