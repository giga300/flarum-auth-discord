import { extend } from 'flarum/extend';
import app from 'flarum/app';
import LogInButtons from 'flarum/components/LogInButtons';
import LogInButton from 'flarum/components/LogInButton';

app.initializers.add('giga300-auth-discord', () => {
  extend(LogInButtons.prototype, 'items', function(items) {
    items.add('discord',
      <LogInButton
        className="Button LogInButton--discord"
        icon="fab fa-discord"
        path="/auth/discord">
        {app.translator.trans('giga300-auth-discord.forum.login_button')}
      </LogInButton>
    );
  });
});