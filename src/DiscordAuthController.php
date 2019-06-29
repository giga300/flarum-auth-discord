<?php

namespace Giga300\AuthDiscord;

use Exception;
use Flarum\Forum\Auth\Registration;
use Flarum\Forum\Auth\ResponseFactory;
use Flarum\Settings\SettingsRepositoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\RedirectResponse;
use Wohali\OAuth2\Client\Provider\Discord;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Provider\AbstractProvider;

class DiscordAuthController implements RequestHandlerInterface
{
    /**
     * @var ResponseFactory
     */
    private $response;

    /**
     * @var SettingsRepositoryInterface
     */
    private $settings;

    /**
     * @param ResponseFactory $response
     */
    public function __construct(ResponseFactory $response, SettingsRepositoryInterface $settings)
    {
        $this->response = $response;
        $this->settings = $settings;
    }

    /**
     * @param string $redirectUri
     * @return AbstractProvider
     */
    private function getProvider($redirectUri): AbstractProvider
    {
        return new Discord([
            'clientId' => $this->settings->get('giga300-auth-discord.client_id'),
            'clientSecret' => $this->settings->get('giga300-auth-discord.client_secret'),
            'redirectUri'  => $redirectUri
        ]);
    }

    /**
     * @param Request $request
     * @return ResponseInterface
     * @throws Exception
     */
    public function handle(Request $request): ResponseInterface
    {
        $redirectUri = (string) $request->getAttribute('originalUri', $request->getUri())->withQuery('');
        $provider = $this->getProvider($redirectUri);
        $session = $request->getAttribute('session');
        $queryParams = $request->getQueryParams();
        $code = array_get($queryParams, 'code');
        if (!$code) {
            $authUrl = $provider->getAuthorizationUrl($this->getAuthorizationUrlOptions());
            $session->put('oauth2state', $provider->getState());
            return new RedirectResponse($authUrl . '&display=popup');
        }
        $state = array_get($queryParams, 'state');
        if (!$state || $state !== $session->get('oauth2state')) {
            $session->remove('oauth2state');
            throw new Exception('Invalid state');
        }
        $token = $provider->getAccessToken('authorization_code', compact('code'));
        $user = $provider->getResourceOwner($token);
        $user_array = $user->toArray();
        return $this->response->make(
            'discord', $user->getEmail(),
            function (Registration $registration) use ($user_array, $user) {
                $registration
                    ->provideTrustedEmail($user_array['email'])
                    ->provideAvatar($this->getAvatar($user))
                    ->suggestUsername($user_array['username'])
                    ->setPayload($user_array);
            }
        );
    }

    /**
     * @return array
     */
    private function getAuthorizationUrlOptions(): array
    {
        return [
            'scope' =>
                [
                    'identify',
                    'email'
                ]
        ];
    }

    /**
     * @param ResourceOwnerInterface $user
     * @return string
     */
    private function getAvatar(ResourceOwnerInterface $user): string
    {
        $hash = $user->getAvatarHash();
        return isset($hash) ? "https://cdn.discordapp.com/avatars/{$user->getId()}/{$user->getAvatarHash()}.png" : '';
    }
}