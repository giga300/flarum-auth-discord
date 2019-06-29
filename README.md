# Flarum Auth Discord

**Flarum Auth Discord** is a extension for Flarum allow to users to login using a Discord account.

## Installation

For last **stable** version:
```
composer require giga300/flarum-auth-discord
```

For last commit on **master** branch:
```
composer require giga300/flarum-auth-discord:dev-master
```

## Configure the extension:

- Login with your Discord account on Discord Developer Portal
- Create a application
- In **Oauth2** menu, add callback URI ``http://{FORUM_URL}/auth/discord``
- Activate extension in Flarum and set Client ID & Client Secret from Discord Application infos.

## Languages
Languages availables: english :gb: french :fr:

## Translate the extension:

If you wants translate **Flarum Auth Discord** it's very simple with Git:

- Fork the repository
- Clone the forked repository on your computer
- Create a file in ``locale`` directory called ``LANGUAGE_CODE.yml`` then use the template below, you can even modify a existing locale file
- Commit all changes and push on your github repository
- Do a pull request on **Flarum Auth Discord** repository

**Lang Template**:

```yaml
giga300-auth-discord:
  admin:
    discord_settings:
      title: "DISCORD_SETTINGS_TITLE"
      client_id_label: "CLIENT_ID_LABEL"
      client_secret_label: "CLIENT_SECRET_LABEL"
  forum:
    login_button: "LOGIN_BUTTON_LABEL"
```