<?php

namespace PouleR\DeezerAPI\Tests;

use PHPUnit\Framework\TestCase;
use PouleR\DeezerAPI\DeezerAPI;
use PouleR\DeezerAPI\DeezerAPIClient;

/**
 * Class DeezerAPITest
 */
class DeezerAPITest extends TestCase
{
    /**
     * @var DeezerAPIClient|\PHPUnit_Framework_MockObject_MockObject
     */
    private $client;

    /**
     * @var DeezerAPI
     */
    private $deezerApi;

    /**
     *
     */
    public function setUp()
    {
        $this->client = $this->createMock(DeezerAPIClient::class);
        $this->deezerApi = new DeezerAPI($this->client);
    }

    /**
     *
     */
    public function testDeezerAPIClient()
    {
        self::assertEquals($this->client, $this->deezerApi->getDeezerAPIClient());
    }

    /**
     *
     */
    public function testUserInformation()
    {
        $this->client->expects(static::once())
            ->method('apiRequest')
            ->with('GET', 'user/me')
            ->willReturn('{"name": "test"}');

        self::assertEquals('{"name": "test"}', $this->deezerApi->getUserInformation());
    }

    /**
     *
     */
    public function testPermissions()
    {
        $this->client->expects(static::once())
            ->method('apiRequest')
            ->with('GET', 'user/me/permissions')
            ->willReturn('{"permissions":{"basic_access":true}}');

        self::assertEquals('{"permissions":{"basic_access":true}}', $this->deezerApi->getPermissions());
    }

    /**
     *
     */
    public function testMyPlaylists()
    {
        $this->client->expects(static::once())
            ->method('apiRequest')
            ->with('GET', 'user/me/playlists')
            ->willReturn('{}');

        self::assertEquals('{}', $this->deezerApi->getMyPlaylists());
    }

    /**
     * @expectedException \PouleR\DeezerAPI\DeezerAPIException
     * @expectedExceptionMessage Create playlist: invalid title
     */
    public function testCreatePlaylistEmptyTitle()
    {
        $this->deezerApi->createPlaylist('');
    }

    /**
     *
     */
    public function testCreatePlaylist()
    {
        $this->client->expects(static::once())
            ->method('apiRequest')
            ->with('POST', 'user/me/playlists', [], 'title=New playlist')
            ->willReturn(['id' => '100']);

        self::assertEquals(['id' => '100'], $this->deezerApi->createPlaylist('New playlist'));
    }

    /**
     * @expectedException \PouleR\DeezerAPI\DeezerAPIException
     * @expectedExceptionMessage Add tracks to playlist: invalid parameters
     */
    public function testAddTracksToPlaylistEmptyPlaylist()
    {
        $this->deezerApi->addTracksToPlaylist('', 'songs');
    }

    /**
     * @expectedException \PouleR\DeezerAPI\DeezerAPIException
     * @expectedExceptionMessage Add tracks to playlist: invalid parameters
     */
    public function testAddTracksToPlaylistEmptyTracks()
    {
        $this->deezerApi->addTracksToPlaylist('playlist', []);
    }

    /**
     *
     */
    public function testAddTracksToPlaylist()
    {
        $this->client->expects(static::once())
            ->method('apiRequest')
            ->with('POST', 'playlist/12345/tracks', [], 'songs=unit,test,song')
            ->willReturn('OK');

        self::assertEquals('OK', $this->deezerApi->addTracksToPlaylist(12345, ['unit', 'test', 'song']));
    }

    /**
     *
     */
    public function testMyAlbums()
    {
        $this->client->expects(static::once())
            ->method('apiRequest')
            ->with('GET', 'user/me/albums')
            ->willReturn('{"data":[]}');

        self::assertEquals('{"data":[]}', $this->deezerApi->getMyAlbums());
    }

    /**
     * @expectedException \PouleR\DeezerAPI\DeezerAPIException
     * @expectedExceptionMessage Get album tracks: invalid albumId
     */
    public function testAlbumTracksEmptyAlbum()
    {
        $this->deezerApi->getAlbumTracks('');
    }

    /**
     *
     */
    public function testAlbumTracks()
    {
        $this->client->expects(static::once())
            ->method('apiRequest')
            ->with('GET', 'album/1234/tracks')
            ->willReturn('{}');

        self::assertEquals('{}', $this->deezerApi->getAlbumTracks(1234));
    }

    /**
     * @expectedException \PouleR\DeezerAPI\DeezerAPIException
     * @expectedExceptionMessage Favorite artist: invalid artistId
     */
    public function testArtistToFavoritesEmptyArtist()
    {
        $this->deezerApi->addArtistToFavorites('');
    }

    /**
     *
     */
    public function testArtistToFavorites()
    {
        $this->client->expects(static::once())
            ->method('apiRequest')
            ->with('POST', 'user/me/artists', [], 'artist_id=artist')
            ->willReturn('{}');

        self::assertEquals('{}', $this->deezerApi->addArtistToFavorites('artist'));
    }

    /**
     * @expectedException \PouleR\DeezerAPI\DeezerAPIException
     * @expectedExceptionMessage Follow user: invalid userId
     */
    public function testFollowUserEmptyUser()
    {
        $this->deezerApi->followUser(null);
    }

    /**
     *
     */
    public function testFollowUser()
    {
        $this->client->expects(static::once())
            ->method('apiRequest')
            ->with('POST', 'user/me/following', [], 'user_id=user')
            ->willReturn('{}');

        self::assertEquals('{}', $this->deezerApi->followUser('user'));
    }

    /**
     * @expectedException \PouleR\DeezerAPI\DeezerAPIException
     * @expectedExceptionMessage Favorite playlist: invalid playlistId
     */
    public function testPlaylistToFavoritesEmptyPlaylist()
    {
        $this->deezerApi->addPlaylistToFavorites(0);
    }

    /**
     *
     */
    public function testPlaylistToFavorites()
    {
        $this->client->expects(static::once())
            ->method('apiRequest')
            ->with('POST', 'user/me/playlists', [], 'playlist_id=1234')
            ->willReturn('{}');

        self::assertEquals('{}', $this->deezerApi->addPlaylistToFavorites(1234));
    }
}
